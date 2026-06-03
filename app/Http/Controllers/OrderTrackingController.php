<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function index()
    {
        return view('orders.track');
    }

    public function search(Request $request)
    {
        $request->validate([
            'keyword' => 'required'
        ]);

        $orders = Order::with('package')
            ->where('invoice_number', $request->keyword)
            ->orWhere('customer_name', 'like', '%' . $request->keyword . '%')
            ->latest()
            ->get();

        return view(
            'orders.track',
            compact('orders')
        );
    }
}