<?php

namespace App\Http\Controllers;

use App\Models\CateringPackage;

class CateringController extends Controller
{
    public function index()
    {
        $packages = CateringPackage::all();

        return view(
            'catering.index',
            compact('packages')
        );
    }

    public function show(CateringPackage $package)
    {
        return view(
            'catering.show',
            compact('package')
        );
    }
}