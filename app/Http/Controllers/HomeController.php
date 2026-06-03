<?php

namespace App\Http\Controllers;
use App\Models\Menu;
use App\Models\CateringPackage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $menus = Menu::all();
        $packages = CateringPackage::all();

        return view('home', compact(
            'menus',
            'packages'
        ));
    }
}
