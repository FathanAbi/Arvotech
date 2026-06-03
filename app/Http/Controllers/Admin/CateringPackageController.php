<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CateringPackage;
use Illuminate\Http\Request;

class CateringPackageController extends Controller
{
    public function index()
    {
        $packages = CateringPackage::latest()->get();

        return view(
            'admin.catering-packages.index',
            compact('packages')
        );
    }

    public function create()
    {
        return view(
            'admin.catering-packages.create'
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_paket' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required|numeric'
        ]);

        CateringPackage::create(
            $request->only([
                'nama_paket',
                'deskripsi',
                'harga'
            ])
        );

        return redirect()
            ->route('admin.catering-packages.index')
            ->with(
                'success',
                'Paket berhasil ditambahkan'
            );
    }

    public function edit(CateringPackage $cateringPackage)
    {
        return view(
            'admin.catering-packages.edit',
            compact('cateringPackage')
        );
    }

    public function update(
        Request $request,
        CateringPackage $cateringPackage
    ) {
        $cateringPackage->update([
            'nama_paket' => $request->nama_paket,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga
        ]);

        return redirect()
            ->route('admin.catering-packages.index');
    }

    public function destroy(
        CateringPackage $cateringPackage
    ) {
        $cateringPackage->delete();

        return back();
    }
}