<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;


class MenuController extends Controller
{
    /**
     * Menampilkan daftar menu
     */
    public function index()
    {
        $menus = Menu::latest()->get();

        return view(
            'admin.menus.index',
            compact('menus')
        );
    }

    /**
     * Form tambah menu
     */
    public function create()
    {
        return view('admin.menus.create');
    }

    /**
     * Simpan menu baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:255',
            'deskripsi' => 'nullable',
            'harga' => 'required|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $fotoPath = null;

        if ($request->hasFile('foto')) {

            $fotoPath = $request
                ->file('foto')
                ->store('menus', 'public');
        }

        Menu::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'foto' => $fotoPath,
        ]);

        return redirect()
            ->route('admin.menus.index')
            ->with(
                'success',
                'Menu berhasil ditambahkan'
            );
    }

    /**
     * Detail menu (opsional)
     */
    public function show(Menu $menu)
    {
        return view(
            'admin.menus.show',
            compact('menu')
        );
    }

    /**
     * Form edit menu
     */
    public function edit(Menu $menu)
    {
        return view(
            'admin.menus.edit',
            compact('menu')
        );
    }

    /**
     * Update menu
     */
    public function update(
        Request $request,
        Menu $menu
    ) {

        $request->validate([
            'nama' => 'required|max:255',
            'deskripsi' => 'nullable',
            'harga' => 'required|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $fotoPath = $menu->foto;

        if ($request->hasFile('foto')) {

            // Hapus foto lama
            $this->deletePhoto($menu);

            // Upload foto baru
            $fotoPath = $request
                ->file('foto')
                ->store('menus', 'public');
        }

        $menu->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'foto' => $fotoPath,
        ]);

        return redirect()
            ->route('admin.menus.index')
            ->with(
                'success',
                'Menu berhasil diperbarui'
            );
    }

    /**
     * Hapus menu
     */
    public function destroy(Menu $menu)
    {
        // SEC-013 (ORPH-01): tanpa ini file gambar tertinggal di disk
        // public setelah baris database dihapus.
        $this->deletePhoto($menu);

        $menu->delete();

        return redirect()
            ->route('admin.menus.index')
            ->with(
                'success',
                'Menu berhasil dihapus'
            );
    }

    /**
     * Hapus file foto menu dari disk public bila ada.
     */
    private function deletePhoto(Menu $menu): void
    {
        if (
            $menu->foto &&
            Storage::disk('public')->exists($menu->foto)
        ) {

            Storage::disk('public')
                ->delete($menu->foto);
        }
    }
}
