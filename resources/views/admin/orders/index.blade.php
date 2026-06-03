@extends('layouts.admin')

@section('content')

<div class="max-w-7xl mx-auto">

    <div class="mb-8">

        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            Kelola Pesanan
        </h1>

    </div>

    <div
        class="bg-white dark:bg-gray-800
               border border-gray-200 dark:border-gray-700
               rounded-2xl shadow-sm overflow-hidden">

        <table class="w-full">

            <thead
                class="bg-gray-100 dark:bg-gray-700">

                <tr>

                    <th class="p-4 text-left">
                        Invoice
                    </th>

                    <th class="p-4 text-left">
                        Pelanggan
                    </th>

                    <th class="p-4 text-left">
                        Paket
                    </th>

                    <th class="p-4 text-left">
                        Total
                    </th>

                    <th class="p-4 text-left">
                        Status
                    </th>

                    <th class="p-4 text-left">
                        Aksi
                    </th>

                </tr>

            </thead>

            <tbody>

                @foreach($orders as $order)

                <tr
                    class="border-t border-gray-200 dark:border-gray-700">

                    <td class="p-4">
                        {{ $order->invoice_number }}
                    </td>

                    <td class="p-4">
                        {{ $order->customer_name }}
                    </td>

                    <td class="p-4">
                        {{ $order->package->nama_paket }}
                    </td>

                    <td class="p-4">
                        Rp {{ number_format($order->total_price) }}
                    </td>

                    <td class="p-4">

                        <span
                            class="px-3 py-1 rounded-full text-sm bg-yellow-100 text-yellow-800">

                            {{ $order->status }}

                        </span>

                    </td>

                    <td class="p-4">

                        <a
                            href="{{ route('admin.orders.show',$order) }}"
                            class="text-blue-500">

                            Detail

                        </a>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

    <div class="mt-6">

        {{ $orders->links() }}

    </div>

</div>

@endsection