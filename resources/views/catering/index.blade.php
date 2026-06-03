@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-6 py-12">

    <h1
        class="text-4xl font-bold text-center mb-10
               text-gray-900 dark:text-white">

        Paket Catering

    </h1>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

        @foreach($packages as $package)

            <div
                class="bg-white dark:bg-gray-800
                       border border-gray-200 dark:border-gray-700
                       rounded-2xl shadow p-6">

                <h2
                    class="text-xl font-bold
                           text-gray-900 dark:text-white">

                    {{ $package->nama_paket }}

                </h2>

                <p
                    class="mt-3 text-gray-600 dark:text-gray-300">

                    {{ $package->deskripsi }}

                </p>

                <div
                    class="mt-4 text-2xl font-bold text-green-600">

                    Rp {{ number_format($package->harga) }}

                </div>

                <a
                    href="{{ route('catering.show', $package) }}"
                    class="mt-6 inline-block
                           bg-orange-500 hover:bg-orange-600
                           text-white px-5 py-3 rounded-xl">

                    Lihat Detail

                </a>

            </div>

        @endforeach

    </div>

</div>

@endsection