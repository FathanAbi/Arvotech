<?php

namespace Tests\Feature\Order;

use App\Models\CateringPackage;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

/**
 * SEC-002 / IDOR-01 regression tests.
 *
 * `Route::resource('orders', ...)` used to expose every CRUD action publicly,
 * so anyone could walk /orders/1, /orders/2, ... and harvest customer PII, or
 * POST to /orders/{id}/upload-proof to overwrite a receipt and flip the order
 * status. Detail and upload are now reachable only via signed URLs.
 */
class OrderAccessTest extends TestCase
{
    use RefreshDatabase;

    private function signedShowUrl(Order $order, int $hours = 1): string
    {
        return URL::temporarySignedRoute(
            'orders.show',
            now()->addHours($hours),
            ['order' => $order->id]
        );
    }

    private function signedUploadUrl(Order $order, int $hours = 1): string
    {
        return URL::temporarySignedRoute(
            'orders.upload-proof',
            now()->addHours($hours),
            ['order' => $order->id]
        );
    }

    public function test_guest_cannot_view_order_without_signature(): void
    {
        $order = Order::factory()->create();

        $this->get('/orders/' . $order->id)->assertForbidden();
    }

    public function test_guest_can_view_order_through_signed_url(): void
    {
        $order = Order::factory()->create();

        $this->get($this->signedShowUrl($order))
            ->assertOk()
            ->assertSee($order->invoice_number)
            ->assertSee($order->customer_name)
            ->assertSee($order->email)
            ->assertSee($order->phone);
    }

    public function test_expired_signed_url_is_rejected(): void
    {
        $order = Order::factory()->create();

        $url = $this->signedShowUrl($order);

        $this->travel(3)->hours();

        $this->get($url)->assertForbidden();
    }

    public function test_tampered_signature_is_rejected(): void
    {
        $order = Order::factory()->create();

        $url = $this->signedShowUrl($order) . 'a';

        $this->get($url)->assertForbidden();
    }

    public function test_guest_cannot_upload_proof_without_signature(): void
    {
        Storage::fake('local');

        $order = Order::factory()->create();

        $this->post('/orders/' . $order->id . '/upload-proof', [
            'payment_proof' => UploadedFile::fake()->image('proof.jpg'),
        ])->assertForbidden();

        $order->refresh();

        $this->assertNull($order->payment_proof);
        $this->assertSame('pending_payment', $order->status);
        $this->assertCount(0, Storage::disk('local')->allFiles());
    }

    public function test_guest_can_upload_proof_through_signed_url(): void
    {
        Storage::fake('local');

        $order = Order::factory()->create();

        $this->post($this->signedUploadUrl($order), [
            'payment_proof' => UploadedFile::fake()->image('proof.jpg'),
        ])->assertRedirect();

        $order->refresh();

        $this->assertSame('waiting_verification', $order->status);
        $this->assertNotNull($order->payment_proof);
        Storage::disk('local')->assertExists($order->payment_proof);
    }

    public function test_unhandled_resource_actions_are_not_routable(): void
    {
        $order = Order::factory()->create();

        // index/edit/update/destroy were removed from the resource. Where
        // another verb still occupies the URI the router answers 405 instead
        // of 404; what matters is that no controller is reached and nothing
        // 500s as it used to.
        $this->get('/orders')->assertStatus(405);
        $this->get('/orders/' . $order->id . '/edit')->assertNotFound();
        $this->put('/orders/' . $order->id, ['customer_name' => 'Hijack'])->assertStatus(405);
        $this->delete('/orders/' . $order->id)->assertStatus(405);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'customer_name' => $order->customer_name,
        ]);
    }

    public function test_booking_form_and_store_remain_public(): void
    {
        $package = CateringPackage::factory()->create([
            'harga' => 25000,
        ]);

        $this->get('/orders/create')->assertOk();

        $response = $this->post('/orders', [
            'customer_name' => 'Pelanggan Uji',
            'email' => 'pelanggan@example.com',
            'phone' => '081234567890',
            'event_date' => now()->addDays(5)->toDateString(),
            'delivery_address' => 'Jl. Contoh No. 1, Surabaya',
            'package_id' => $package->id,
            'quantity' => 4,
        ]);

        $response->assertRedirect();
        $this->assertStringContainsString(
            'signature=',
            (string) $response->headers->get('Location')
        );

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Pelanggan Uji',
            'total_price' => 100000,
            'status' => 'pending_payment',
        ]);
    }

    public function test_tracking_search_ignores_wildcard_and_matches_exact_invoice(): void
    {
        $order = Order::factory()->create();

        // Wildcard must not dump the customer base.
        $this->post('/cek-pesanan', ['keyword' => '%'])
            ->assertOk()
            ->assertDontSee($order->invoice_number);

        // Single letter must not dump the customer base.
        $this->post('/cek-pesanan', ['keyword' => 'a'])
            ->assertOk()
            ->assertDontSee($order->invoice_number);

        // Exact invoice returns exactly that order plus a signed detail link.
        $this->post('/cek-pesanan', ['keyword' => $order->invoice_number])
            ->assertOk()
            ->assertSee($order->invoice_number)
            ->assertSee('signature=', false);
    }
}
