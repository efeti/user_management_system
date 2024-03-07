<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Database\Factories\ClientFactory;
use Laravel\Passport\Passport;
use Laravel\Passport\TokenRepository;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic test example.
     */
    public function test_user_can_sign_up_successfully(): void
    {
        $this->postJson(route('user.register'), [
            'name' => $this->faker->name,
            'email' => $email = $this->faker->email,
            'password' => 'password',
        ])->assertCreated();

        //check the db that user exist
        $this->assertDatabaseHas('users', [
            'email' => $email,
            'role' => 'user'
        ]);
    }

    public function test_a_user_can_login_successfully(): void
    {
        //create passport client
        $clientRepository = new ClientRepository();

        /** @var \Laravel\Passport\Client */
        $client = $clientRepository->createPersonalAccessClient(
            1,
            'Test Personal Access Client',
            config('app.url')
        );

        $user = User::factory()->create();

        $response = $this->postJson(route('user.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'token',
            'profile'
        ]);
    }

    public function test_login_not_successful(): void
    {
        //create passport client
        $clientRepository = new ClientRepository();

        /** @var \Laravel\Passport\Client */
        $client = $clientRepository->createPersonalAccessClient(
            1,
            'Test Personal Access Client',
            config('app.url')
        );


        $user = User::factory()->create();

        $response = $this->postJson(route('user.login'), [
            'email' => $user->email,
            'password' => 'wrong_password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'error',
        ]);
    }


}
