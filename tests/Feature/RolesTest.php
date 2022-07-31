<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RolesTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed');
    }

    protected function loginAsAdmin()
    {
        $user = $this->from('/login')->post('/login', [
            'email' => 'admin@admin.com',
            'password' => '1234',
        ]);

        return $user;
    }

    public function test_roles_routes_are_protected_from_unauthorized_access()
    {
        $this->get('/roles')->assertStatus(302)->assertRedirect('/login');
        $this->withOutExceptionHandling();

        $this->loginAsAdmin()->assertRedirect('/roles');
        $this->get('/roles')->assertStatus(200);
    }

    public function test_a_role_can_be_created()
    {
        $this->withoutExceptionHandling();
        $this->loginAsAdmin()->assertRedirect('/dashboard');

        $this->post('/roles', [
            'title' => 'Test Role',
            'permissions' => [1,2],
        ])->assertRedirect('/roles');

        $this->assertDatabaseHas('roles', [
            'title' => 'Test Role',
        ]);
    }

    public function test_a_role_can_be_updated()
    {
        $this->withoutExceptionHandling();
        $this->loginAsAdmin()->assertRedirect('/dashboard');

        $this->post('/roles', [
            'title' => 'test_role',
            'permissions' => [2,5],
        ])->assertRedirect('/roles');

        $role = Role::where('title', 'test_role')->first();
        $this->get('/permissions/'.$role->id.'/edit/')->assertStatus(200);

        $this->put('/roles/' . $role->id, [
            'title' => 'test role',
            'permissions' => [1,3],
        ])->assertRedirect('/roles');

        $this->assertDatabaseHas('roles', [
            'title' => 'test role',
        ]);
    }

    public function test_a_role_can_be_deleted()
    {
        $this->withoutExceptionHandling();
        $response = $this->loginAsAdmin()->assertRedirect('/dashboard');

        $response = $this->post('/roles', [
            'title' => 'test_role1',
            'permissions' => [2,5],
        ]);

        $role = Role::where('title', 'test_role1')->first();
        $response = $this->from('/roles')->delete('/roles/' . $role->id);
        $response->assertRedirect('/roles');
        $role = Role::where('title', 'test_role1')->first();
        $this->assertNull($role);
    }
}
