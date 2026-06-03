<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpdeskMessage extends Model
{
    protected $fillable = [
        'helpdesk_ticket_id',
        'sender',
        'message'
    ];

    public function ticket()
    {
        return $this->belongsTo(
            HelpdeskTicket::class,
            'helpdesk_ticket_id'
        );
    }
}