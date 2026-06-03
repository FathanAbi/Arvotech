<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->paginate(10);

        return view(
            'admin.orders.index',
            compact('orders')
        );
    }

    public function edit(Order $order)
    {
        return view(
            'admin.orders.edit',
            compact('order')
        );
    }

    public function update(Request $request, Order $order)
    {
        $order->update([
            'status' => $request->status
        ]);

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Status berhasil diubah');
    }
}