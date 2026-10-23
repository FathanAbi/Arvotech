<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HelpdeskSeeder extends Seeder
{
    public function run(): void
    {
        // helpdesk_tickets: ticket_number (HD-XXXXXXXX), name, email, subject, status (open|answered|closed)
        // helpdesk_messages: helpdesk_ticket_id, sender (user|admin), message
        $tickets = [
            [
                'ticket_number' => 'HD-K4M9X2QA',
                'name'          => 'Siti Aminah',
                'email'         => 'siti.aminah@example.com',
                'subject'       => 'Bisa ubah jumlah porsi pesanan?',
                'status'        => 'closed',
                'days_ago'      => 40,
                'messages'      => [
                    ['user',  'Halo, saya sudah pesan Paket Hemat Arisan 50 porsi. Apakah masih bisa ditambah menjadi 60 porsi?'],
                    ['admin', 'Halo Bu Siti, bisa. Penambahan porsi paling lambat H-3 acara. Silakan buat pesanan baru untuk 10 porsi tambahan, nanti kami gabungkan pengirimannya.'],
                    ['user',  'Baik, terima kasih infonya. Sudah saya buat pesanan tambahannya.'],
                    ['admin', 'Sudah kami terima dan gabungkan. Terima kasih, Bu Siti!'],
                ],
            ],
            [
                'ticket_number' => 'HD-R7T3NB5W',
                'name'          => 'Budi Santoso',
                'email'         => 'budi.santoso@example.com',
                'subject'       => 'Pertanyaan tentang menu tanpa kupang',
                'status'        => 'answered',
                'days_ago'      => 9,
                'messages'      => [
                    ['user',  'Salah satu tamu saya alergi kerang. Apakah Paket Kenduri bisa diganti tanpa kupang dan sate kerang?'],
                    ['admin', 'Bisa, Pak. Kami ganti dengan tahu tek dan lentho tambahan. Mohon sebutkan jumlah porsi non-kerang saat konfirmasi pesanan.'],
                ],
            ],
            [
                'ticket_number' => 'HD-C2H8LV6E',
                'name'          => 'Dewi Lestari',
                'email'         => 'dewi.lestari@example.com',
                'subject'       => 'Bukti transfer belum diverifikasi',
                'status'        => 'open',
                'days_ago'      => 3,
                'messages'      => [
                    ['user', 'Saya sudah upload bukti transfer 2 hari lalu, tapi status pesanan masih menunggu verifikasi. Mohon dicek.'],
                ],
            ],
            [
                'ticket_number' => 'HD-P5Y1ZD8J',
                'name'          => 'Maya Anggraini',
                'email'         => 'maya.a@example.com',
                'subject'       => 'Apakah ada layanan antar ke luar kota?',
                'status'        => 'open',
                'days_ago'      => 1,
                'messages'      => [
                    ['user', 'Apakah catering bisa diantar ke Sidoarjo? Jika bisa, apakah ada biaya tambahan?'],
                ],
            ],
        ];

        foreach ($tickets as $t) {
            $createdAt = now()->subDays($t['days_ago']);

            DB::transaction(function () use ($t, $createdAt) {
                $ticket = DB::table('helpdesk_tickets')->where('ticket_number', $t['ticket_number'])->first();

                if ($ticket) {
                    $ticketId = $ticket->id;
                    DB::table('helpdesk_tickets')->where('id', $ticketId)->update([
                        'name'       => $t['name'],
                        'email'      => $t['email'],
                        'subject'    => $t['subject'],
                        'status'     => $t['status'],
                        'updated_at' => now(),
                    ]);
                    DB::table('helpdesk_messages')->where('helpdesk_ticket_id', $ticketId)->delete();
                } else {
                    $ticketId = DB::table('helpdesk_tickets')->insertGetId([
                        'ticket_number' => $t['ticket_number'],
                        'name'          => $t['name'],
                        'email'         => $t['email'],
                        'subject'       => $t['subject'],
                        'status'        => $t['status'],
                        'created_at'    => $createdAt,
                        'updated_at'    => $createdAt,
                    ]);
                }

                foreach ($t['messages'] as $i => [$sender, $message]) {
                    $at = $createdAt->copy()->addHours($i * 4);
                    DB::table('helpdesk_messages')->insert([
                        'helpdesk_ticket_id' => $ticketId,
                        'sender'             => $sender,
                        'message'            => $message,
                        'created_at'         => $at,
                        'updated_at'         => $at,
                    ]);
                }
            });
        }
    }
}