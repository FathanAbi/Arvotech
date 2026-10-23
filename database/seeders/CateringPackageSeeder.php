<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CateringPackageSeeder extends Seeder
{
    public function run(): void
    {
        // Table `catering_packages`: nama_paket, deskripsi, harga (int, Rupiah per porsi), foto (nullable).
        $packages = [
            ['Paket Hemat Arisan', 'Lontong balap biasa, kerupuk puli, dan es teh manis. Pas untuk arisan, pengajian, dan acara keluarga kecil.', 20000],
            ['Paket Lontong Balap Komplit', 'Lontong balap komplit dengan kupang, lentho, kerupuk puli, dan es teh manis. Favorit untuk acara kantor dan reuni.', 28000],
            ['Paket Kenduri Surabaya', 'Lontong balap komplit, sate kerang 5 tusuk, tahu tek mini, kerupuk puli, dan es jeruk. Cocok untuk syukuran dan hajatan.', 38000],
            ['Paket Prasmanan Sultan', 'Prasmanan lengkap: lontong balap, rujak cingur, tahu campur, sate kerang, semanggi, lentho, kerupuk, dan es degan.', 55000],
            ['Paket Snack Box Rapat', 'Lontong kupang mini, lentho, kerupuk puli, dan air mineral dalam kotak. Praktis untuk rapat dan seminar.', 18000],
        ];

        foreach ($packages as [$nama, $deskripsi, $harga]) {
            $exists = DB::table('catering_packages')->where('nama_paket', $nama)->exists();

            DB::table('catering_packages')->updateOrInsert(
                ['nama_paket' => $nama],
                [
                    'deskripsi'  => $deskripsi,
                    'harga'      => $harga,
                    'foto'       => null,
                    'updated_at' => now(),
                ] + ($exists ? [] : ['created_at' => now()])
            );
        }
    }
}