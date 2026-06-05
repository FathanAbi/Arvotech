@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 dark:bg-gray-900">

    <div class="max-w-5xl mx-auto px-6 py-10">

        {{-- Ticket Created Alert --}}
        @if(session('success'))

            <div
                class="mb-8 bg-green-50 border border-green-200 rounded-2xl p-6">

                <div class="flex items-start gap-4">

                    <div class="text-4xl">
                        ✅
                    </div>

                    <div>

                        <h3 class="text-xl font-bold text-green-700">
                            Tiket Berhasil Dibuat
                        </h3>

                        <p class="mt-2 text-green-600">
                            Simpan nomor tiket berikut dengan baik untuk
                            memantau perkembangan laporan Anda.
                        </p>

                        <div
                            class="mt-4 bg-white border-2 border-green-300 rounded-xl p-4">

                            <div class="text-sm text-gray-500">
                                Nomor Tiket
                            </div>

                            <div
                                class="text-3xl font-bold tracking-wider text-green-700">

                                {{ session('ticket_number') }}

                            </div>

                        </div>

                        <div
                            class="mt-4 text-sm font-medium text-red-600">

                            ⚠️ Mohon simpan nomor tiket ini dengan baik.
                            Nomor tiket diperlukan untuk melihat status dan
                            riwayat percakapan dengan admin.

                        </div>

                    </div>

                </div>

            </div>

        @endif

        {{-- Header --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">

            <div
                class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">

                <div>

                    <h1
                        class="text-3xl font-bold text-gray-900 dark:text-white">

                        {{ $ticket->subject }}

                    </h1>

                    <p
                        class="mt-2 text-gray-500 dark:text-gray-400">

                        Nomor Tiket:
                        <span class="font-semibold">
                            {{ $ticket->ticket_number }}
                        </span>

                    </p>

                </div>

                <div>

                    @if($ticket->status == 'open')

                        <span
                            class="px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-700">

                            Open

                        </span>

                    @elseif($ticket->status == 'answered')

                        <span
                            class="px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-700">

                            Answered

                        </span>

                    @else

                        <span
                            class="px-4 py-2 rounded-full text-sm font-semibold bg-gray-100 text-gray-700">

                            Closed

                        </span>

                    @endif

                </div>

            </div>

        </div>

        {{-- Conversation --}}
        <div
            class="mt-8 bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">

            <h2
                class="text-xl font-bold text-gray-900 dark:text-white mb-6">

                Riwayat Percakapan

            </h2>

            <div class="space-y-6">

                @foreach($ticket->messages as $message)

                    @if($message->sender == 'user')

                        <div class="flex justify-end">

                            <div
                                class="max-w-2xl bg-orange-500 text-white rounded-2xl p-4">

                                <div
                                    class="font-semibold text-sm mb-2">

                                    USER ({{ $ticket->name }})

                                </div>

                                <p>
                                    {{ $message->message }}
                                </p>

                                <div
                                    class="mt-2 text-xs text-orange-100">

                                    {{ $message->created_at->format('d M Y H:i') }}

                                </div>

                            </div>

                        </div>

                    @else

                        <div class="flex justify-start">

                            <div
                                class="max-w-2xl bg-gray-100 dark:bg-gray-700 rounded-2xl p-4">

                                <div
                                    class="font-semibold text-sm mb-2 text-blue-600">

                                    ADMIN

                                </div>

                                <p
                                    class="text-gray-800 dark:text-gray-200">

                                    {{ $message->message }}

                                </p>

                                <div
                                    class="mt-2 text-xs text-gray-500">

                                    {{ $message->created_at->format('d M Y H:i') }}

                                </div>

                            </div>

                        </div>

                    @endif

                @endforeach

            </div>

        </div>

        {{-- Reply Form --}}

        @if($ticket->status !== 'closed')

        <div
            class="mt-8 bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">

            <h2
                class="text-xl font-bold text-gray-900 dark:text-white mb-4">

                Kirim Balasan

            </h2>

            <form
                method="POST"
                action="{{ url('/helpdesk/'.$ticket->ticket_number.'/reply') }}">

                @csrf

                <textarea
                    name="message"
                    rows="5"
                    required
                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-orange-500 focus:ring-orange-500"
                    placeholder="Tulis balasan atau informasi tambahan..."></textarea>

                <div class="mt-4 flex justify-end">

                    <button
                        type="submit"
                        class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-xl font-semibold transition">

                        Kirim Balasan

                    </button>

                </div>

            </form>

        </div>

        @else

        <div
            class="mt-8 bg-red-50 dark:bg-red-900/20
                border border-red-200 dark:border-red-700
                rounded-3xl p-8">

            <div class="flex items-center gap-3">

                <div class="text-3xl">
                    🔒
                </div>

                <div>

                    <h3 class="font-bold text-red-700 dark:text-red-300">
                        Tiket Ditutup
                    </h3>

                    <p class="mt-1 text-red-600 dark:text-red-400">

                        Tiket ini telah ditutup oleh admin dan tidak dapat menerima balasan baru.

                    </p>

                </div>

            </div>

        </div>

        @endif

    </div>

</div>

@endsection