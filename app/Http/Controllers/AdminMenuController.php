<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class AdminMenuController extends Controller
{
    public function index()
    {
        $menus = Menu::all();

        return view(
            'admin.menus.index',
            compact('menus')
        );
    }

    public function create()
    {
        return view('admin.menus.create');
    }

    public function store(Request $request)
    {
        Menu::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
        ]);

        return redirect()
            ->route('admin.menus.index');
    }

    public function edit(Menu $menu)
    {
        return view(
            'admin.menus.edit',
            compact('menu')
        );
    }

    public function update(Request $request, Menu $menu)
    {
        $menu->update($request->all());

        return redirect()
            ->route('admin.menus.index');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return back();
    }
}