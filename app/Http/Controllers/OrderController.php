<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\CateringPackage;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Form pemesanan catering
     */
    public function create(Request $request)
    {
        $packages = CateringPackage::orderBy('nama_paket')
            ->get();

        $selectedPackage = $request->package;

        return view(
            'orders.create',
            compact(
                'packages',
                'selectedPackage'
            )
        );
    }

    /**
     * Simpan pesanan
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => [
                'required',
                'max:255'
            ],
            'email' => [
                'required',
                'email'
            ],
            'phone' => [
                'required',
                'max:30'
            ],
            'event_date' => [
                'required',
                'date',
                'after_or_equal:today'
            ],
            'delivery_address' => [
                'required'
            ],
            'package_id' => [
                'required',
                'exists:catering_packages,id'
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1'
            ]
        ]);

        $package = CateringPackage::findOrFail(
            $validated['package_id']
        );

        $order = Order::create([
            'invoice_number' => 'INV-' .
                now()->format('YmdHis') .
                '-' .
                rand(100, 999),

            'customer_name' =>
                $validated['customer_name'],

            'email' =>
                $validated['email'],

            'phone' =>
                $validated['phone'],

            'event_date' =>
                $validated['event_date'],

            'delivery_address' =>
                $validated['delivery_address'],

            'package_id' =>
                $validated['package_id'],

            'quantity' =>
                $validated['quantity'],

            'total_price' =>
                $package->harga *
                $validated['quantity'],

            'status' =>
                'pending_payment'
        ]);

        return redirect()
            ->route(
                'orders.show',
                $order
            )
            ->with(
                'success',
                'Pesanan berhasil dibuat. Silakan lakukan pembayaran dan tunggu verifikasi admin.'
            );
    }

    /**
     * Detail pesanan
     */
    public function show(Order $order)
    {
        $order->load('package');

        return view(
            'orders.show',
            compact('order')
        );
    }

    public function uploadProof(
        Request $request,
        Order $order
    )
    {
        $request->validate([
            'payment_proof' =>
                'required|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        if (
            $order->payment_proof &&
            Storage::disk('public')->exists(
                $order->payment_proof
            )
        ) {
            Storage::disk('public')
                ->delete(
                    $order->payment_proof
                );
        }

        $path = $request
            ->file('payment_proof')
            ->store(
                'payment-proofs',
                'public'
            );

        $order->update([
            'payment_proof' => $path,
            'status' => 'waiting_verification'
        ]);

        return back()->with(
            'success',
            'Bukti pembayaran berhasil diupload dan menunggu verifikasi admin.'
        );
    }
}