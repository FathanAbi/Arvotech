@extends('layouts.admin')

@section('content')

<div class="max-w-5xl mx-auto">

    <div class="flex justify-between items-center mb-8">

        <div>

            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Detail Pesanan
            </h1>

            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Invoice: {{ $order->invoice_number }}
            </p>

        </div>

        <a
            href="{{ route('admin.orders.index') }}"
            class="px-4 py-2 rounded-lg
                   bg-gray-200 hover:bg-gray-300
                   dark:bg-gray-700 dark:hover:bg-gray-600
                   text-gray-800 dark:text-white">

            Kembali

        </a>

    </div>

    <div class="grid lg:grid-cols-2 gap-6">

        <!-- Informasi Pelanggan -->
        <div
            class="bg-white dark:bg-gray-800
                   border border-gray-200 dark:border-gray-700
                   rounded-2xl shadow-sm p-6">

            <h2 class="text-xl font-bold mb-6 text-gray-900 dark:text-white">
                Informasi Pelanggan
            </h2>

            <div class="space-y-4">

                <div>
                    <p class="text-sm text-gray-500">Nama</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $order->customer_name }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $order->email }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Telepon</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $order->phone }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Tanggal Acara</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $order->event_date }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Alamat Pengiriman</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $order->delivery_address }}
                    </p>
                </div>

            </div>

        </div>

        <!-- Informasi Pesanan -->
        <div
            class="bg-white dark:bg-gray-800
                   border border-gray-200 dark:border-gray-700
                   rounded-2xl shadow-sm p-6">

            <h2 class="text-xl font-bold mb-6 text-gray-900 dark:text-white">
                Informasi Pesanan
            </h2>

            <div class="space-y-4">

                <div>
                    <p class="text-sm text-gray-500">Paket Catering</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $order->package->nama_paket }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Jumlah</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $order->quantity }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Total Harga</p>
                    <p class="font-bold text-green-600 text-xl">
                        Rp {{ number_format($order->total_price) }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Status Saat Ini</p>

                    <span
                        class="inline-flex px-3 py-1 rounded-full
                               bg-yellow-100 text-yellow-800">

                        {{ $order->status }}

                    </span>
                </div>

            </div>

        </div>

    </div>

    <!-- Bukti Transfer -->
    <div
        class="mt-6 bg-white dark:bg-gray-800
            border border-gray-200 dark:border-gray-700
            rounded-2xl shadow-sm p-6">

        <h2
            class="text-xl font-bold mb-6
                text-gray-900 dark:text-white">

            Bukti Pembayaran

        </h2>

        @if($order->payment_proof)

            <div class="space-y-4">

                <img
                    src="{{ asset('storage/' . $order->payment_proof) }}"
                    alt="Bukti Pembayaran"
                    class="max-w-xl rounded-xl border border-gray-200 dark:border-gray-700 shadow">

                <a
                    href="{{ asset('storage/' . $order->payment_proof) }}"
                    target="_blank"
                    class="inline-flex items-center px-4 py-2
                        bg-blue-600 hover:bg-blue-700
                        text-white rounded-lg">

                    Lihat Ukuran Penuh

                </a>

            </div>

        @else

            <div
                class="bg-yellow-50 dark:bg-yellow-900/20
                    border border-yellow-200 dark:border-yellow-700
                    rounded-xl p-4">

                <p class="text-yellow-700 dark:text-yellow-300">

                    Pelanggan belum mengunggah bukti pembayaran.

                </p>

            </div>

        @endif

    </div>

    <!-- Update Status -->
    <div
        class="mt-6 bg-white dark:bg-gray-800
               border border-gray-200 dark:border-gray-700
               rounded-2xl shadow-sm p-6">

        <h2 class="text-xl font-bold mb-6 text-gray-900 dark:text-white">
            Update Status Pesanan
        </h2>

        <form
            method="POST"
            action="{{ route('admin.orders.status', $order) }}">

            @csrf
            @method('PATCH')

            <div class="flex flex-col md:flex-row gap-4">

                <select
                    name="status"
                    class="flex-1 px-4 py-3 rounded-xl
                        border border-gray-300 dark:border-gray-600
                        bg-white dark:bg-gray-700
                        text-gray-900 dark:text-white">

                    <option
                        value="pending_payment"
                        @selected($order->status == 'pending_payment')>

                        Pending Payment

                    </option>

                    <option
                        value="waiting_verification"
                        @selected($order->status == 'waiting_verification')>

                        Waiting Verification

                    </option>

                    <option
                        value="processing"
                        @selected($order->status == 'processing')>

                        Processing

                    </option>

                    <option
                        value="shipping"
                        @selected($order->status == 'shipping')>

                        Shipping

                    </option>

                    <option
                        value="completed"
                        @selected($order->status == 'completed')>

                        Completed

                    </option>

                    <option
                        value="rejected"
                        @selected($order->status == 'rejected')>

                        Rejected

                    </option>

                </select>

                <button
                    type="submit"
                    class="px-6 py-3 rounded-xl
                           bg-green-600 hover:bg-green-700
                           text-white font-semibold">

                    Update Status

                </button>

            </div>

        </form>

    </div>

</div>

@endsection