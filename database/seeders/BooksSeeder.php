<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Review;
use App\Models\User;
use Database\Factories\ReviewFactory;
use Illuminate\Database\Seeder;

class BooksSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genres = Genre::all()->pluck('id');
        $authors = Author::all()->pluck('id');
        $users = User::all();

        Book::factory(55)->create()->each(
            function ($book) use ($genres, $authors, $users) {
                $book->genres()->attach($genres->random(rand(1, 2)));
                $book->authors()->attach($authors->random(rand(1, 2)));

                for ($i=1; $i <= rand(1, 15); $i++) {
                    if ($book->approved()) {
                        Review::factory([
                            'book_id' => $book->id,
                            'user_id' => $users->random(1)->first()->id
                        ])->create();
                    }
                }
            }
        );
    }

}
