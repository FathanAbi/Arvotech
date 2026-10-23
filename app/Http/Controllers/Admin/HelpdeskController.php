<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HelpdeskTicket;
use App\Models\HelpdeskMessage;
use Illuminate\Http\Request;

class HelpdeskController extends Controller
{
    public function index()
    {
        $tickets = HelpdeskTicket::latest()
            ->paginate(10);

        return view(
            'admin.helpdesk.index',
            compact('tickets')
        );
    }

    public function show($id)
    {
        $ticket = HelpdeskTicket::with('messages')
            ->findOrFail($id);

        return view(
            'admin.helpdesk.show',
            compact('ticket')
        );
    }

    public function reply(Request $request, $id)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $ticket = HelpdeskTicket::findOrFail($id);

        HelpdeskMessage::create([
            'helpdesk_ticket_id' => $ticket->id,
            'sender' => 'admin',
            'message' => $validated['message']
        ]);

        $ticket->update([
            'status' => 'answered'
        ]);

        return back();
    }

    public function close($id)
    {
        $ticket = HelpdeskTicket::findOrFail($id);

        HelpdeskMessage::create([
            'helpdesk_ticket_id' => $ticket->id,
            'sender' => 'admin',
            'message' => '[SISTEM] Tiket telah ditutup oleh admin.'
        ]);

        $ticket->update([
            'status' => 'closed'
        ]);

        return back()->with(
            'success',
            'Tiket berhasil ditutup.'
        );
    }
}
