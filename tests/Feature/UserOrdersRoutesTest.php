<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserOrdersRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestHasNoAccessToUsersOrdersList()
    {
        $response = $this->get(route('user.orders.index'));

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function testAuthenticatedUserHasAccessToUsersOrdersList()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('user.orders.index'));

        $response->assertStatus(200);
    }
}
