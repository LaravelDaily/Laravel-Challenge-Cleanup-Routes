<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminBooksRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function testUserAndGuestCannotAccessAdminDashboardPage()
    {
        $response = $this->get(route('admin.index'));

        $response->assertStatus(403);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.index'));

        $response->assertStatus(403);
    }

    public function testAdminUserCanAccessAdminDashboardPage()
    {
        $user = User::factory()->create(['admin' => true]);

        $response = $this->actingAs($user)->get(route('admin.index'));

        $response->assertStatus(200);
    }

    public function testUserAndGuestCannotViewAllBooksListPage()
    {
        $response = $this->get(route('admin.books.index'));

        $response->assertStatus(403);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.books.index'));

        $response->assertStatus(403);
    }

    public function testAdminCanViewAllBooksListPage()
    {
        $user = User::factory()->create(['admin' => true]);

        $response = $this->actingAs($user)->get(route('admin.books.index'));

        $response->assertStatus(200);
    }

    public function testUserAndGuestCannotViewCreateBooksPage()
    {
        $response = $this->get(route('admin.books.create'));

        $response->assertStatus(403);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.books.create'));

        $response->assertStatus(403);
    }

    public function testAdminCanViewCreateBooksPage()
    {
        $user = User::factory()->create(['admin' => true]);

        $response = $this->actingAs($user)->get(route('admin.books.create'));

        $response->assertStatus(200);
    }

    public function testUserAndGuestCannotStoreNewBook()
    {
        $response = $this->post(route('admin.books.store'), []);

        $response->assertStatus(403);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.books.store'), []);

        $response->assertStatus(403);
    }

    public function testAdminCanStoreNewBook()
    {
        $user = User::factory()->create(['admin' => true]);

        $response = $this->actingAs($user)->post(route('admin.books.store'), []);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.books.index'));
    }

    public function testUserAndGuestCannotViewUpdateBooksPage()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $response = $this->get(route('admin.books.edit', $book));

        $response->assertStatus(403);

        $response = $this->actingAs($user)->get(route('admin.books.edit', $book));

        $response->assertStatus(403);
    }

    public function testAdminCanViewUpdateBooksPage()
    {
        $user = User::factory()->create(['admin' => true]);

        $book = Book::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.books.edit', $book));

        $response->assertStatus(200);
    }

    public function testUserAndGuestCannotAccessBookUpdateRoute()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $response = $this->put(route('admin.books.update', $book));

        $response->assertStatus(403);

        $response = $this->actingAs($user)->put(route('admin.books.update', $book));

        $response->assertStatus(403);
    }

    public function testAdminCanAccessBookUpdateRoute()
    {
        $user = User::factory()->create(['admin' => true]);

        $book = Book::factory()->create();

        $response = $this->actingAs($user)->put(route('admin.books.update', $book));

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.books.index'));
    }

    public function testUserAndGuestCannotAccessBookDestroyRoute()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $response = $this->put(route('admin.books.destroy', $book));

        $response->assertStatus(403);

        $response = $this->actingAs($user)->put(route('admin.books.destroy', $book));

        $response->assertStatus(403);
    }

    public function testAdminCanAccessBookDestroyRoute()
    {
        $user = User::factory()->create(['admin' => true]);

        $book = Book::factory()->create();

        $response = $this->actingAs($user)->put(route('admin.books.destroy', $book));

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.books.index'));
    }

    public function testUserAndGuestCannotAccessBookApproveRoute()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $response = $this->put(route('admin.books.approve', $book));

        $response->assertStatus(403);

        $response = $this->actingAs($user)->put(route('admin.books.approve', $book));

        $response->assertStatus(403);
    }

    public function testAdminCanAccessBookApproveRoute()
    {
        $user = User::factory()->create(['admin' => true]);

        $book = Book::factory()->create();

        $response = $this->actingAs($user)->put(route('admin.books.approve', $book));

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.books.index'));
    }
}
