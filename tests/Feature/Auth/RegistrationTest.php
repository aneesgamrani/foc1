<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function test_registration_screen_requires_authentication(): void
    {
        $response = $this->get('/register');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_users_can_register_new_users(): void
    {
        $creator = User::factory()->create();
        Permission::findOrCreate('user-create', 'web');
        $creator->givePermissionTo('user-create');

        $response = $this->actingAs($creator)->post('/register', [
            'name' => 'Test User',
            'company_name' => 'Acme Corporation',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticatedAs($creator);
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'company_name' => 'Acme Corporation',
            'created_by' => $creator->id,
        ]);
        $response->assertRedirect('/dashboard');
    }
}
