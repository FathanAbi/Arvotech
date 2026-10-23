<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * SEC-004 / AUTH-01 regression tests.
 *
 * Public self-registration used to be enabled even though this application has
 * no customer portal (orders are placed anonymously). Anyone could POST to
 * /register to create unlimited accounts -- the prerequisite for the BAC-01
 * privilege-escalation chain -- and the handler then redirected to an undefined
 * `dashboard` route, producing an HTTP 500 while leaving the account persisted.
 *
 * The routes are now removed entirely; administrator accounts are provisioned
 * via Database\Seeders\AdminSeeder only.
 */
class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_is_not_reachable(): void
    {
        $this->get('/register')->assertNotFound();
    }

    public function test_registration_submission_is_not_reachable(): void
    {
        $this->post('/register', [
            'name' => 'Penyusup',
            'email' => 'penyusup@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertNotFound();
    }

    public function test_valid_registration_payload_creates_no_account(): void
    {
        $this->post('/register', [
            'name' => 'Penyusup',
            'email' => 'penyusup@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseMissing('users', ['email' => 'penyusup@example.com']);
        $this->assertGuest();
    }

    public function test_register_route_name_no_longer_exists(): void
    {
        $this->assertFalse(Route::has('register'));
    }

    public function test_login_remains_reachable(): void
    {
        $this->get('/login')->assertOk();
    }
}
