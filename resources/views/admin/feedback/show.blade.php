@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 dark:bg-gray-900">

    <div class="max-w-4xl mx-auto px-6 py-8">

        <div
            class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">

            <div class="mb-6">

                <h1
                    class="text-3xl font-bold text-gray-900 dark:text-white">

                    Detail Masukan

                </h1>

            </div>

            <div class="space-y-4">

                <div>

                    <span class="font-semibold">
                        Nama:
                    </span>

                    {{ $feedback->name }}

                </div>

                <div>

                    <span class="font-semibold">
                        Email:
                    </span>

                    {{ $feedback->email ?: '-' }}

                </div>

                <div>

                    <span class="font-semibold">
                        Jenis:
                    </span>

                    {{ ucfirst($feedback->category) }}

                </div>

                <div>

                    <span class="font-semibold">
                        Tanggal:
                    </span>

                    {{ $feedback->created_at->format('d M Y H:i') }}

                </div>

                @if($feedback->rating)

                <div>

                    <span class="font-semibold">
                        Rating:
                    </span>

                    {{ $feedback->rating }}/5 ⭐

                </div>

                @endif

                <hr>

                <div>

                    <h3
                        class="font-semibold mb-3">

                        Isi Masukan

                    </h3>

                    <div
                        class="bg-gray-50 dark:bg-gray-700 rounded-xl p-5">

                        {{ $feedback->message }}

                    </div>

                </div>

            </div>

            <div class="mt-8">

                <a
                    href="{{ route('admin.feedback.index') }}"
                    class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-xl">

                    Kembali

                </a>

            </div>

        </div>

    </div>

</div>

@endsection