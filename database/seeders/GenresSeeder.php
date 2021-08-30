<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Genre;
use Illuminate\Database\Seeder;

class GenresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genres = ['Comedy', 'Horror', 'Fantasy', 'Thriller'];

        foreach ($genres as $genre) {
            Genre::create([
                'name' => $genre
            ]);
        }
    }
}
