<?php

namespace Tests\Feature\Security;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * SEC-006 / RATE-01 regression tests.
 *
 * Every public POST endpoint used to be unthrottled, so a script could spam
 * orders/feedback/tickets, fill the disk with payment-proof uploads, hammer the
 * search forms, or enumerate password resets. Each endpoint now carries a
 * `throttle` middleware, so the request that exceeds the budget must answer
 * HTTP 429 instead of reaching the controller.
 *
 * The middleware runs before the controller, so an empty (invalid) payload
 * still consumes the budget — which is exactly what makes the limit testable
 * without seeding data.
 */
class RateLimitTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Fire the endpoint `$allowed` times, asserting none of the allowed
     * requests were throttled, then return the response that exceeds the
     * budget.
     */
    private function exhaust(string $uri, array $payload, int $allowed, int $allowedStatus)
    {
        for ($i = 0; $i < $allowed; $i++) {
            $this->post($uri, $payload)->assertStatus($allowedStatus);
        }

        return $this->post($uri, $payload);
    }

    public function test_order_creation_is_throttled_to_ten_per_minute(): void
    {
        $this->exhaust('/orders', [], 10, 302)->assertStatus(429);
    }

    public function test_feedback_submission_is_throttled_to_ten_per_minute(): void
    {
        $this->exhaust('/feedback', [], 10, 302)->assertStatus(429);
    }

    public function test_helpdesk_submission_is_throttled_to_ten_per_minute(): void
    {
        $this->exhaust('/helpdesk', [], 10, 302)->assertStatus(429);
    }

    public function test_order_tracking_search_is_throttled_to_thirty_per_minute(): void
    {
        // A well-formed keyword reaches the view (200), not a redirect.
        $this->exhaust('/cek-pesanan', ['keyword' => 'INV-NOPE'], 30, 200)
            ->assertStatus(429);
    }

    public function test_helpdesk_search_is_throttled_to_thirty_per_minute(): void
    {
        $this->exhaust('/helpdesk/search', [], 30, 302)->assertStatus(429);
    }

    public function test_password_reset_request_is_throttled_to_five_per_minute(): void
    {
        $this->exhaust('/forgot-password', [], 5, 302)->assertStatus(429);
    }
}
