@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto px-6 py-12">

    <h1
        class="text-4xl font-bold text-center
               text-gray-900 dark:text-white">

        Cek Status Pesanan

    </h1>

    <p
        class="mt-3 text-center
               text-gray-600 dark:text-gray-400">

        Masukkan nomor invoice pesanan Anda.

    </p>

    <form
        action="{{ route('orders.track.search') }}"
        method="POST"
        class="mt-8">

        @csrf

        <div class="flex gap-3">

            <input
                type="text"
                name="keyword"
                placeholder="INV-20260601001"
                class="flex-1 px-4 py-3 rounded-xl
                       border border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-800
                       text-gray-900 dark:text-white">

            <button
                class="bg-orange-500 hover:bg-orange-600
                       text-white px-6 rounded-xl">

                Cari

            </button>

        </div>

        @error('keyword')

            <p class="mt-2 text-red-500 text-sm">
                {{ $message }}
            </p>

        @enderror

    </form>

    @isset($orders)

        <div class="mt-10 space-y-4">

            @forelse($orders as $order)

                @php

                    $badge = match($order->status){

                        'pending_payment'
                            => 'bg-gray-100 text-gray-800',

                        'waiting_verification'
                            => 'bg-yellow-100 text-yellow-800',

                        'processing'
                            => 'bg-blue-100 text-blue-800',

                        'shipping'
                            => 'bg-purple-100 text-purple-800',

                        'completed'
                            => 'bg-green-100 text-green-800',

                        'rejected'
                            => 'bg-red-100 text-red-800',

                        default
                            => 'bg-gray-100 text-gray-800'
                    };

                @endphp

                <div
                    class="bg-white dark:bg-gray-800
                        border border-gray-200 dark:border-gray-700
                        rounded-2xl p-6">

                    <div
                        class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">

                        <div>

                            <h3
                                class="font-bold text-lg
                                    text-gray-900 dark:text-white">

                                {{ $order->invoice_number }}

                            </h3>

                            <p
                                class="text-gray-600 dark:text-gray-400">

                                {{ $order->customer_name }}

                            </p>

                            <p
                                class="mt-2 text-sm
                                    text-gray-500 dark:text-gray-400">

                                Paket:
                                {{ $order->package->nama_paket }}

                            </p>

                            <p
                                class="text-sm
                                    text-gray-500 dark:text-gray-400">

                                Total:
                                Rp {{ number_format($order->total_price) }}

                            </p>

                        </div>

                        <div class="flex flex-col items-end gap-3">

                            <span
                                class="px-3 py-1 rounded-full text-sm {{ $badge }}">

                                {{ ucwords(str_replace('_',' ', $order->status)) }}

                            </span>

                            <a
                                href="{{ $showUrls[$order->id] ?? '#' }}"
                                class="bg-orange-500 hover:bg-orange-600
                                    text-white px-4 py-2 rounded-lg
                                    text-sm font-medium transition">

                                Lihat Detail

                            </a>

                        </div>

                    </div>

                </div>

            @empty

                <div
                    class="bg-red-50 dark:bg-red-900/20
                           border border-red-200 dark:border-red-700
                           p-4 rounded-xl">

                    Pesanan tidak ditemukan.

                </div>

            @endforelse

        </div>

    @endisset

</div>

@endsection