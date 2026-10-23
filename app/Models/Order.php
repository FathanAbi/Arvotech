<?php

namespace App\Models;
use App\Models\CateringPackage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_name',
        'email',
        'phone',
        'event_date',
        'delivery_address',
        'package_id',
        'quantity',
        'total_price',
        'status',
        'payment_proof'
    ];

    public function package()
    {
        return $this->belongsTo(
            CateringPackage::class,
            'package_id'
        );
    }
}