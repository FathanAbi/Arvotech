@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto px-6 py-12">

    <div
        class="bg-white dark:bg-gray-800
               border border-gray-200 dark:border-gray-700
               rounded-2xl shadow p-8">

        <h1
            class="text-3xl font-bold mb-6
                   text-gray-900 dark:text-white">

            Detail Pesanan

        </h1>

        <div class="space-y-4">

            <div>
                <strong>No Invoice:</strong>
                {{ $order->invoice_number }}
            </div>

            <div>
                <strong>Nama:</strong>
                {{ $order->customer_name }}
            </div>

            <div>
                <strong>Email:</strong>
                {{ $order->email }}
            </div>

            <div>
                <strong>Telepon:</strong>
                {{ $order->phone }}
            </div>

            <div>
                <strong>Paket:</strong>
                {{ $order->package->nama_paket }}
            </div>

            <div>
                <strong>Jumlah:</strong>
                {{ $order->quantity }}
            </div>

            <div>
                <strong>Total:</strong>
                Rp {{ number_format($order->total_price) }}
            </div>

            <div>
                <strong>Status:</strong>

                <span
                    class="bg-yellow-100
                           text-yellow-800
                           px-3 py-1 rounded-full">

                    {{ $order->status }}

                </span>

            </div>

        </div>

        <div
            class="mt-8 p-5 rounded-xl
                   bg-orange-50 dark:bg-orange-900/20">

            <h3
                class="font-bold text-orange-600">

                Informasi Pembayaran

            </h3>

            <p class="mt-2">

                Silakan transfer ke rekening berikut:

            </p>

            <div class="mt-4">

                <p>
                    Bank BCA
                </p>

                <p>
                    1234567890
                </p>

                <p>
                    a.n. Lontong Balap Cak Gendut
                </p>

            </div>

            <p class="mt-4 text-sm">

                Setelah pembayaran diverifikasi admin,
                status pesanan akan diperbarui.

            </p>

        </div>

    </div>

</div>

@endsection