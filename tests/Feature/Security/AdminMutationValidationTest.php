<?php

namespace Tests\Feature\Security;

use App\Models\CateringPackage;
use App\Models\HelpdeskMessage;
use App\Models\HelpdeskTicket;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * SEC-010 / VAL-01 regression tests.
 *
 * Admin mutation endpoints used to accept unvalidated input: an arbitrary
 * order status hit the `orders.status` enum and threw a database exception,
 * an empty helpdesk reply was persisted, and updating a catering package wrote
 * request values straight into the model. All three now validate first.
 */
class AdminMutationValidationTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_invalid_order_status_is_rejected(): void
    {
        $order = Order::factory()->create(['status' => 'pending_payment']);

        $this->actingAs($this->admin())
            ->patch('/admin/orders/'.$order->id.'/status', ['status' => 'bogus'])
            ->assertSessionHasErrors('status');

        $this->assertSame('pending_payment', $order->fresh()->status);
    }

    public function test_valid_order_status_is_accepted(): void
    {
        $order = Order::factory()->create(['status' => 'pending_payment']);

        $this->actingAs($this->admin())
            ->patch('/admin/orders/'.$order->id.'/status', ['status' => 'shipping'])
            ->assertSessionHasNoErrors();

        $this->assertSame('shipping', $order->fresh()->status);
    }

    public function test_empty_helpdesk_reply_is_rejected(): void
    {
        $ticket = HelpdeskTicket::create([
            'ticket_number' => 'HD-VAL00001',
            'name' => 'Pelanggan Uji',
            'email' => 'pelanggan@example.com',
            'subject' => 'Validasi balasan',
        ]);

        $this->actingAs($this->admin())
            ->post('/admin/helpdesk/'.$ticket->id.'/reply', ['message' => ''])
            ->assertSessionHasErrors('message');

        $this->assertSame(0, HelpdeskMessage::count());
    }

    public function test_invalid_catering_package_update_is_rejected(): void
    {
        $package = CateringPackage::factory()->create(['nama_paket' => 'Paket Asli']);

        $this->actingAs($this->admin())
            ->put('/admin/catering-packages/'.$package->id, [
                'nama_paket' => '',
                'deskripsi' => 'Deskripsi baru',
                'harga' => 50000,
            ])
            ->assertSessionHasErrors('nama_paket');

        $this->assertSame('Paket Asli', $package->fresh()->nama_paket);
    }
}
