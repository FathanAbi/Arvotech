@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 dark:bg-gray-900">

    <div class="max-w-7xl mx-auto px-6 py-8">

        <!-- Header -->
        <div class="mb-8">

            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Helpdesk Tiket
            </h1>

            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Kelola pertanyaan, keluhan, dan permintaan bantuan dari pengguna.
            </p>

        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">

                <div class="flex justify-between items-center">

                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Total Tiket
                        </p>

                        <h2 class="mt-2 text-4xl font-bold text-purple-500">
                            {{ $tickets->total() }}
                        </h2>
                    </div>

                    <div class="h-14 w-14 rounded-xl bg-purple-100 dark:bg-purple-500/20 flex items-center justify-center text-2xl">
                        🎫
                    </div>

                </div>

            </div>

        </div>

        <!-- Table Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">

                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Daftar Tiket
                </h2>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead>

                        <tr class="bg-gray-50 dark:bg-gray-700">

                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                Ticket
                            </th>

                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                Nama
                            </th>

                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                Subjek
                            </th>

                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                Status
                            </th>

                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700 dark:text-gray-200">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($tickets as $ticket)

                        <tr class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">

                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $ticket->ticket_number }}
                            </td>

                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                {{ $ticket->name }}
                            </td>

                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                {{ $ticket->subject }}
                            </td>

                            <td class="px-6 py-4">

                                @if($ticket->status == 'open')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                        Open
                                    </span>
                                @elseif($ticket->status == 'answered')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                        Answered
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                                        Closed
                                    </span>
                                @endif

                            </td>

                            <td class="px-6 py-4 text-center">

                                <a
                                    href="{{ route('admin.helpdesk.show', $ticket->id) }}"
                                    class="inline-flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium rounded-lg transition">

                                    Lihat Detail

                                </a>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">

                                Belum ada tiket helpdesk.

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        <!-- Pagination -->
        <div class="mt-6">

            {{ $tickets->links() }}

        </div>

    </div>

</div>

@endsection