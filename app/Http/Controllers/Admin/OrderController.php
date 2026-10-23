<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('package')
            ->latest()
            ->paginate(10);

        return view(
            'admin.orders.index',
            compact('orders')
        );
    }

    public function show(Order $order)
    {
        $order->load('package');

        return view(
            'admin.orders.show',
            compact('order')
        );
    }

    /**
     * SEC-007 (STOR-01): stream bukti pembayaran dari disk privat.
     * Dilindungi middleware `['auth', 'admin']`.
     */
    public function paymentProof(Order $order)
    {
        abort_unless(
            $order->payment_proof &&
            Storage::disk('local')->exists($order->payment_proof),
            404
        );

        return Storage::disk('local')
            ->response($order->payment_proof);
    }

    public function updateStatus(
        Request $request,
        Order $order
    ) {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in([
                    'pending_payment',
                    'waiting_verification',
                    'processing',
                    'shipping',
                    'completed',
                    'rejected',
                ]),
            ],
        ]);

        $order->update([
            'status' => $validated['status']
        ]);

        return back()->with(
            'success',
            'Status pesanan berhasil diperbarui'
        );
    }
}