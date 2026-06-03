<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpdeskTicket extends Model
{
    protected $fillable = [
        'ticket_number',
        'name',
        'email',
        'subject',
        'status'
    ];

    public function messages()
    {
        return $this->hasMany(
            HelpdeskMessage::class
        );
    }
}
