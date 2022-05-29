<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class PermissionsTest extends TestCase
{
    use RefreshDatabase;

    public function withoutAuthorization()
    {
        Gate::before(function () {
            return true;
        });

        return $this;
    }

    public function test_permissions_are_protected_from_public()
    {
        $response = $this->get('/permissions');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_permissions_are_visible()
    {
        $this->withoutAuthorization();

        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/permissions');
        $response->assertStatus(200);
    }

    public function test_permission_create()
    {
        $this->withoutAuthorization();

        $user = User::factory()->create();
        $newData = [
            'title' => 'Test_perm'
        ];
        $response = $this->actingAs($user)->post('/permissions', $newData);
        $response->assertRedirect('/permissions');
    }

    public function test_permission_update_successful()
    {
        $this->withoutAuthorization();

        $user = User::factory()->create();
        $newData = [
            'title' => 'Test_perm'
        ];
        $response = $this->actingAs($user)->post('/permissions', $newData);

        $perm = Permission::where('title', 'Test_perm')->first();
        $this->assertNotNull($perm);

        $testData = [
            'title' => 'New_title'
        ];

        $this->actingAs($user)->get('/permissions/'.$perm->id.'/edit/')->assertStatus(200);

        $response = $this->actingAs($user)->put('/permissions/'.$perm->id, $testData);
        $this->assertDatabaseHas('permissions', $testData);
        $perm1 = Permission::where('title', 'New_title')->first();
        $this->assertNotNull($perm1);
        $response->assertRedirect('/permissions');
    }

    public function test_permission_delete_successful()
    {
        $this->withoutAuthorization();

        $user = User::factory()->create();
        $newData = [
            'title' => 'Test_perm'
        ];
        $this->actingAs($user)->post('/permissions', $newData);

        $perm = Permission::where('title', 'Test_perm')->first();
        $this->assertNotNull($perm);

        $this->actingAs($user)->delete('/permissions/'.$perm->id);
        $perm = Permission::where('title', 'Test_perm')->first();
        $this->assertNull($perm);
    }
}
