<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class CateringPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_paket',
        'deskripsi',
        'harga',
        'foto'
    ];

    public function orders()
    {
        return $this->hasMany(
            Order::class,
            'package_id'
        );
    }
}
