<?php

namespace Database\Factories;

use App\Models\CateringPackage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CateringPackage>
 */
class CateringPackageFactory extends Factory
{
    protected $model = CateringPackage::class;

    public function definition(): array
    {
        return [
            'nama_paket' => 'Paket ' . fake()->unique()->words(2, true),
            'deskripsi' => fake()->sentence(),
            'harga' => fake()->numberBetween(15000, 75000),
            'foto' => null,
        ];
    }
}
