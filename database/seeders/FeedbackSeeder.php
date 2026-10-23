<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeedbackSeeder extends Seeder
{
    public function run(): void
    {
        // feedback: name, email (nullable), category (kritik|saran), message, is_read
        $items = [
            ['Andi Pratama',   'andi.p@example.com',     'saran',  'Kuah petis lontong balapnya mantap. Semoga ada paket catering vegetarian juga.', true,  30],
            ['Ratna Sari',     null,                     'kritik', 'Pengiriman catering sempat terlambat 30 menit dari jadwal. Mohon diperbaiki.',  true,  24],
            ['Fajar Nugroho',  'fajar.n@example.com',    'saran',  'Mohon tambahkan opsi pembayaran QRIS agar lebih praktis.',                       false, 15],
            ['Lilis Handayani', null,                    'saran',  'Rasanya otentik seperti di Surabaya. Tolong tambah menu semanggi dalam paket.',  true,  12],
            ['Wawan Setiawan', 'wawan.s@example.com',    'kritik', 'Porsi lentho di paket snack box terasa kurang. Mungkin bisa ditambah satu.',      false, 8],
            ['Nia Kurniasih',  'nia.k@example.com',      'saran',  'Website mudah dipakai. Bisa ditambah foto di setiap paket catering?',            false, 5],
            ['Rizky Maulana',  null,                     'kritik', 'Balasan helpdesk agak lama. Semoga bisa lebih cepat.',                            false, 2],
            ['Ayu Permata',    'ayu.p@example.com',      'saran',  'Pelayanan ramah, makanan sampai masih hangat. Terus pertahankan!',               false, 1],
        ];

        foreach ($items as [$name, $email, $category, $message, $isRead, $daysAgo]) {
            $exists = DB::table('feedback')->where('name', $name)->where('message', $message)->exists();
            $at = now()->subDays($daysAgo);

            DB::table('feedback')->updateOrInsert(
                ['name' => $name, 'message' => $message],
                [
                    'email'      => $email,
                    'category'   => $category,
                    'is_read'    => $isRead,
                    'updated_at' => $at,
                ] + ($exists ? [] : ['created_at' => $at])
            );
        }
    }
}