@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 dark:bg-gray-900">

    <div class="max-w-4xl mx-auto px-6 py-12">

        <div
            class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">

            <div class="text-center mb-8">

                <div class="text-5xl mb-4">
                    💡
                </div>

                <h1
                    class="text-3xl font-bold text-gray-900 dark:text-white">

                    Kritik dan Saran

                </h1>

                <p
                    class="mt-2 text-gray-600 dark:text-gray-400">

                    Masukan Anda membantu kami meningkatkan kualitas layanan website.

                </p>

            </div>

            @if(session('success'))

                <div
                    class="mb-6 p-4 rounded-xl bg-green-100 text-green-700">

                    {{ session('success') }}

                </div>

            @endif

            <form
                method="POST"
                action="{{ route('feedback.store') }}">

                @csrf

                <div class="grid md:grid-cols-2 gap-6">

                    <div>

                        <label class="block mb-2">
                            Nama
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="w-full rounded-xl">

                    </div>

                    <div>

                        <label class="block mb-2">
                            Email (Opsional)
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="w-full rounded-xl">

                    </div>

                </div>

                <div class="mt-6">

                    <label class="block mb-2">
                        Jenis Masukan
                    </label>

                    <select
                        name="category"
                        class="w-full rounded-xl">

                        <option value="kritik">
                            Kritik
                        </option>

                        <option value="saran">
                            Saran
                        </option>

                    </select>

                </div>

                <div class="mt-6">

                    <label class="block mb-2">
                        Pesan
                    </label>

                    <textarea
                        rows="6"
                        name="message"
                        class="w-full rounded-xl"></textarea>

                </div>

                <button
                    class="mt-6 bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-xl">

                    Kirim Masukan

                </button>

            </form>

        </div>

    </div>

</div>

@endsection