<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserBooksRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function testUserBooksListPageGuestsCannotSee()
    {
        $response = $this->get(route('user.books.index'));

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function testUserBooksListPageAuthenticatedUsersCanSee()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('user.books.index'));

        $response->assertStatus(200);
    }

    public function testUserBooksEditPageGuestsCannotSee()
    {
        $response = $this->get(route('user.books.edit', 'challenge'));

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function testUserBooksEditPageAuthenticatedUsersCanSee()
    {
        $user = User::factory()->create();

        $book = Book::factory()->create();

        $response = $this->actingAs($user)->get(route('user.books.edit', $book->slug));

        $response->assertStatus(200);
    }

    public function testUserBooksUpdateMethodGuestsCannotSee()
    {
        $response = $this->put(route('user.books.update', 'challenge'));

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function testUserBooksUpdateMethodAuthenticatedUsersCanSee()
    {
        $user = User::factory()->create();

        $book = Book::factory()->create();

        $response = $this->actingAs($user)->put(route('user.books.update', $book->slug), []);

        $response->assertStatus(302);
        $response->assertRedirect(route('books.show', $book));
    }

    public function testGuestCannotDeleteBook()
    {
        $response = $this->delete(route('user.books.destroy', 'challenge'));

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function testAuthenticatedUsersCanDeleteBook()
    {
        $user = User::factory()->create();

        $book = Book::factory()->create();

        $response = $this->actingAs($user)->delete(route('user.books.destroy', $book));

        $response->assertStatus(302);
        $response->assertRedirect(route('user.books.index'));
    }
}
