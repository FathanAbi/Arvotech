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
        CateringPackage::create($this->validated($request));

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
        $cateringPackage->update($this->validated($request));

        return redirect()
            ->route('admin.catering-packages.index');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'nama_paket' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
        ]);
    }

    public function destroy(
        CateringPackage $cateringPackage
    ) {
        if ($cateringPackage->orders()->exists()) {
            return back()->with(
                'error',
                'Paket tidak dapat dihapus karena masih memiliki pesanan terkait.'
            );
        }

        $cateringPackage->delete();

        return back()->with('success', 'Paket berhasil dihapus.');
    }
}
