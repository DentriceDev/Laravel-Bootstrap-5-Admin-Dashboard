<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionsTest extends TestCase
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

    public function test_permissions_routes_are_protected_from_unauthorized_access()
    {
        $this->get('/permissions')->assertStatus(302)->assertRedirect('/login');

        $this->loginAsAdmin()->assertRedirect('/permissions');
        $this->get('/permissions')->assertStatus(200);
    }

    public function test_a_permission_can_be_created()
    {
        $this->withOutExceptionHandling();
        $this->loginAsAdmin()->assertRedirect('/dashboard');

        $newData = [
            'title' => 'Test_perm'
        ];

        $this->from('/permissions')->post('/permissions', $newData)->assertRedirect('/permissions');
    }

    public function test_a_permission_can_be_updated()
    {
        $this->withOutExceptionHandling();
        $this->loginAsAdmin()->assertRedirect('/dashboard');

        $newData = [
            'title' => 'Test_perm'
        ];

        $this->from('/permissions')->post('/permissions', $newData)->assertRedirect('/permissions');
        $perm = Permission::where('title', 'Test_perm')->first();
        $this->assertNotNull($perm);

        $testData = [
            'title' => 'New_title'
        ];

        $this->get('/permissions/'.$perm->id.'/edit/')->assertStatus(200);
        $this->from('/permissions')->put('/permissions/'.$perm->id, $testData)->assertRedirect('/permissions');
        $this->assertDatabaseHas('permissions', $testData);
        $this->assertNotNull(Permission::where('title', 'New_title')->first());
    }

    public function test_a_permission_can_be_deleted()
    {
        $this->withOutExceptionHandling();
        $this->loginAsAdmin()->assertRedirect('/dashboard');

        $newData = [
            'title' => 'Test_perm'
        ];

        $this->from('/permissions')->post('/permissions', $newData)->assertRedirect('/permissions');
        $perm = Permission::where('title', 'Test_perm')->first();
        $this->assertNotNull($perm);
        $this->from('/permissions')->delete('/permissions/'.$perm->id)->assertRedirect('/permissions');
        $this->assertNull(Permission::where('title', 'Test_perm')->first());
    }
}
