<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Order matters: orders depend on catering packages.
        $this->call([
            AdminSeeder::class,
            MenuSeeder::class,
            CateringPackageSeeder::class,
            OrderSeeder::class,
            HelpdeskSeeder::class,
            FeedbackSeeder::class,
        ]);
    }
}