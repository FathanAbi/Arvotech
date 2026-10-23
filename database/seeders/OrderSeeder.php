<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $packages = DB::table('catering_packages')->pluck('harga', 'nama_paket');
        $packageIds = DB::table('catering_packages')->pluck('id', 'nama_paket');

        if ($packageIds->isEmpty()) {
            $this->command?->warn('No catering packages found; run CateringPackageSeeder first. Skipping orders.');
            return;
        }

        // [invoice suffix, name, email, phone, address, package, qty, status, event offset (days from today), created offset (days ago), has proof]
        $orders = [
            ['20260915101530-214', 'Siti Aminah',      'siti.aminah@example.com',  '081234567801', 'Jl. Darmo Permai Selatan No. 12, Sukomanunggal, Surabaya', 'Paket Hemat Arisan',           50,  'completed',            -35, 45, true],
            ['20260920143012-587', 'PT Maju Bersama',  'ga@majubersama.example',   '081234567802', 'Jl. Basuki Rahmat No. 88, Genteng, Surabaya',              'Paket Snack Box Rapat',     120, 'completed',            -28, 40, true],
            ['20260928091145-342', 'Budi Santoso',     'budi.santoso@example.com', '081234567803', 'Jl. Raya Kertajaya Indah No. 5, Mulyorejo, Surabaya',      'Paket Kenduri Surabaya',     80, 'completed',            -14, 30, true],
            ['20261010083320-761', 'Rina Wulandari',   'rina.w@example.com',       '081234567804', 'Perumahan Citraland Blok G-7, Lakarsantri, Surabaya',      'Paket Prasmanan Sultan',     60, 'shipping',               0, 12, true],
            ['20261012160245-129', 'Hendra Kusuma',    'hendra.k@example.com',     '081234567805', 'Jl. Ngagel Jaya Selatan No. 31, Wonokromo, Surabaya',      'Paket Lontong Balap Komplit',100,'processing',             5, 10, true],
            ['20261016112008-455', 'Dewi Lestari',     'dewi.lestari@example.com', '081234567806', 'Jl. Mayjen Sungkono No. 45, Dukuh Pakis, Surabaya',        'Paket Hemat Arisan',         40, 'waiting_verification',   9,  6, true],
            ['20261019134510-698', 'Yayasan Cahaya',   'admin@cahaya.example',     '081234567807', 'Jl. Raya Menganti No. 17, Wiyung, Surabaya',               'Paket Kenduri Surabaya',    150, 'waiting_verification',  14,  4, true],
            ['20261021075532-903', 'Agus Prasetyo',    'agus.p@example.com',       '081234567808', 'Jl. Rungkut Madya No. 9, Rungkut, Surabaya',               'Paket Lontong Balap Komplit', 30,'pending_payment',        12,  2, false],
            ['20261022190415-276', 'Maya Anggraini',   'maya.a@example.com',       '081234567809', 'Jl. Pucang Anom No. 22, Gubeng, Surabaya',                 'Paket Snack Box Rapat',      75, 'pending_payment',        20,  1, false],
            ['20261005101010-518', 'Joko Widodo Putra','joko.putra@example.com',   '081234567810', 'Jl. Kenjeran No. 101, Bulak, Surabaya',                    'Paket Hemat Arisan',         25, 'rejected',               -3, 18, true],
        ];

        $proofIndex = 1;
        foreach ($orders as [$suffix, $name, $email, $phone, $address, $pkg, $qty, $status, $eventOffset, $createdOffset, $hasProof]) {
            if (! isset($packageIds[$pkg])) {
                continue;
            }

            $invoice = 'INV-' . $suffix;
            $createdAt = now()->subDays($createdOffset);
            $proofPath = $hasProof ? 'payment-proofs/example' . $proofIndex++ . '.png' : null;

            $exists = DB::table('orders')->where('invoice_number', $invoice)->exists();

            DB::table('orders')->updateOrInsert(
                ['invoice_number' => $invoice],
                [
                    'customer_name'    => $name,
                    'email'            => $email,
                    'phone'            => $phone,
                    'event_date'       => now()->addDays($eventOffset)->toDateString(),
                    'delivery_address' => $address,
                    'package_id'       => $packageIds[$pkg],
                    'quantity'         => $qty,
                    'total_price'      => $packages[$pkg] * $qty,
                    'status'           => $status,
                    'payment_proof'    => $proofPath,
                    'updated_at'       => $createdAt->copy()->addHours(3),
                ] + ($exists ? [] : ['created_at' => $createdAt])
            );
        }
    }
}