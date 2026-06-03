@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto px-6 py-12">

    <div
        class="bg-white dark:bg-gray-800
               border border-gray-200 dark:border-gray-700
               rounded-2xl shadow p-8">

        <h1
            class="text-3xl font-bold
                   text-gray-900 dark:text-white">

            {{ $package->nama_paket }}

        </h1>

        <p
            class="mt-6 text-gray-600 dark:text-gray-300">

            {{ $package->deskripsi }}

        </p>

        <div
            class="mt-6 text-3xl font-bold text-green-600">

            Rp {{ number_format($package->harga) }}

        </div>

        <div class="mt-8">

            <a
                href="{{ route('orders.create', [
                    'package' => $package->id
                ]) }}"
                class="bg-orange-500 hover:bg-orange-600
                       text-white px-6 py-3 rounded-xl">

                Pesan Paket Ini

            </a>

        </div>

    </div>

</div>

@endsection