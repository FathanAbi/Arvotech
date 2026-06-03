<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
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
        ]);

        Menu::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
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
        ]);

        $menu->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
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
        $menu->delete();

        return redirect()
            ->route('admin.menus.index')
            ->with(
                'success',
                'Menu berhasil dihapus'
            );
    }
}