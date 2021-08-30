<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BooksRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function testHomeRoute()
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
    }

    public function testBooksCreateRouteGuestHasNoAccess()
    {
        $response = $this->get(route('books.create'));

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function testBooksCreateRouteAuthenticatedUserHasAccess()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('books.create'));

        $response->assertStatus(200);
    }

    public function testBooksStoreRouteGuestHasNoAccess()
    {
        $response = $this->post(route('books.store'), []);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function testBooksStoreRouteAuthenticatedUserHasAccess()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('books.store'), []);

        $response->assertStatus(302);
        $response->assertRedirect(route('books.show', 'challenge'));
    }

    public function testBooksReportCreateRouteGuestHasNoAccess()
    {
        $response = $this->get(route('books.report.create', 'challenge'));

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function testBooksReportCreateRouteAuthenticatedUserHasAccess()
    {
        $user = User::factory()->create();

        Book::factory()->create(['name' => 'challenge']);

        $response = $this->actingAs($user)->get(route('books.report.create', 'challenge'));

        $response->assertStatus(200);
    }

    public function testBooksReportStoreRouteGuestHasNoAccess()
    {
        $response = $this->post(route('books.report.store', 'challenge'), []);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function testBooksReportStoreRouteAuthenticatedUserHasAccess()
    {
        $user = User::factory()->create();

        $book = Book::factory()->create(['name' => 'challenge']);

        $response = $this->actingAs($user)->post(route('books.report.store', $book), []);

        $response->assertStatus(302);
        $response->assertRedirect(route('books.show', 'challenge'));
    }

    public function testEveryoneCanViewBookShowPage()
    {
        User::factory()->create();
        $book = Book::factory()->create();

        $response = $this->get(route('books.show', $book->slug));

        $response->assertStatus(200);
    }
}
