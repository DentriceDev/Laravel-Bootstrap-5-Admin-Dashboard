<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationTest extends TestCase
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

    public function withoutAuthorization()
    {
        Gate::before(function () {
            return true;
        });

        return $this;
    }

    public function test_profile_routes_are_protected_from_unauthorized_access()
    {
        $this->get('/user/account')->assertStatus(302)->assertRedirect('/login');
        $this->withOutExceptionHandling();

        $this->loginAsAdmin()->assertRedirect('/user/account');
        $this->get('/dashboard')->assertOk();
    }

    public function test_profile_fields_are_visible_to_authorized_users()
    {
        $this->loginAsAdmin()->assertRedirect('/dashboard');
        $this->withOutExceptionHandling();

        $this->post('/password/confirm', [
            'password' => '1234',
        ]);

        $this->assertStringContainsString('value="Admin"', $this->get('/user/account')->getContent());
        $this->assertStringContainsString('value="admin@admin.com"', $this->get('/user/account')->getContent());
    }

    public function test_profile_password_can_be_updated()
    {
        $this->loginAsAdmin()->assertRedirect('/dashboard');
        $this->withOutExceptionHandling();

        $newData = [
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ];
        $this->post('/password/confirm', [
            'password' => '1234',
        ]);

        $this->from('user/account')->post('/user/password', $newData)->assertRedirect('/user/account');

        //Check if the user is able to log in with the new password
        $this->assertTrue(Auth::attempt([
            'email' => 'admin@admin.com',
            'password' => 'newpassword'
        ]));
    }

    public function test_profile_name_and_email_can_be_updated()
    {
        $this->loginAsAdmin()->assertRedirect('/dashboard');
        $this->withOutExceptionHandling();

        $newData = [
            'name' => 'New name',
            'email' => 'new@email.com',
        ];
        $this->post('/password/confirm', [
            'password' => '1234',
        ])->assertRedirect('/dashboard');
        $this->post('/user/profile', $newData);
        $this->assertDatabaseHas('users', $newData);

        // Check if the user is still able to log in - password unchanged
        $this->assertTrue(Auth::attempt([
            'email' => 'new@email.com',
            'password' => '1234'
        ]));
    }

    public function test_a_user_can_register()
    {
        $this->withOutExceptionHandling();

        $newData = [
            'name' => 'New name',
            'email' => 'new@email.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword'
        ];
        $this->post('/register', $newData)->assertRedirect('/dashboard');
    }

    public function test_must_verify_email()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);
        $this->actingAs($user)->get('/dashboard')->assertRedirect('/email/verify');
    }

    public function test_email_can_be_verified()
    {
        $this->withoutAuthorization();

        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $this->actingAs($user)->get($verificationUrl);
        Event::assertDispatched(Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());

        $this->actingAs($user)->post('/password/confirm', [
            'password' => 'password',
        ]);
        $this->get('/user/account')->assertOk();
    }

    public function test_password_confirmation_page()
    {
        $this->withoutAuthorization();
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user)->get('/user/account')->assertRedirect('/password/confirm');

        $this->actingAs($user)->post('/password/confirm', [
            'password' => 'password',
        ])->assertRedirect()->assertSessionHasNoErrors();
    }

    // public function test_password_at_least_one_uppercase_lowercase_letter()
    // {
    //     $user = [
    //         'name' => 'New name',
    //         'email' => 'new@email.com',
    //     ];

    //     $invalidPassword = '12345678';
    //     $validPassword = 'a12345678';

    //     $this->post('/register', $user + [
    //         'password' => $invalidPassword,
    //         'password_confirmation' => $invalidPassword
    //     ]);
    //     $this->assertDatabaseMissing('users', $user);

    //     $this->post('/register', $user + [
    //             'password' => $validPassword,
    //             'password_confirmation' => $validPassword
    //         ]);
    //     $this->assertDatabaseHas('users', $user);
    // }

}
