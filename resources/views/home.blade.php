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

                            Jalan Embong Malang 38, Surabaya,
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
                            href="https://maps.app.goo.gl/G1gVA9z1MPsSs65K9"
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

                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d253303.43783176574!2d112.42670129453121!3d-7.256063299999992!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7f95d917c4fdf%3A0x49db77b7799abc54!2sLontong%20Balap%20Asli%20Pak%20Gendut%20(Tidak%20Buka%20Cabang)!5e0!3m2!1sid!2sid!4v1780642299721!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

            </div>

        </div>

    </div>

</section>

@endsection