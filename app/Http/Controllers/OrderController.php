<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\CateringPackage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class OrderController extends Controller
{
    /**
     * Masa berlaku tautan detail pesanan yang dikirim setelah submit.
     * Cukup panjang agar pelanggan masih bisa kembali mengunggah bukti bayar.
     */
    private const SHOW_LINK_TTL_DAYS = 7;

    /**
     * Masa berlaku tautan yang dibuat saat halaman detail dibuka.
     */
    private const UPLOAD_LINK_TTL_HOURS = 2;

    /**
     * Tautan detail pesanan bertanda tangan (SEC-002).
     * Tanpa signature, /orders/{id} tidak dapat diakses publik.
     */
    private function signedShowUrl(Order $order): string
    {
        return URL::temporarySignedRoute(
            'orders.show',
            now()->addDays(self::SHOW_LINK_TTL_DAYS),
            ['order' => $order->id]
        );
    }

    /**
     * Tautan unggah bukti pembayaran bertanda tangan (SEC-002).
     */
    private function signedUploadUrl(Order $order): string
    {
        return URL::temporarySignedRoute(
            'orders.upload-proof',
            now()->addHours(self::UPLOAD_LINK_TTL_HOURS),
            ['order' => $order->id]
        );
    }

    /**
     * Tautan bukti pembayaran bertanda tangan (SEC-007).
     * Berkas disimpan di disk privat, jadi hanya tautan bertanda tangan
     * yang bisa membukanya.
     */
    private function signedProofUrl(Order $order): string
    {
        return URL::temporarySignedRoute(
            'orders.payment-proof',
            now()->addHours(self::UPLOAD_LINK_TTL_HOURS),
            ['order' => $order->id]
        );
    }

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
            ->to($this->signedShowUrl($order))
            ->with(
                'success',
                'Pesanan berhasil dibuat. Silakan lakukan pembayaran dan tunggu verifikasi admin.'
            );
    }

    /**
     * Detail pesanan
     *
     * SEC-002 (IDOR-01): rute ini dilindungi middleware `signed`, sehingga
     * hanya bisa dibuka lewat tautan bertanda tangan — bukan dengan menebak
     * ID berurutan.
     */
    public function show(Order $order)
    {
        $order->load('package');

        return view(
            'orders.show',
            [
                'order' => $order,
                'uploadUrl' => $this->signedUploadUrl($order),
                'proofUrl' => $order->payment_proof
                    ? $this->signedProofUrl($order)
                    : null,
            ]
        );
    }

    /**
     * SEC-007 (STOR-01): stream bukti pembayaran dari disk privat.
     * Hanya dapat diakses lewat tautan bertanda tangan.
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

    public function uploadProof(
        Request $request,
        Order $order
    ) {
        $request->validate([
            'payment_proof' =>
                'required|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        if (
            $order->payment_proof &&
            Storage::disk('local')->exists(
                $order->payment_proof
            )
        ) {
            Storage::disk('local')
                ->delete(
                    $order->payment_proof
                );
        }

        $path = $request
            ->file('payment_proof')
            ->store(
                'payment-proofs',
                'local'
            );

        $order->update([
            'payment_proof' => $path,
            'status' => 'waiting_verification'
        ]);

        // Kembali ke halaman detail lewat tautan bertanda tangan yang baru,
        // bukan `back()`, agar referer yang sudah kedaluwarsa tidak 403.
        return redirect()
            ->to($this->signedShowUrl($order))
            ->with(
                'success',
                'Bukti pembayaran berhasil diupload dan menunggu verifikasi admin.'
            );
    }
}
