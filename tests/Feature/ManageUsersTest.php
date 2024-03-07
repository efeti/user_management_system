<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageUsersTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {

        parent::setUp();
    }


    public function test_user_update_name_successfully()
    {

        $user = User::factory()->create();
        Passport::actingAs($user);

        $new_name = "new name";
        $response = $this->patchJson(route('auth.update_profile'), ['name' => $new_name]);
        $response->assertStatus(200);

        $this->assertEquals($new_name, $user->fresh()->name);
    }

    public function test_admin_update_role_successfully()
    {
        $admin = User::factory()->admin()->create();
        $user_to_update = User::factory()->create();

        Passport::actingAs($admin, ['admin']);

        $response = $this->postJson(route('auth.update_role'), [
            'email'=> $user_to_update->email, 
            'role' => 'admin'
        ]);
        
        $response->assertStatus(200);
    }

    public function test_admin_view_users_successfully()
    {
        $admin = User::factory()->admin()->create();
        passport::actingAs($admin, ['admin']);

        $response = $this->getJson(route('auth.view_all_users'));
        $response->assertStatus(200);
    }

    public function test_user_view_users_not_successful()
    {
        $admin = User::factory()->create();
        passport::actingAs($admin, ['user']);

        $response = $this->getJson(route('auth.view_all_users'));
        $response->assertStatus(403);
    }

    public function test_admin_delete_user_successfully()
    {
        $admin = User::factory()->admin()->create();

        $userToDelete = User::factory()->create();

        passport::actingAs($admin, ['admin']);

        $response = $this->deleteJson(route('auth.admin.delete_user'), ['email' => $userToDelete->email])->dump();

        $response->assertStatus(200);

        $this->assertDatabaseMissing('users', ['email' => $userToDelete->email]);
    }

    public function test_user_delete_user_not_successful()
    {
        $user = User::factory()->create();

        $userToDelete = User::factory()->create();

        passport::actingAs($user, ['user']);

        $response = $this->deleteJson(route('auth.admin.delete_user'), ['email' => $userToDelete->email])->dump();

        $response->assertStatus(403);

        $this->assertDatabaseHas('users', ['email' => $userToDelete->email]);
    }
}
