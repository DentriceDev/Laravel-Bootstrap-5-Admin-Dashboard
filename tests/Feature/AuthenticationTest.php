<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;
    // use WithoutMiddleware; // should not be used when testing with middleware with has $errors variable

    public function withoutAuthorization()
    {
        Gate::before(function () {
            return true;
        });

        return $this;
    }

    public function test_profile_routes_are_protected_from_public()
    {
        $response = $this->get('/user/account');
        $response->assertStatus(302);
        $response->assertRedirect('/login');

        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertOk();
    }

    public function test_profile_fields_are_visible()
    {
        $this->withoutAuthorization();

        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/password/confirm', [
            'password' => 'password',
        ]);
        $response = $this->actingAs($user)->get('/user/account');
        $this->assertStringContainsString('value="'.$user->name.'"', $response->getContent());
        $this->assertStringContainsString('value="'.$user->email.'"', $response->getContent());
    }

    public function test_profile_password_update_successful()
    {
        $this->withoutAuthorization();

        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);
        $newData = [
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ];
        $this->actingAs($user)->post('/password/confirm', [
            'password' => 'password',
        ]);
        $this->actingAs($user)->post('/user/password', $newData);

        //Check if the user is able to log in with the new password
        $this->assertTrue(Auth::attempt([
            'email' => $user->email,
            'password' => 'newpassword'
        ]));
    }

    public function test_profile_name_and_email_update_successful()
    {
        $this->withoutAuthorization();
        $this->withOutExceptionHandling();

        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);
        $newData = [
            'name' => 'New name',
            'email' => 'new@email.com',
        ];
        $this->actingAs($user)->post('/password/confirm', [
            'password' => 'password',
        ]);
        $this->actingAs($user)->post('/user/profile', $newData);
        $this->assertDatabaseHas('users', $newData);

        // Check if the user is still able to log in - password unchanged
        $this->assertTrue(Auth::attempt([
            'email' => $user->email,
            'password' => 'password'
        ]));
    }

    public function test_user_can_register()
    {
        $this->withoutAuthorization();

        $newData = [
            'name' => 'New name',
            'email' => 'new1@email.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword'
        ];
        $response = $this->post('/register', $newData);
        $response->assertRedirect('/dashboard');
    }

    public function test_must_verify_email()
    {
        $this->withoutAuthorization();
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

        $response = $this->actingAs($user)->post('/password/confirm', [
            'password' => 'password',
        ]);
        $response = $this->get('/user/account');
        $response->assertOk();
    }

    public function test_password_confirmation_page()
    {
        $this->withoutAuthorization();
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/user/account');
        $response->assertRedirect('/password/confirm');

        $response = $this->actingAs($user)->post('/password/confirm', [
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
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
