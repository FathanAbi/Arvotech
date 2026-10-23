<?php

namespace Tests\Feature\Security;

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * SEC-012 / MASS-01 regression tests.
 *
 * `role` used to be listed in the User model's fillable attributes, so any
 * controller that later reached for `User::create($request->all())` or
 * `$user->fill($request->all())` would have let an attacker mint an admin
 * account. The attribute is now guarded; privileged roles are assigned
 * explicitly by Database\Seeders\AdminSeeder.
 */
class UserMassAssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_is_not_fillable(): void
    {
        $user = new User;

        $this->assertFalse($user->isFillable('role'));
        $this->assertTrue($user->isFillable('name'));
        $this->assertTrue($user->isFillable('email'));
        $this->assertTrue($user->isFillable('password'));

        $this->assertSame(
            ['name', 'email', 'password'],
            $user->getFillable()
        );
    }

    public function test_mass_assignment_cannot_escalate_privileges(): void
    {
        $user = User::create([
            'name' => 'Penyusup',
            'email' => 'penyusup@example.com',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $this->assertSame('customer', $user->fresh()->role);
        $this->assertDatabaseHas('users', [
            'email' => 'penyusup@example.com',
            'role' => 'customer',
        ]);
    }

    public function test_fill_does_not_apply_role(): void
    {
        $user = User::factory()->create();

        $user->fill(['name' => 'Nama Baru', 'role' => 'admin'])->save();

        $fresh = $user->fresh();

        $this->assertSame('Nama Baru', $fresh->name);
        $this->assertSame('customer', $fresh->role);
    }

    public function test_role_can_still_be_assigned_explicitly(): void
    {
        $user = User::factory()->create();

        $user->role = 'admin';
        $user->save();

        $this->assertSame('admin', $user->fresh()->role);
    }

    /**
     * The seeder is invoked directly here on purpose: `php artisan db:seed` and
     * `$this->seed()` both run inside `Model::unguarded()`, which would mask a
     * regression where `role` is passed through the mass-assignment array
     * instead of being assigned explicitly.
     */
    public function test_admin_seeder_still_provisions_an_admin(): void
    {
        (new AdminSeeder)->run();

        $admin = User::where('email', 'admin@cakgendut.com')->firstOrFail();

        $this->assertSame('admin', $admin->role);
        $this->assertSame('Administrator', $admin->name);
    }

    public function test_admin_seeder_is_idempotent(): void
    {
        (new AdminSeeder)->run();
        (new AdminSeeder)->run();

        $this->assertSame(
            1,
            User::where('email', 'admin@cakgendut.com')->count()
        );
        $this->assertSame(
            'admin',
            User::where('email', 'admin@cakgendut.com')->firstOrFail()->role
        );
    }

    public function test_admin_seeder_works_through_the_artisan_command(): void
    {
        $this->seed(AdminSeeder::class);

        $this->assertSame(
            'admin',
            User::where('email', 'admin@cakgendut.com')->firstOrFail()->role
        );
    }

    /**
     * Factories hydrate models inside `Model::unguarded()`, so the `role`
     * override used by the existing test helpers keeps working even though the
     * attribute is guarded. This pins that behaviour down explicitly.
     */
    public function test_factory_can_still_set_role_for_tests(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->assertSame('admin', $admin->fresh()->role);
    }
}
