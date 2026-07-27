@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto px-6 py-12">

    <div class="mb-8 text-center">

        <h1 class="text-4xl font-bold text-gray-900 dark:text-white">
            Pemesanan Catering
        </h1>

        <p class="mt-3 text-gray-600 dark:text-gray-400">
            Lengkapi data berikut untuk melakukan pemesanan catering.
        </p>

    </div>

    <div
        class="bg-white dark:bg-gray-800
               border border-gray-200 dark:border-gray-700
               rounded-2xl shadow-sm p-8">

        <form
            method="POST"
            action="{{ route('orders.store') }}">

            @csrf

            <!-- Nama -->
            <div class="mb-6">

                <label
                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">

                    Nama Pemesan

                </label>

                <input
                    type="text"
                    name="customer_name"
                    value="{{ old('customer_name') }}"
                    class="w-full px-4 py-3 rounded-xl
                           border border-gray-300 dark:border-gray-600
                           bg-white dark:bg-gray-700
                           text-gray-900 dark:text-white">

                @error('customer_name')
                    <p class="mt-2 text-red-500 text-sm">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            <!-- Email -->
            <div class="mb-6">

                <label
                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">

                    Email

                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full px-4 py-3 rounded-xl
                           border border-gray-300 dark:border-gray-600
                           bg-white dark:bg-gray-700
                           text-gray-900 dark:text-white">

                @error('email')
                    <p class="mt-2 text-red-500 text-sm">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            <!-- Telepon -->
            <div class="mb-6">

                <label
                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">

                    Nomor Telepon

                </label>

                <input
                    type="text"
                    name="phone"
                    value="{{ old('phone') }}"
                    class="w-full px-4 py-3 rounded-xl
                           border border-gray-300 dark:border-gray-600
                           bg-white dark:bg-gray-700
                           text-gray-900 dark:text-white">

                @error('phone')
                    <p class="mt-2 text-red-500 text-sm">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            <!-- Tanggal Acara -->
            <div class="mb-6">

                <label
                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">

                    Tanggal Acara

                </label>

                <input
                    type="date"
                    name="event_date"
                    value="{{ old('event_date') }}"
                    class="w-full px-4 py-3 rounded-xl
                           border border-gray-300 dark:border-gray-600
                           bg-white dark:bg-gray-700
                           text-gray-900 dark:text-white">

            </div>

            <!-- Alamat -->
            <div class="mb-6">

                <label
                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">

                    Alamat Pengiriman

                </label>

                <textarea
                    rows="4"
                    name="delivery_address"
                    class="w-full px-4 py-3 rounded-xl
                           border border-gray-300 dark:border-gray-600
                           bg-white dark:bg-gray-700
                           text-gray-900 dark:text-white">{{ old('delivery_address') }}</textarea>

            </div>

            <!-- Paket -->
            <div class="mb-6">

                <label
                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">

                    Paket Catering

                </label>

                <select
                    id="packageSelect"
                    name="package_id"
                    class="w-full px-4 py-3 rounded-xl
                        border border-gray-300 dark:border-gray-600
                        bg-white dark:bg-gray-700
                        text-gray-900 dark:text-white">

                    <option value="">
                        Pilih Paket Catering
                    </option>

                    @foreach($packages as $package)

                        <option
                            value="{{ $package->id }}"
                            data-price="{{ $package->harga }}"
                            @selected(old('package_id', $selectedPackage ?? '') == $package->id)>

                            {{ $package->nama_paket }}
                            - Rp {{ number_format($package->harga) }}

                        </option>

                    @endforeach

                </select>

            </div>

            <!-- Jumlah -->
            <div class="mb-8">

                <label
                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">

                    Jumlah Porsi

                </label>

                <input
                    id="quantityInput"
                    type="number"
                    min="1"
                    name="quantity"
                    value="{{ old('quantity', 1) }}"
                    class="w-full px-4 py-3 rounded-xl
                        border border-gray-300 dark:border-gray-600
                        bg-white dark:bg-gray-700
                        text-gray-900 dark:text-white">

            </div>

            <!-- Ringkasan Pesanan -->
            <div
                class="mb-8 p-6 rounded-xl
                    bg-green-50 dark:bg-green-900/20
                    border border-green-200 dark:border-green-700">

                <h3 class="font-semibold text-lg text-green-700 dark:text-green-400 mb-4">
                    Ringkasan Pesanan
                </h3>

                <div class="space-y-2 text-sm">

                    <div class="flex justify-between">
                        <span>Harga Paket</span>
                        <span id="packagePrice">
                            Rp 0
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span>Jumlah Porsi</span>
                        <span id="quantityDisplay">
                            1
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span>Ongkos Kirim</span>
                        <span class="font-semibold text-green-600">
                            GRATIS
                        </span>
                    </div>

                    <hr class="my-3 border-gray-300 dark:border-gray-600">

                    <div class="flex justify-between text-lg font-bold">
                        <span>Total Harga</span>
                        <span id="totalPrice">
                            Rp 0
                        </span>
                    </div>

                </div>

            </div>

            <!-- Info Pembayaran -->
            <div
                class="mb-8 p-5 rounded-xl
                       bg-orange-50 dark:bg-orange-900/20
                       border border-orange-200 dark:border-orange-700">

                <h3 class="font-semibold text-orange-600 dark:text-orange-400">
                    Informasi Pembayaran
                </h3>

                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">

                    Setelah pesanan dibuat, silakan melakukan transfer ke rekening berikut:

                </p>

                <div class="mt-3">

                    <p class="font-semibold">
                        Bank BCA
                    </p>

                    <p>
                        1234567890
                    </p>

                    <p>
                        a.n. Lontong Balap Cak Gendut
                    </p>

                </div>

            </div>

            <!-- Tombol -->
            <button
                type="submit"
                class="w-full py-4 rounded-xl
                       bg-orange-500 hover:bg-orange-600
                       text-white font-semibold
                       shadow-lg shadow-orange-500/20
                       transition">

                Buat Pesanan Catering

            </button>

        </form>

    </div>

</div>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const packageSelect = document.getElementById('packageSelect');
    const quantityInput = document.getElementById('quantityInput');

    const packagePrice = document.getElementById('packagePrice');
    const quantityDisplay = document.getElementById('quantityDisplay');
    const totalPrice = document.getElementById('totalPrice');

    function formatRupiah(number) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
    }

    function calculateTotal() {

        const selectedOption =
            packageSelect.options[packageSelect.selectedIndex];

        const price =
            parseInt(selectedOption.dataset.price || 0);

        const quantity =
            parseInt(quantityInput.value || 1);

        const total = price * quantity;

        packagePrice.textContent = formatRupiah(price);
        quantityDisplay.textContent = quantity;
        totalPrice.textContent = formatRupiah(total);
    }

    packageSelect.addEventListener('change', calculateTotal);
    quantityInput.addEventListener('input', calculateTotal);

    calculateTotal();

});

</script>

@endsection