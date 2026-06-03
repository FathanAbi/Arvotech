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

    public function reply(Request $request, $ticketNumber)
    {
        $ticket = HelpdeskTicket::where(
            'ticket_number',
            $ticketNumber
        )->firstOrFail();

        HelpdeskMessage::create([
            'helpdesk_ticket_id' => $ticket->id,
            'sender' => 'user',
            'message' => $request->message
        ]);

        return back();
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