<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         // Admin user
         User::create([
             'name' => 'Admin',
             'email' => 'admin@admin.com',
             'password' => bcrypt('password'),
             'admin' => true,
             'date_of_birth' => Carbon::today()
         ]);

         User::create([
             'name' => 'User',
             'email' => 'user@example.com',
             'password' => bcrypt('password'),
             'date_of_birth' => Carbon::today()
         ]);

        User::factory()
            ->count(20)
            ->create();
    }
}
