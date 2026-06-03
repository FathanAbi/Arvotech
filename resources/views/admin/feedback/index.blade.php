@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 dark:bg-gray-900">

    <div class="max-w-7xl mx-auto px-6 py-8">

        <!-- Header -->
        <div class="mb-8">

            <h1
                class="text-3xl font-bold text-gray-900 dark:text-white">

                Kritik & Saran

            </h1>

            <p
                class="mt-2 text-gray-600 dark:text-gray-400">

                Kelola masukan dari pengguna website.

            </p>

        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">

                <h3 class="text-gray-500">
                    Total Masukan
                </h3>

                <div class="text-4xl font-bold text-orange-500 mt-2">
                    {{ $totalFeedback }}
                </div>

            </div>

            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">

                <h3 class="text-gray-500">
                    Kritik
                </h3>

                <div class="text-4xl font-bold text-red-500 mt-2">
                    {{ $totalCriticisms }}
                </div>

            </div>

            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">

                <h3 class="text-gray-500">
                    Saran
                </h3>

                <div class="text-4xl font-bold text-green-500 mt-2">
                    {{ $totalSuggestions }}
                </div>

            </div>

        </div>

        <!-- Table -->
        <div
            class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

            <table class="w-full">

                <thead>

                    <tr
                        class="bg-gray-50 dark:bg-gray-700">

                        <th class="px-6 py-4 text-left">
                            Nama
                        </th>

                        <th class="px-6 py-4 text-left">
                            Jenis
                        </th>

                        <th class="px-6 py-4 text-left">
                            Tanggal
                        </th>

                        <th class="px-6 py-4 text-left">
                            Status
                        </th>

                        <th class="px-6 py-4 text-center">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($feedbacks as $feedback)

                    <tr
                        class="border-t border-gray-200 dark:border-gray-700">

                        <td class="px-6 py-4">
                            {{ $feedback->name }}
                        </td>

                        <td class="px-6 py-4">

                            @if($feedback->category == 'kritik')

                                <span
                                    class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">

                                    Kritik

                                </span>

                            @else

                                <span
                                    class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">

                                    Saran

                                </span>

                            @endif

                        </td>

                        <td class="px-6 py-4">
                            {{ $feedback->created_at->format('d M Y') }}
                        </td>

                        <td class="px-6 py-4">

                            @if($feedback->is_read)

                                <span
                                    class="text-green-600 font-medium">

                                    Dibaca

                                </span>

                            @else

                                <span
                                    class="text-red-600 font-medium">

                                    Belum Dibaca

                                </span>

                            @endif

                        </td>

                        <td class="px-6 py-4 text-center">

                            <a
                                href="{{ route('admin.feedback.show', $feedback->id) }}"
                                class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg">

                                Detail

                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td
                            colspan="5"
                            class="text-center py-10 text-gray-500">

                            Belum ada kritik dan saran.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-6">

            {{ $feedbacks->links() }}

        </div>

    </div>

</div>

@endsection