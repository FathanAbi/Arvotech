<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // SEC-012 (MASS-01): `role` is no longer mass assignable, so it has to
        // be set explicitly after the record is created or updated.
        $admin = User::updateOrCreate(
            [
                'email' => 'admin@cakgendut.com',
            ],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
            ]
        );

        $admin->role = 'admin';
        $admin->save();
    }
}
