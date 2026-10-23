<?php

namespace Tests\Feature\Security;

use App\Models\CateringPackage;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * SEC-008 / DATA-01 regression tests.
 *
 * The orders.package_id foreign key used to cascade on delete, so removing a
 * catering package silently wiped every historical order and payment. The FK
 * is now restrictOnDelete(), and the controller refuses deletion while orders
 * still reference the package.
 */
class CateringPackageDeletionTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_package_with_orders_cannot_be_deleted(): void
    {
        $package = CateringPackage::factory()->create();
        $order = Order::factory()->create(['package_id' => $package->id]);

        $this->actingAs($this->admin())
            ->delete('/admin/catering-packages/'.$package->id)
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('catering_packages', ['id' => $package->id]);
        $this->assertDatabaseHas('orders', ['id' => $order->id]);
    }

    public function test_package_without_orders_can_be_deleted(): void
    {
        $package = CateringPackage::factory()->create();

        $this->actingAs($this->admin())
            ->delete('/admin/catering-packages/'.$package->id)
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('catering_packages', ['id' => $package->id]);
    }
}
