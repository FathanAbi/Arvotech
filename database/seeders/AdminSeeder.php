<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'admin@cakgendut.com';

        // Written via the query builder because `role` is intentionally
        // NOT mass-assignable on the User model.
        // An existing admin is left untouched (password is not overwritten).
        if (DB::table('users')->where('email', $email)->exists()) {
            return;
        }

        DB::table('users')->insert([
            'name'              => 'admin',
            'email'             => $email,
            'email_verified_at' => null,
            'password'          => Hash::make(env('ADMIN_SEED_PASSWORD', 'password')),
            'role'              => 'admin',
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);
    }
}