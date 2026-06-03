@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 dark:bg-gray-900">

    <div class="max-w-4xl mx-auto px-6 py-12">

        <!-- Header -->
        <div class="text-center mb-10">

            <div
                class="inline-flex items-center justify-center h-20 w-20 rounded-full bg-orange-100 dark:bg-orange-500/20 mb-4">

                <span class="text-4xl">🎫</span>

            </div>

            <h1 class="text-4xl font-bold text-gray-900 dark:text-white">
                Helpdesk & Bantuan
            </h1>

            <p class="mt-3 text-gray-600 dark:text-gray-400">
                Sampaikan pertanyaan, keluhan, atau permintaan bantuan kepada tim kami.
                Kami akan merespons tiket Anda secepat mungkin.
            </p>

        </div>

        <!-- Success Message -->
        @if(session('success'))

            <div
                class="mb-6 p-4 rounded-xl bg-green-100 text-green-700 border border-green-200">

                {{ session('success') }}

            </div>

        @endif

        <!-- Validation Errors -->
        @if($errors->any())

            <div
                class="mb-6 p-4 rounded-xl bg-red-100 text-red-700 border border-red-200">

                <ul class="list-disc list-inside">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif

        <!-- Form Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">

            <form method="POST" action="{{ url('/helpdesk') }}">

                @csrf

                <div class="grid md:grid-cols-2 gap-6">

                    <!-- Nama -->
                    <div>

                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">

                            Nama Lengkap

                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-orange-500 focus:ring-orange-500"
                            placeholder="Masukkan nama Anda">

                    </div>

                    <!-- Email -->
                    <div>

                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">

                            Email

                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-orange-500 focus:ring-orange-500"
                            placeholder="nama@email.com">

                    </div>

                </div>

                <!-- Subjek -->
                <div class="mt-6">

                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">

                        Subjek

                    </label>

                    <input
                        type="text"
                        name="subject"
                        value="{{ old('subject') }}"
                        class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-orange-500 focus:ring-orange-500"
                        placeholder="Judul atau topik pertanyaan">

                </div>

                <!-- Pesan -->
                <div class="mt-6">

                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">

                        Pesan

                    </label>

                    <textarea
                        name="message"
                        rows="6"
                        class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-orange-500 focus:ring-orange-500"
                        placeholder="Tuliskan pertanyaan atau kendala Anda secara lengkap...">{{ old('message') }}</textarea>

                </div>

                <!-- Button -->
                <div class="mt-8 flex justify-end">

                    <button
                        type="submit"
                        class="px-8 py-3 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-xl shadow-sm transition">

                        Kirim Tiket

                    </button>

                </div>

            </form>

        </div>

        <!-- Info -->
        <div
            class="mt-8 bg-orange-50 dark:bg-orange-500/10 border border-orange-200 dark:border-orange-500/20 rounded-2xl p-6">

            <h3 class="font-semibold text-orange-600 mb-2">
                Informasi Helpdesk
            </h3>

            <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">

                <li>📌 Setiap laporan akan mendapatkan nomor tiket unik.</li>

                <li>📧 Gunakan email yang aktif agar mudah dihubungi.</li>

                <li>⏱️ Tim kami akan merespons tiket sesuai jam operasional.</li>

                <li>🎫 Simpan nomor tiket untuk memantau perkembangan laporan.</li>

            </ul>

        </div>

    </div>

</div>

@endsection