@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto px-6 py-10">

    {{-- Header --}}
    <div class="mb-8">

        <h1
            class="text-3xl font-bold
                   text-gray-900 dark:text-white">

            Detail Tiket Helpdesk

        </h1>

        <p
            class="mt-2 text-gray-600 dark:text-gray-400">

            Kelola percakapan dan status tiket pelanggan.

        </p>

    </div>

    {{-- Informasi Tiket --}}
    <div
        class="bg-white dark:bg-gray-800
               border border-gray-200 dark:border-gray-700
               rounded-2xl shadow-sm p-6">

        @php

            $statusClass = match($ticket->status) {

                'open'
                    => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',

                'answered'
                    => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',

                'closed'
                    => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',

                default
                    => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200'
            };

        @endphp

        <div
            class="grid md:grid-cols-2 gap-6">

            <div>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Nomor Tiket
                </p>

                <p
                    class="font-bold text-lg
                           text-gray-900 dark:text-white">

                    {{ $ticket->ticket_number }}

                </p>

            </div>

            <div>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Status
                </p>

                <span
                    class="inline-flex px-3 py-1 rounded-full text-sm font-semibold {{ $statusClass }}">

                    {{ strtoupper($ticket->status) }}

                </span>

            </div>

            <div>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Nama Pengguna
                </p>

                <p
                    class="font-medium
                           text-gray-900 dark:text-white">

                    {{ $ticket->name }}

                </p>

            </div>

            <div>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Email
                </p>

                <p
                    class="font-medium
                           text-gray-900 dark:text-white">

                    {{ $ticket->email }}

                </p>

            </div>

            <div class="md:col-span-2">

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Subjek
                </p>

                <p
                    class="font-medium
                           text-gray-900 dark:text-white">

                    {{ $ticket->subject }}

                </p>

            </div>

        </div>

    </div>

    {{-- Tombol Tutup Tiket --}}
    @if($ticket->status !== 'closed')

    <div class="mt-6">

        <form
            method="POST"
            action="{{ route('admin.helpdesk.close', $ticket->id) }}">

            @csrf

            <button
                type="submit"
                onclick="return confirm('Tutup tiket ini?')"
                class="px-5 py-3 rounded-xl
                       bg-red-600 hover:bg-red-700
                       text-white font-semibold transition">

                Tutup Tiket

            </button>

        </form>

    </div>

    @endif

    {{-- Percakapan --}}
    <div
        class="mt-8 bg-white dark:bg-gray-800
               border border-gray-200 dark:border-gray-700
               rounded-2xl shadow-sm p-6">

        <h2
            class="text-xl font-bold mb-6
                   text-gray-900 dark:text-white">

            Riwayat Percakapan

        </h2>

        <div class="space-y-5">

            @foreach($ticket->messages as $message)

                @if($message->sender == 'user')

                    <div class="flex justify-start">

                        <div
                            class="max-w-3xl rounded-2xl p-4
                                   bg-orange-50 dark:bg-orange-900/20
                                   border border-orange-200 dark:border-orange-800">

                            <div
                                class="font-semibold text-orange-600 dark:text-orange-300">

                                USER ({{ $ticket->name }})

                            </div>

                            <div
                                class="mt-2 text-gray-800 dark:text-gray-200">

                                {{ $message->message }}

                            </div>

                            <div
                                class="mt-3 text-xs
                                       text-gray-500 dark:text-gray-400">

                                {{ $message->created_at->format('d M Y H:i') }}

                            </div>

                        </div>

                    </div>

                @else

                    <div class="flex justify-end">

                        <div
                            class="max-w-3xl rounded-2xl p-4
                                   bg-blue-50 dark:bg-blue-900/20
                                   border border-blue-200 dark:border-blue-800">

                            <div
                                class="font-semibold text-blue-600 dark:text-blue-300">

                                ADMIN

                            </div>

                            <div
                                class="mt-2 text-gray-800 dark:text-gray-200">

                                {{ $message->message }}

                            </div>

                            <div
                                class="mt-3 text-xs
                                       text-gray-500 dark:text-gray-400">

                                {{ $message->created_at->format('d M Y H:i') }}

                            </div>

                        </div>

                    </div>

                @endif

            @endforeach

        </div>

    </div>

    {{-- Form Balasan --}}
    @if($ticket->status !== 'closed')

    <div
        class="mt-8 bg-white dark:bg-gray-800
               border border-gray-200 dark:border-gray-700
               rounded-2xl shadow-sm p-6">

        <h2
            class="text-xl font-bold mb-4
                   text-gray-900 dark:text-white">

            Kirim Balasan

        </h2>

        <form
            method="POST"
            action="{{ route('admin.helpdesk.reply', $ticket->id) }}">

            @csrf

            <textarea
                name="message"
                rows="5"
                required
                class="w-full rounded-xl
                       border border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-700
                       text-gray-900 dark:text-white
                       focus:ring-2 focus:ring-blue-500
                       focus:border-blue-500"
                placeholder="Tulis balasan..."></textarea>

            <button
                type="submit"
                class="mt-4 px-6 py-3 rounded-xl
                       bg-blue-600 hover:bg-blue-700
                       text-white font-semibold">

                Kirim Balasan

            </button>

        </form>

    </div>

    @else

    <div
        class="mt-8 rounded-2xl p-6
               bg-red-50 dark:bg-red-900/20
               border border-red-200 dark:border-red-800">

        <p
            class="font-medium
                   text-red-700 dark:text-red-300">

            🔒 Tiket ini telah ditutup dan tidak dapat dibalas lagi.

        </p>

    </div>

    @endif

</div>

@endsection