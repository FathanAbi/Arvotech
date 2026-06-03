{{-- resources/views/admin/helpdesk/show.blade.php --}}

@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto py-8 px-6">

    <h1 class="text-2xl font-bold mb-4">
        Detail Tiket Helpdesk
    </h1>

    <div class="bg-white rounded-lg shadow p-6">

        <p>
            <strong>No Ticket:</strong>
            {{ $ticket->ticket_number }}
        </p>

        <p>
            <strong>Nama:</strong>
            {{ $ticket->name }}
        </p>

        <p>
            <strong>Email:</strong>
            {{ $ticket->email }}
        </p>

        <p>
            <strong>Subjek:</strong>
            {{ $ticket->subject }}
        </p>

        <p>
            <strong>Status:</strong>
            {{ $ticket->status }}
        </p>

    </div>

    <div class="mt-6">

        <h2 class="font-bold text-lg mb-4">
            Percakapan
        </h2>

        @foreach($ticket->messages as $message)

            <div class="mb-4 p-4 rounded-lg border">

                <div class="font-semibold">
                    @if($message->sender == 'user')
                        <strong>
                            USER ({{ $ticket->name }})
                        </strong>
                    @else
                        <strong>
                            ADMIN
                        </strong>
                    @endif
                </div>

                <div class="mt-2">
                    {{ $message->message }}
                </div>

            </div>

        @endforeach

    </div>

    <div class="mt-8">

        <form
            method="POST"
            action="{{ route('admin.helpdesk.reply', $ticket->id) }}">

            @csrf

            <textarea
                name="message"
                rows="5"
                class="w-full border rounded-lg p-3"
                placeholder="Tulis balasan..."></textarea>

            <button
                type="submit"
                class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg">

                Kirim Balasan

            </button>

        </form>

    </div>

</div>

@endsection