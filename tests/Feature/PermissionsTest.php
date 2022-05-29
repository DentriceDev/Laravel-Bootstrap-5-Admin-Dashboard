<?php

namespace Tests\Feature;

use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PermissionsTest extends TestCase
{
    public function withoutAuthorization()
    {
        \Gate::before(function () {
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

        $response = $this->get('/permissions');
        $response->assertStatus(200);
    }

    public function test_permission_create()
    {
        $this->withoutAuthorization();

        $newData = [
            'title' => 'Test_perm'
        ];
        $response = $this->post('/permissions', $newData);
        $response->assertRedirect('/permissions');
    }

    public function test_permission_update_successful()
    {
        $this->withoutAuthorization();
    }

    public function test_permission_delete_successful()
    {
        $this->withoutAuthorization();
    }
}
