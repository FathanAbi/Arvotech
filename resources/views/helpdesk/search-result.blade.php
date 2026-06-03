@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 dark:bg-gray-900">

    <div class="max-w-5xl mx-auto px-6 py-10">

        <div
            class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700">

            <div class="p-6 border-b">

                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white">

                    Hasil Pencarian Tiket

                </h1>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead>

                    <tr class="bg-gray-50 dark:bg-gray-700">

                        <th class="px-6 py-4 text-left">
                            Nomor Tiket
                        </th>

                        <th class="px-6 py-4 text-left">
                            Nama
                        </th>

                        <th class="px-6 py-4 text-left">
                            Subjek
                        </th>

                        <th class="px-6 py-4">
                            Status
                        </th>

                        <th class="px-6 py-4">
                            Aksi
                        </th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($tickets as $ticket)

                    <tr class="border-t">

                        <td class="px-6 py-4">
                            {{ $ticket->ticket_number }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $ticket->name }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $ticket->subject }}
                        </td>

                        <td class="px-6 py-4">
                            {{ ucfirst($ticket->status) }}
                        </td>

                        <td class="px-6 py-4">

                            <a
                                href="{{ route('helpdesk.show', $ticket->ticket_number) }}"
                                class="bg-orange-500 text-white px-4 py-2 rounded-lg">

                                Detail

                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="5"
                            class="text-center py-8 text-gray-500">

                            Tidak ditemukan tiket.

                        </td>

                    </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection