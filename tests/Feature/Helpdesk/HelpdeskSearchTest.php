<?php

namespace Tests\Feature\Helpdesk;

use App\Models\HelpdeskMessage;
use App\Models\HelpdeskTicket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * SEC-003 / INFO-01 regression tests.
 *
 * `HelpdeskController::search()` used to build `LIKE %keyword%` clauses on
 * both `ticket_number` and `name`, so submitting `%` or a single letter
 * returned every ticket in the database together with a direct link to each
 * one. Lookup now requires an exact ticket number plus the email that
 * created the ticket.
 */
class HelpdeskSearchTest extends TestCase
{
    use RefreshDatabase;

    private function ticket(array $attributes = []): HelpdeskTicket
    {
        $ticket = HelpdeskTicket::create(array_merge([
            'ticket_number' => 'HD-TEST0001',
            'name' => 'Pelanggan Uji',
            'email' => 'pelanggan@example.com',
            'subject' => 'Pertanyaan pesanan',
        ], $attributes));

        HelpdeskMessage::create([
            'helpdesk_ticket_id' => $ticket->id,
            'sender' => 'user',
            'message' => 'Isi laporan rahasia pelanggan.',
        ]);

        return $ticket;
    }

    private function search(array $payload)
    {
        return $this->post('/helpdesk/search', $payload);
    }

    public function test_wildcard_does_not_dump_tickets(): void
    {
        $this->ticket();

        $this->search([
            'ticket_number' => '%',
            'email' => 'pelanggan@example.com',
        ])->assertRedirect(route('helpdesk.search.form'))
            ->assertSessionHasErrors('ticket_number');

        $this->assertSame(0, HelpdeskTicket::where('ticket_number', '%')->count());
    }

    public function test_wildcard_suffix_does_not_match(): void
    {
        $this->ticket();

        $this->search([
            'ticket_number' => 'HD-TEST%',
            'email' => 'pelanggan@example.com',
        ])->assertSessionHasErrors('ticket_number');
    }

    public function test_partial_ticket_number_does_not_match(): void
    {
        $this->ticket();

        $this->search([
            'ticket_number' => 'HD-TEST',
            'email' => 'pelanggan@example.com',
        ])->assertSessionHasErrors('ticket_number');
    }

    public function test_name_can_no_longer_be_searched(): void
    {
        $this->ticket();

        // Previously matched via `orWhere('name', 'like', '%...%')`.
        $this->search([
            'ticket_number' => 'Pelanggan Uji',
            'email' => 'pelanggan@example.com',
        ])->assertSessionHasErrors('ticket_number');
    }

    public function test_single_letter_does_not_dump_tickets(): void
    {
        $this->ticket();

        $this->search([
            'ticket_number' => 'a',
            'email' => 'pelanggan@example.com',
        ])->assertSessionHasErrors('ticket_number');
    }

    public function test_email_is_required(): void
    {
        $this->ticket();

        $this->search([
            'ticket_number' => 'HD-TEST0001',
        ])->assertSessionHasErrors('email');
    }

    public function test_ticket_number_is_required(): void
    {
        $this->ticket();

        $this->search([
            'email' => 'pelanggan@example.com',
        ])->assertSessionHasErrors('ticket_number');
    }

    public function test_correct_ticket_with_wrong_email_does_not_leak(): void
    {
        $ticket = $this->ticket();

        $this->search([
            'ticket_number' => $ticket->ticket_number,
            'email' => 'penyusup@example.com',
        ])->assertRedirect(route('helpdesk.search.form'))
            ->assertSessionHasErrors('ticket_number');
    }

    public function test_wrong_email_message_does_not_reveal_ticket_existence(): void
    {
        $ticket = $this->ticket();

        $this->search([
            'ticket_number' => $ticket->ticket_number,
            'email' => 'penyusup@example.com',
        ])->assertSessionHasErrors([
            'ticket_number' => 'Nomor tiket dan email tidak cocok dengan tiket mana pun.',
        ]);
    }

    public function test_exact_ticket_and_email_redirects_to_ticket(): void
    {
        $ticket = $this->ticket();

        $this->search([
            'ticket_number' => $ticket->ticket_number,
            'email' => $ticket->email,
        ])->assertRedirect(route('helpdesk.show', $ticket->ticket_number));

        $this->followingRedirects()
            ->post('/helpdesk/search', [
                'ticket_number' => $ticket->ticket_number,
                'email' => $ticket->email,
            ])
            ->assertOk()
            ->assertSee($ticket->subject)
            ->assertSee($ticket->ticket_number);
    }

    public function test_ticket_number_match_is_case_insensitive_but_still_exact(): void
    {
        $ticket = $this->ticket();

        $this->search([
            'ticket_number' => strtolower($ticket->ticket_number),
            'email' => $ticket->email,
        ])->assertRedirect(route('helpdesk.show', $ticket->ticket_number));
    }

    public function test_search_form_renders_the_two_required_fields(): void
    {
        $this->get('/helpdesk/search')
            ->assertOk()
            ->assertSee('name="ticket_number"', false)
            ->assertSee('name="email"', false);
    }

    public function test_search_never_returns_a_list_of_tickets(): void
    {
        $this->ticket();

        $response = $this->search([
            'ticket_number' => '%',
            'email' => 'pelanggan@example.com',
        ]);

        // The bulk result table was removed entirely: only a redirect plus a
        // validation error is produced, never a rendered list of records.
        $response->assertRedirect();
        $this->assertStringNotContainsString(
            'Hasil Pencarian Tiket',
            (string) $response->getContent()
        );
    }
}
