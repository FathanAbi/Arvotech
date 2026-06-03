@extends('layouts.admin')

@section('content')

<div class="max-w-3xl mx-auto">

    <!-- Header -->
    <div class="mb-8">

        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            Edit Menu
        </h1>

        <p class="mt-2 text-gray-600 dark:text-gray-400">
            Perbarui informasi menu yang tersedia.
        </p>

    </div>

    <!-- Form -->
    <div
        class="bg-white dark:bg-gray-800
               border border-gray-200 dark:border-gray-700
               rounded-2xl shadow-sm p-8">

        <form
            action="{{ route('admin.menus.update', $menu) }}"
            method="POST">

            @csrf
            @method('PUT')

            <!-- Nama Menu -->
            <div class="mb-6">

                <label
                    for="nama"
                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">

                    Nama Menu

                </label>

                <input
                    id="nama"
                    type="text"
                    name="nama"
                    value="{{ old('nama', $menu->nama) }}"
                    class="w-full px-4 py-3 rounded-xl
                           border border-gray-300 dark:border-gray-600
                           bg-white dark:bg-gray-700
                           text-gray-900 dark:text-white
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
                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">

                    Deskripsi

                </label>

                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    rows="5"
                    class="w-full px-4 py-3 rounded-xl
                           border border-gray-300 dark:border-gray-600
                           bg-white dark:bg-gray-700
                           text-gray-900 dark:text-white
                           focus:ring-2 focus:ring-orange-500
                           focus:border-orange-500">{{ old('deskripsi', $menu->deskripsi) }}</textarea>

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
                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">

                    Harga (Rp)

                </label>

                <input
                    id="harga"
                    type="number"
                    name="harga"
                    value="{{ old('harga', $menu->harga) }}"
                    min="0"
                    class="w-full px-4 py-3 rounded-xl
                           border border-gray-300 dark:border-gray-600
                           bg-white dark:bg-gray-700
                           text-gray-900 dark:text-white
                           focus:ring-2 focus:ring-orange-500
                           focus:border-orange-500">

                @error('harga')
                    <p class="mt-2 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3">

                <button
                    type="submit"
                    class="px-6 py-3 rounded-xl
                           bg-orange-500 hover:bg-orange-600
                           text-white font-semibold
                           shadow-lg shadow-orange-500/20
                           transition">

                    Simpan Perubahan

                </button>

                <a
                    href="{{ route('admin.menus.index') }}"
                    class="px-6 py-3 rounded-xl
                           bg-gray-200 hover:bg-gray-300
                           dark:bg-gray-700 dark:hover:bg-gray-600
                           text-gray-800 dark:text-white
                           font-medium text-center
                           transition">

                    Batal

                </a>

            </div>

        </form>

    </div>

</div>

@endsection