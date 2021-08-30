<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserSettingsRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestHasNoAccessToUsersSettingsPage()
    {
        $response = $this->get(route('user.settings'));

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function testAuthenticatedUserHasAccessToUsersSettingsPage()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('user.settings'));

        $response->assertStatus(200);
    }

    public function testGuestHasNoAccessToUsersSettingsUpdate()
    {
        $user = User::factory()->create();

        $response = $this->post(route('user.settings.update', $user), []);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function testAuthenticatedUserHasAccessToUsersSettingsUpdate()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('user.settings.update', $user), []);

        $response->assertStatus(302);
        $response->assertRedirect(route('user.settings'));
    }

    public function testGuestHasNoAccessToUsersPasswordChange()
    {
        $user = User::factory()->create();

        $response = $this->post(route('user.password.update', $user), []);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function testAuthenticatedUserHasAccessToUsersPasswordChange()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('user.password.update', $user), []);

        $response->assertStatus(302);
        $response->assertRedirect(route('user.settings'));
    }
}
