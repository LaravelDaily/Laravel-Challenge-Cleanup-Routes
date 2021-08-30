<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->paragraph(),
            'price' => rand(1000, 9999),
            'discount' => rand(0, 20),
            'user_id' => User::inRandomOrder()->first(),
            'approved' => rand(true, false),
            'created_at' => $this->faker->dateTimeBetween('-1 months', 'now')
        ];
    }

    /*public function configure()
    {
        return $this->afterCreating(function (Book $book) {
            $path = storage_path('app/blank_cover.png');

            $book->addMedia($path)->preservingOriginal()->toMediaCollection('covers');
        });
    }*/
}
