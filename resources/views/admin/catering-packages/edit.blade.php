@extends('layouts.admin')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="mb-8">

        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            Edit Paket Catering
        </h1>

        <p class="mt-2 text-gray-600 dark:text-gray-400">
            Perbarui informasi paket catering.
        </p>

    </div>

    <div
        class="bg-white dark:bg-gray-800
               border border-gray-200 dark:border-gray-700
               rounded-2xl shadow-sm p-8">

        <form
            action="{{ route('admin.catering-packages.update', $cateringPackage) }}"
            method="POST">

            @csrf
            @method('PUT')

            <!-- Nama Paket -->
            <div class="mb-6">

                <label
                    for="nama_paket"
                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">

                    Nama Paket

                </label>

                <input
                    id="nama_paket"
                    type="text"
                    name="nama_paket"
                    value="{{ old('nama_paket', $cateringPackage->nama_paket) }}"
                    class="w-full px-4 py-3 rounded-xl
                           border border-gray-300 dark:border-gray-600
                           bg-white dark:bg-gray-700
                           text-gray-900 dark:text-white
                           focus:ring-2 focus:ring-green-500
                           focus:border-green-500">

            </div>

            <!-- Deskripsi -->
            <div class="mb-6">

                <label
                    for="deskripsi"
                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">

                    Deskripsi Paket

                </label>

                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    rows="5"
                    class="w-full px-4 py-3 rounded-xl
                           border border-gray-300 dark:border-gray-600
                           bg-white dark:bg-gray-700
                           text-gray-900 dark:text-white
                           focus:ring-2 focus:ring-green-500
                           focus:border-green-500">{{ old('deskripsi', $cateringPackage->deskripsi) }}</textarea>

            </div>

            <!-- Harga -->
            <div class="mb-8">

                <label
                    for="harga"
                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">

                    Harga Paket (Rp)

                </label>

                <input
                    id="harga"
                    type="number"
                    name="harga"
                    value="{{ old('harga', $cateringPackage->harga) }}"
                    min="0"
                    class="w-full px-4 py-3 rounded-xl
                           border border-gray-300 dark:border-gray-600
                           bg-white dark:bg-gray-700
                           text-gray-900 dark:text-white
                           focus:ring-2 focus:ring-green-500
                           focus:border-green-500">

            </div>

            <div class="flex gap-3">

                <button
                    type="submit"
                    class="px-6 py-3 rounded-xl
                           bg-green-600 hover:bg-green-700
                           text-white font-semibold
                           shadow-lg shadow-green-600/20
                           transition">

                    Simpan Perubahan

                </button>

                <a
                    href="{{ route('admin.catering-packages.index') }}"
                    class="px-6 py-3 rounded-xl
                           bg-gray-200 hover:bg-gray-300
                           dark:bg-gray-700 dark:hover:bg-gray-600
                           text-gray-800 dark:text-white
                           font-medium transition">

                    Batal

                </a>

            </div>

        </form>

    </div>

</div>

@endsection