<?php

namespace Database\Factories;

use App\Models\CateringPackage;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 20);

        return [
            'invoice_number' => 'INV-'
                . now()->format('YmdHis')
                . '-'
                . fake()->unique()->numberBetween(1000, 999999),

            'customer_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('08##########'),

            'event_date' => now()->addDays(
                fake()->numberBetween(1, 30)
            )->toDateString(),

            'delivery_address' => fake()->address(),

            'package_id' => CateringPackage::factory(),
            'quantity' => $quantity,
            'total_price' => $quantity * 25000,

            'status' => 'pending_payment',
            'payment_proof' => null,
        ];
    }

    public function waitingVerification(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'waiting_verification',
            'payment_proof' => 'payment-proofs/proof.jpg',
        ]);
    }
}
