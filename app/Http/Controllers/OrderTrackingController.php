<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class OrderTrackingController extends Controller
{
    /**
     * Masa berlaku tautan detail dari halaman pelacakan.
     */
    private const SHOW_LINK_TTL_HOURS = 2;

    public function index()
    {
        return view('orders.track');
    }

    /**
     * SEC-002 (IDOR-01) + SEC-003 (INFO-01): pencarian dibatasi pada
     * kecocokan persis nomor invoice. Pencarian wildcard pada nama
     * sebelumnya memungkinkan pengambilan seluruh data pesanan sekaligus,
     * dan kini akan ikut membocorkan tautan detail bertanda tangan.
     */
    public function search(Request $request)
    {
        $request->validate([
            'keyword' => [
                'required',
                'string',
                'max:255',
            ]
        ]);

        $orders = Order::with('package')
            ->where('invoice_number', $request->keyword)
            ->latest()
            ->get();

        // Tautan detail bertanda tangan, satu per pesanan yang ditemukan.
        $showUrls = $orders->mapWithKeys(
            fn (Order $order) => [
                $order->id => URL::temporarySignedRoute(
                    'orders.show',
                    now()->addHours(self::SHOW_LINK_TTL_HOURS),
                    ['order' => $order->id]
                ),
            ]
        );

        return view(
            'orders.track',
            compact('orders', 'showUrls')
        );
    }
}
