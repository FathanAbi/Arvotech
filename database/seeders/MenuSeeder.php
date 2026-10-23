<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Table `menus`: nama, deskripsi, harga (int, Rupiah), foto (nullable, public disk path).
        $menus = [
            ['Lontong Balap Komplit', 'Lontong, tauge, tahu goreng, lentho, dan kupang dengan kuah petis khas Surabaya, ditaburi bawang goreng dan sambal.', 18000],
            ['Lontong Balap Biasa', 'Lontong, tauge, tahu goreng, dan lentho dengan kuah petis gurih tanpa kupang.', 13000],
            ['Lontong Balap Sate Kerang', 'Satu porsi lontong balap dengan tambahan 5 tusuk sate kerang berbumbu manis-gurih.', 25000],
            ['Lontong Kupang', 'Lontong dengan kupang segar dan bumbu petis, cocok untuk pencinta seafood.', 20000],
            ['Tahu Tek', 'Tahu goreng, kentang, lontong, tauge, dan selada dipotong dengan gunting, disiram bumbu petis kacang.', 16000],
            ['Tahu Campur', 'Tahu, lontong, tauge, dan irisan daging sapi dengan kuah petis khas Lamongan-Surabaya.', 20000],
            ['Rujak Cingur', 'Irisan cingur sapi, sayuran, buah, dan lontong dengan bumbu petis dan kacang yang pedas legit.', 22000],
            ['Semanggi Suroboyo', 'Daun semanggi rebus dengan bumbu pecel ubi manis, disajikan bersama kerupuk puli.', 15000],
            ['Sate Kerang (10 tusuk)', 'Sate kerang bakar dengan bumbu kecap dan petis, cocok sebagai teman lontong balap.', 20000],
            ['Lentho (3 pcs)', 'Gorengan singkong dan kacang tolo berisi bumbu, renyah di luar, lembut di dalam.', 8000],
            ['Kerupuk Puli', 'Kerupuk beras khas Surabaya, pelengkap lontong balap dan semanggi.', 3000],
            ['Es Teh Manis', 'Teh seduh dingin dengan gula pasir.', 4000],
            ['Es Degan', 'Kelapa muda segar dengan es serut dan sirup.', 10000],
            ['Es Jeruk', 'Perasan jeruk peras segar dengan es batu.', 8000],
        ];

        foreach ($menus as $index => [$nama, $deskripsi, $harga]) {
            $exists = DB::table('menus')->where('nama', $nama)->exists();
            $menuNumber = $index + 1;
            $foto = null;

            foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
                if (Storage::disk('public')->exists("menus/menu{$menuNumber}.{$ext}")) {
                    $foto = "menus/menu{$menuNumber}.{$ext}";
                    break;
                }
            }

            DB::table('menus')->updateOrInsert(
                ['nama' => $nama],
                [
                    'deskripsi'  => $deskripsi,
                    'harga'      => $harga,
                    'foto'       => $foto,
                    'updated_at' => now(),
                ] + ($exists ? [] : ['created_at' => now()])
            );
        }
    }
}