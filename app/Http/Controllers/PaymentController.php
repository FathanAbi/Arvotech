<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function upload(Request $request, Order $order)
    {
        $request->validate([
            'proof_image' => 'required|image|max:2048'
        ]);

        $path = $request->file('proof_image')
            ->store('payments', 'public');

        Payment::create([
            'order_id' => $order->id,
            'proof_image' => $path,
            'status' => 'pending'
        ]);

        $order->update([
            'status' => 'waiting_verification'
        ]);

        return back()
            ->with('success', 'Bukti transfer berhasil diupload');
    }
}

public function upload(Request $request, Order $order)
{
    $path = $request
        ->file('proof_image')
        ->store('payments');

    Payment::create([
        'order_id' => $order->id,
        'proof_image' => $path
    ]);

    $order->update([
        'status' =>
        'waiting_verification'
    ]);

    return back();
}
