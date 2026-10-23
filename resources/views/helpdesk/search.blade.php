@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 dark:bg-gray-900">

    <div class="max-w-3xl mx-auto px-6 py-16">

        <div
            class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">

            <div class="text-center mb-8">

                <div
                    class="inline-flex items-center justify-center h-20 w-20 rounded-full bg-orange-100 dark:bg-orange-500/20 mb-4">

                    🔍

                </div>

                <h1
                    class="text-3xl font-bold text-gray-900 dark:text-white">

                    Lacak Tiket Helpdesk

                </h1>

                <p
                    class="mt-3 text-gray-600 dark:text-gray-400">

                    Masukkan nomor tiket dan email yang Anda gunakan saat
                    membuat tiket.

                </p>

            </div>

            @if($errors->any())

                <div
                    class="mb-6 p-4 rounded-xl bg-red-100 text-red-700 border border-red-200 dark:bg-red-900/20 dark:text-red-300 dark:border-red-700">

                    <ul class="list-disc list-inside">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <form
                method="POST"
                action="{{ route('helpdesk.search') }}">

                @csrf

                <label
                    for="ticket_number"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">

                    Nomor Tiket

                </label>

                <input
                    type="text"
                    id="ticket_number"
                    name="ticket_number"
                    value="{{ old('ticket_number') }}"
                    required
                    autocomplete="off"
                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    placeholder="Contoh: HD-A7KX92QP">

                <label
                    for="email"
                    class="block mt-4 text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">

                    Email

                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    placeholder="email@contoh.com">

                <button
                    type="submit"
                    class="w-full mt-4 bg-orange-500 hover:bg-orange-600 text-white py-3 rounded-xl font-semibold">

                    Cari Tiket

                </button>

            </form>

        </div>

    </div>

</div>

@endsection