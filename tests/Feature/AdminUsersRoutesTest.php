<?php

namespace Tests\Feature;

use Tests\TestCase;
Use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminUsersRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function testUserAndGuestCannotViewAllUsersListPage()
    {
        $response = $this->get(route('admin.users.index'));

        $response->assertStatus(403);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.users.index'));

        $response->assertStatus(403);
    }

    public function testAdminCanViewAllUsersListPage()
    {
        $user = User::factory()->create(['admin' => true]);

        $response = $this->actingAs($user)->get(route('admin.users.index'));

        $response->assertStatus(200);
    }

    public function testUserAndGuestCannotViewUpdateUserPage()
    {
        $user = User::factory()->create();

        $response = $this->get(route('admin.users.edit', $user));

        $response->assertStatus(403);

        $response = $this->actingAs($user)->get(route('admin.users.edit', $user));

        $response->assertStatus(403);
    }

    public function testAdminCanViewUpdateUserPage()
    {
        $adminUser = User::factory()->create(['admin' => true]);
        $user = User::factory()->create();

        $response = $this->actingAs($adminUser)->get(route('admin.users.edit', $user));

        $response->assertStatus(200);
    }

    public function testUserAndGuestCannotAccessUserUpdateRoute()
    {
        $user = User::factory()->create();

        $response = $this->put(route('admin.users.update', $user));

        $response->assertStatus(403);

        $response = $this->actingAs($user)->put(route('admin.users.update', $user));

        $response->assertStatus(403);
    }

    public function testAdminCanAccessUserUpdateRoute()
    {
        $adminUser = User::factory()->create(['admin' => true]);
        $user = User::factory()->create();

        $response = $this->actingAs($adminUser)->put(route('admin.users.update', $user));

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.users.index'));
    }

    public function testUserAndGuestCannotAccessUserDestroyRoute()
    {
        $user = User::factory()->create();

        $response = $this->put(route('admin.users.destroy', $user));

        $response->assertStatus(403);

        $response = $this->actingAs($user)->put(route('admin.users.destroy', $user));

        $response->assertStatus(403);
    }

    public function testAdminCanAccessUserDestroyRoute()
    {
        $adminUser = User::factory()->create(['admin' => true]);
        $user = User::factory()->create();

        $response = $this->actingAs($adminUser)->put(route('admin.users.destroy', $user));

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.users.index'));
    }
}
