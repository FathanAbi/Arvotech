<?php

namespace Tests\Feature\Admin;

use App\Models\Feedback;
use App\Models\HelpdeskTicket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * SEC-001 / BAC-01 regression tests.
 *
 * `/admin/helpdesk` and `/admin/feedback` were previously guarded by the
 * `auth` middleware only, so any registered account (role = `customer`)
 * could read every support ticket, reply while impersonating the
 * administrator, close tickets, and read all customer feedback.
 */
class AdminRouteAccessTest extends TestCase
{
    use RefreshDatabase;

    private function customer(): User
    {
        return User::factory()->create();
    }

    /**
     * NOTE: `role` is intentionally absent from the User model's fillable list
     * (SEC-012 / MASS-01), so production code must assign it explicitly
     * (`$user->role = 'admin'; $user->save();`). Factories still accept the
     * attribute because Laravel runs factory hydration inside
     * `Model::unguarded()`, which makes `['role' => 'admin']` safe in tests.
     */
    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function ticket(): HelpdeskTicket
    {
        return HelpdeskTicket::create([
            'ticket_number' => 'HD-TEST0001',
            'name' => 'Pelanggan Uji',
            'email' => 'pelanggan@example.com',
            'subject' => 'Pertanyaan pesanan',
        ]);
    }

    public function test_guest_is_redirected_from_admin_helpdesk(): void
    {
        $this->get('/admin/helpdesk')->assertRedirect('/login');
    }

    public function test_guest_is_redirected_from_admin_feedback(): void
    {
        $this->get('/admin/feedback')->assertRedirect('/login');
    }

    public function test_customer_cannot_list_admin_helpdesk(): void
    {
        $this->actingAs($this->customer())
            ->get('/admin/helpdesk')
            ->assertForbidden();
    }

    public function test_customer_cannot_view_admin_helpdesk_ticket(): void
    {
        $ticket = $this->ticket();

        $this->actingAs($this->customer())
            ->get('/admin/helpdesk/' . $ticket->id)
            ->assertForbidden();
    }

    public function test_customer_cannot_reply_to_ticket_as_admin(): void
    {
        $ticket = $this->ticket();

        $this->actingAs($this->customer())
            ->post('/admin/helpdesk/' . $ticket->id . '/reply', [
                'message' => 'Pesan palsu atas nama admin.',
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('helpdesk_messages', 0);
    }

    public function test_customer_cannot_close_ticket(): void
    {
        $ticket = $this->ticket();

        $this->actingAs($this->customer())
            ->post('/admin/helpdesk/' . $ticket->id . '/close')
            ->assertForbidden();

        $this->assertSame('open', $ticket->fresh()->status);
        $this->assertDatabaseCount('helpdesk_messages', 0);
    }

    public function test_customer_cannot_list_admin_feedback(): void
    {
        $this->actingAs($this->customer())
            ->get('/admin/feedback')
            ->assertForbidden();
    }

    public function test_customer_cannot_view_admin_feedback_detail(): void
    {
        $feedback = Feedback::create([
            'name' => 'Pelanggan Uji',
            'email' => 'pelanggan@example.com',
            'category' => 'kritik',
            'message' => 'Pelayanan kurang cepat.',
        ]);

        $this->actingAs($this->customer())
            ->get('/admin/feedback/' . $feedback->id)
            ->assertForbidden();

        $this->assertFalse((bool) $feedback->fresh()->is_read);
    }

    public function test_admin_can_list_helpdesk_tickets(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin/helpdesk')
            ->assertOk();
    }

    public function test_admin_can_view_helpdesk_ticket(): void
    {
        $ticket = $this->ticket();

        $this->actingAs($this->admin())
            ->get('/admin/helpdesk/' . $ticket->id)
            ->assertOk();
    }

    public function test_admin_can_list_feedback(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin/feedback')
            ->assertOk();
    }

    public function test_admin_can_view_feedback_detail(): void
    {
        $feedback = Feedback::create([
            'name' => 'Pelanggan Uji',
            'email' => 'pelanggan@example.com',
            'category' => 'saran',
            'message' => 'Tambahkan menu vegetarian.',
        ]);

        $this->actingAs($this->admin())
            ->get('/admin/feedback/' . $feedback->id)
            ->assertOk();

        $this->assertTrue((bool) $feedback->fresh()->is_read);
    }
}
