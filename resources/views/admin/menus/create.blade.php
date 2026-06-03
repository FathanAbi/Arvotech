@extends('layouts.admin')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="mb-8">

        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            Tambah Menu
        </h1>

        <p class="mt-2 text-gray-600 dark:text-gray-400">
            Tambahkan menu baru untuk ditampilkan pada website.
        </p>

    </div>

    <form
        action="{{ route('admin.menus.store') }}"
        method="POST"
        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-8 rounded-2xl shadow-sm">

        @csrf

        <!-- Nama Menu -->
        <div class="mb-6">

            <label
                for="nama"
                class="block mb-2 font-medium text-gray-700 dark:text-gray-300">

                Nama Menu

            </label>

            <input
                id="nama"
                type="text"
                name="nama"
                value="{{ old('nama') }}"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-700
                       text-gray-900 dark:text-white
                       px-4 py-3
                       focus:ring-2 focus:ring-orange-500
                       focus:border-orange-500">

            @error('nama')
                <p class="mt-2 text-sm text-red-500">
                    {{ $message }}
                </p>
            @enderror

        </div>

        <!-- Deskripsi -->
        <div class="mb-6">

            <label
                for="deskripsi"
                class="block mb-2 font-medium text-gray-700 dark:text-gray-300">

                Deskripsi

            </label>

            <textarea
                id="deskripsi"
                name="deskripsi"
                rows="5"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-700
                       text-gray-900 dark:text-white
                       px-4 py-3
                       focus:ring-2 focus:ring-orange-500
                       focus:border-orange-500">{{ old('deskripsi') }}</textarea>

            @error('deskripsi')
                <p class="mt-2 text-sm text-red-500">
                    {{ $message }}
                </p>
            @enderror

        </div>

        <!-- Harga -->
        <div class="mb-8">

            <label
                for="harga"
                class="block mb-2 font-medium text-gray-700 dark:text-gray-300">

                Harga (Rp)

            </label>

            <input
                id="harga"
                type="number"
                name="harga"
                value="{{ old('harga') }}"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-700
                       text-gray-900 dark:text-white
                       px-4 py-3
                       focus:ring-2 focus:ring-orange-500
                       focus:border-orange-500">

            @error('harga')
                <p class="mt-2 text-sm text-red-500">
                    {{ $message }}
                </p>
            @enderror

        </div>

        <!-- Tombol -->
        <div class="flex gap-3">

            <button
                type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition">

                Simpan Menu

            </button>

            <a
                href="{{ route('admin.menus.index') }}"
                class="bg-gray-200 hover:bg-gray-300
                       dark:bg-gray-700 dark:hover:bg-gray-600
                       text-gray-800 dark:text-white
                       px-6 py-3 rounded-lg transition">

                Batal

            </a>

        </div>

    </form>

</div>

@endsection