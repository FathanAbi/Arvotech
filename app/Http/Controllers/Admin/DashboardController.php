<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\CateringPackage;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalMenus' => Menu::count(),
            'totalPackages' => CateringPackage::count(),
            'totalOrders' => Order::count(),
        ]);
    }
}