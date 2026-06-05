@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors">


<div class="max-w-7xl mx-auto px-6 py-8">

    <!-- Header -->
    <div class="mb-8">

        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            Dashboard Admin
        </h1>

        <p class="mt-2 text-gray-600 dark:text-gray-400">
            Kelola menu, paket catering, dan pesanan pelanggan.
        </p>

    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Total Menu
                    </p>

                    <h2 class="mt-2 text-4xl font-bold text-orange-500">
                        {{ $totalMenus }}
                    </h2>

                </div>

                <div
                    class="h-14 w-14 rounded-xl bg-orange-100 dark:bg-orange-500/20 flex items-center justify-center">

                    🍽️

                </div>

            </div>

        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Paket Catering
                    </p>

                    <h2 class="mt-2 text-4xl font-bold text-green-500">
                        {{ $totalPackages }}
                    </h2>

                </div>

                <div
                    class="h-14 w-14 rounded-xl bg-green-100 dark:bg-green-500/20 flex items-center justify-center">

                    📦

                </div>

            </div>

        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Total Pesanan
                    </p>

                    <h2 class="mt-2 text-4xl font-bold text-blue-500">
                        {{ $totalOrders }}
                    </h2>

                </div>

                <div
                    class="h-14 w-14 rounded-xl bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center">

                    🛒

                </div>

            </div>

        </div>

    </div>

    <!-- Quick Actions -->
    <div class="mt-10">

        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
            Menu Pengelolaan
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <a
                href="{{ route('admin.menus.index') }}"
                class="group bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 shadow-sm hover:shadow-lg transition">

                <div class="flex items-center gap-4">

                    <div
                        class="h-14 w-14 rounded-xl bg-orange-100 dark:bg-orange-500/20 flex items-center justify-center text-2xl">

                        🍜

                    </div>

                    <div>

                        <h3
                            class="text-lg font-bold text-gray-900 dark:text-white">

                            Kelola Menu

                        </h3>

                        <p
                            class="text-sm text-gray-600 dark:text-gray-400 mt-1">

                            Tambah, ubah, dan hapus menu makanan.

                        </p>

                    </div>

                </div>

            </a>

            <a
                href="{{ route('admin.catering-packages.index') }}"
                class="group bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 shadow-sm hover:shadow-lg transition">

                <div class="flex items-center gap-4">

                    <div
                        class="h-14 w-14 rounded-xl bg-green-100 dark:bg-green-500/20 flex items-center justify-center text-2xl">

                        🎉

                    </div>

                    <div>

                        <h3
                            class="text-lg font-bold text-gray-900 dark:text-white">

                            Paket Catering

                        </h3>

                        <p
                            class="text-sm text-gray-600 dark:text-gray-400 mt-1">

                            Kelola paket catering dan harga layanan.

                        </p>

                    </div>

                </div>

            </a>

            <a
                href="{{ route('admin.orders.index') }}"
                class="group bg-white dark:bg-gray-800
                    border border-gray-200 dark:border-gray-700
                    rounded-2xl p-6 shadow-sm
                    hover:shadow-lg transition">

                <div class="flex items-center gap-4">

                    <div
                        class="h-14 w-14 rounded-xl
                            bg-blue-100 dark:bg-blue-500/20
                            flex items-center justify-center
                            text-2xl">

                        📋

                    </div>

                    <div>

                        <h3
                            class="text-lg font-bold
                                text-gray-900 dark:text-white">

                            Kelola Pesanan

                        </h3>

                        <p
                            class="text-sm text-gray-600 dark:text-gray-400 mt-1">

                            Verifikasi pembayaran dan update status pesanan pelanggan.

                        </p>

                    </div>

                </div>

            </a>

            <a
                href="{{ route('admin.helpdesk.index') }}"
                class="group bg-white dark:bg-gray-800
                    border border-gray-200 dark:border-gray-700
                    rounded-2xl p-6 shadow-sm
                    hover:shadow-lg transition">

                <div class="flex items-center gap-4">

                    <div
                        class="h-14 w-14 rounded-xl
                            bg-purple-100 dark:bg-purple-500/20
                            flex items-center justify-center
                            text-2xl">

                        🎫

                    </div>

                    <div>

                        <h3
                            class="text-lg font-bold
                                text-gray-900 dark:text-white">

                            Helpdesk Tiket

                        </h3>

                        <p
                            class="text-sm text-gray-600 dark:text-gray-400 mt-1">

                            Kelola tiket bantuan, balas pertanyaan pengguna, dan pantau status layanan.

                        </p>

                    </div>

                </div>

            </a>

            <a
                href="{{ route('admin.feedback.index') }}"
                class="group bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 shadow-sm hover:shadow-lg transition">

                <div class="flex items-center gap-4">

                    <div
                        class="h-14 w-14 rounded-xl bg-yellow-100 dark:bg-yellow-500/20 flex items-center justify-center text-2xl">

                        💡

                    </div>

                    <div>

                        <h3
                            class="text-lg font-bold text-gray-900 dark:text-white">

                            Kritik & Saran

                        </h3>

                        <p
                            class="text-sm text-gray-600 dark:text-gray-400 mt-1">

                            Kelola masukan dan evaluasi dari pengguna.

                        </p>

                    </div>

                </div>

            </a>

        </div>

    </div>

</div>


</div>

@endsection
