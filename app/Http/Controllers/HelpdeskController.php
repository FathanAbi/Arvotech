<?php

namespace App\Http\Controllers;

use App\Models\HelpdeskTicket;
use App\Models\HelpdeskMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HelpdeskController extends Controller
{
    public function create()
    {
        return view('helpdesk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required'
        ]);

        $ticket = HelpdeskTicket::create([
            'ticket_number' => 'HD-' .
                strtoupper(Str::random(8)),
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
        ]);

        HelpdeskMessage::create([
            'helpdesk_ticket_id' => $ticket->id,
            'sender' => 'user',
            'message' => $request->message
        ]);

        return redirect()
            ->route('helpdesk.show', $ticket->ticket_number)
            ->with([
                'success' => true,
                'ticket_number' => $ticket->ticket_number
            ]);
    }

    public function show($ticketNumber)
    {
        $ticket = HelpdeskTicket::with('messages')
            ->where(
                'ticket_number',
                $ticketNumber
            )
            ->firstOrFail();

        return view(
            'helpdesk.show',
            compact('ticket')
        );
    }

    public function reply(
        Request $request,
        $ticketNumber
    ) {
        $ticket = HelpdeskTicket::where(
            'ticket_number',
            $ticketNumber
        )->firstOrFail();

        // Tidak boleh membalas tiket yang sudah ditutup
        if ($ticket->status === 'closed') {

            return back()->with(
                'error',
                'Tiket telah ditutup dan tidak dapat menerima balasan baru.'
            );
        }

        $request->validate([
            'message' => [
                'required',
                'string',
                'max:5000'
            ]
        ]);

        HelpdeskMessage::create([
            'helpdesk_ticket_id' => $ticket->id,
            'sender' => 'user',
            'message' => $request->message
        ]);

        // Jika sebelumnya sudah dijawab admin,
        // ubah kembali menjadi open karena user memberikan tanggapan baru
        if ($ticket->status === 'answered') {

            $ticket->update([
                'status' => 'open'
            ]);
        }

        return back()->with(
            'success',
            'Balasan berhasil dikirim.'
        );
    }

    public function searchForm()
    {
        return view('helpdesk.search');
    }

    public function search(Request $request)
    {
        $request->validate([
            'keyword' => 'required'
        ]);

        $tickets = HelpdeskTicket::where(
                'ticket_number',
                'like',
                '%' . $request->keyword . '%'
            )
            ->orWhere(
                'name',
                'like',
                '%' . $request->keyword . '%'
            )
            ->latest()
            ->get();

        return view(
            'helpdesk.search-result',
            compact('tickets')
        );
    }
}