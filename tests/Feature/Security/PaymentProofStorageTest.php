<?php

namespace Tests\Feature\Security;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

/**
 * SEC-007 / STOR-01 regression tests.
 *
 * Payment proofs used to be written to the `public` disk, so anyone who knew
 * or guessed a filename could read bank receipts at /storage/payment-proofs/...
 * They now live on the `local` (private) disk and are only reachable through
 * an admin-only route or the customer's signed link.
 */
class PaymentProofStorageTest extends TestCase
{
    use RefreshDatabase;

    private function proofOrder(): Order
    {
        $order = Order::factory()->waitingVerification()->create();

        Storage::disk('local')->put(
            $order->payment_proof,
            (string) UploadedFile::fake()->image('proof.jpg')->get()
        );

        return $order;
    }

    private function signedProofUrl(Order $order): string
    {
        return URL::temporarySignedRoute(
            'orders.payment-proof',
            now()->addHours(1),
            ['order' => $order->id]
        );
    }

    public function test_guest_is_redirected_from_admin_proof_route(): void
    {
        $order = $this->proofOrder();

        $this->get('/admin/orders/'.$order->id.'/payment-proof')
            ->assertRedirect('/login');
    }

    public function test_customer_cannot_read_proof_from_admin_route(): void
    {
        $order = $this->proofOrder();

        $this->actingAs(User::factory()->create())
            ->get('/admin/orders/'.$order->id.'/payment-proof')
            ->assertForbidden();
    }

    public function test_admin_can_stream_proof(): void
    {
        $order = $this->proofOrder();

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get('/admin/orders/'.$order->id.'/payment-proof')
            ->assertOk();
    }

    public function test_guest_cannot_read_proof_without_signature(): void
    {
        $order = $this->proofOrder();

        $this->get('/orders/'.$order->id.'/payment-proof')
            ->assertForbidden();
    }

    public function test_guest_can_stream_proof_through_signed_url(): void
    {
        $order = $this->proofOrder();

        $this->get($this->signedProofUrl($order))->assertOk();
    }

    public function test_upload_lands_on_private_disk_not_public(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $order = Order::factory()->create();

        $uploadUrl = URL::temporarySignedRoute(
            'orders.upload-proof',
            now()->addHours(1),
            ['order' => $order->id]
        );

        $this->post($uploadUrl, [
            'payment_proof' => UploadedFile::fake()->image('proof.jpg'),
        ])->assertRedirect();

        $order->refresh();

        Storage::disk('local')->assertExists($order->payment_proof);
        Storage::disk('public')->assertMissing($order->payment_proof);
    }
}
