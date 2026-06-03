@extends('layouts.app')

@section('content')

<section
class="relative overflow-hidden">

<div
class="max-w-7xl mx-auto px-6 py-20">

    <div
    class="grid lg:grid-cols-2 gap-12 items-center">

        <div>

            <span
            class="text-orange-500 font-semibold">

                Kuliner Khas Surabaya

            </span>

            <h1
            class="text-5xl font-extrabold mt-4">

                Lontong Balap
                Cak Gendut

            </h1>

            <p
            class="mt-6 text-lg
            text-gray-600
            dark:text-gray-300">

                Menyajikan lontong balap
                autentik dengan cita rasa
                khas Surabaya sejak lama.

            </p>

            <div
            class="mt-8 flex gap-4">

                <a
                href="#menu"
                class="bg-orange-500
                text-white
                px-6 py-3 rounded-xl">

                    Lihat Menu

                </a>

                <a
                    href="{{ route('catering.index') }}"
                    class="border border-orange-500
                        text-orange-500
                        px-6 py-3 rounded-xl">

                    Lihat Paket Catering

                </a>

            </div>

        </div>

        <div>

            <img
            src="/images/lontong-balap.jpg"
            alt="Lontong Balap Cak Gendut"
            class="w-60 max-w-lg object-cover rounded-3xl shadow-2xl mx-auto">

        </div>

    </div>

</div>

</section>

<section
id="menu"
class="py-10">

<div
class="max-w-7xl mx-auto px-6">

    <h2
    class="text-3xl font-bold mb-10">

        Menu Favorit

    </h2>

    <div
    class="grid
    md:grid-cols-2
    lg:grid-cols-3
    gap-6">

        @foreach($menus as $menu)

        <div
        class="bg-white
        dark:bg-gray-800
        rounded-2xl
        shadow p-6">

            <h3
            class="font-bold text-xl">

                {{ $menu->nama }}

            </h3>

            <p
            class="text-gray-500
            dark:text-gray-300 mt-2">

                {{ $menu->deskripsi }}

            </p>

            <p
            class="text-orange-500
            font-bold mt-4">

                Rp
                {{ number_format($menu->harga) }}

            </p>

        </div>

        @endforeach

    </div>

</div>

</section>

<!-- Informasi Usaha -->
<section class="py-16 bg-gray-50 dark:bg-gray-800">

    <div class="max-w-7xl mx-auto px-6">

        <h2
            class="text-3xl font-bold mb-10 text-left
                   text-gray-900 dark:text-white">

            Informasi Usaha

        </h2>

        <div class="grid lg:grid-cols-2 gap-8">

            <!-- Detail -->
            <div
                class="bg-white dark:bg-gray-900
                       rounded-2xl shadow p-8">

                <h3
                    class="text-2xl font-bold mb-6
                           text-gray-900 dark:text-white">

                    Lontong Balap Cak Gendut

                </h3>

                <div class="space-y-6">

                    <!-- Jam Buka -->
                    <div>

                        <h4
                            class="font-semibold text-orange-500">

                            🕒 Jam Operasional

                        </h4>

                        <div
                            class="mt-2 text-gray-700 dark:text-gray-300">

                            <p>
                                Senin - Jumat
                            </p>

                            <p class="font-medium">
                                09:00 - 17:00
                            </p>

                            <p class="mt-3">
                                Sabtu - Minggu
                            </p>

                            <p class="font-medium">
                                13:00 - 21:00
                            </p>

                        </div>

                    </div>

                    <!-- Lokasi -->
                    <div>

                        <h4
                            class="font-semibold text-orange-500">

                            📍 Lokasi

                        </h4>

                        <p
                            class="mt-2 text-gray-700 dark:text-gray-300">

                            Jl. Contoh No. 123, Surabaya,
                            Jawa Timur

                        </p>

                    </div>

                    <!-- Maps Link -->
                    <div>

                        <h4
                            class="font-semibold text-orange-500">

                            🗺️ Google Maps

                        </h4>

                        <a
                            href="https://maps.google.com"
                            target="_blank"
                            class="inline-block mt-2
                                   text-blue-600 hover:underline">

                            Buka di Google Maps

                        </a>

                    </div>

                </div>

            </div>

            <!-- Embed Maps -->
            <div
                class="rounded-2xl overflow-hidden shadow-lg">

                <iframe
                    src="https://www.google.com/maps?q=Surabaya&output=embed"
                    width="100%"
                    height="450"
                    style="border:0;"
                    loading="lazy"
                    allowfullscreen="">
                </iframe>

            </div>

        </div>

    </div>

</section>

@endsection