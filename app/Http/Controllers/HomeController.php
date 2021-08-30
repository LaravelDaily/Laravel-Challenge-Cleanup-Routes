<?php

namespace App\Http\Controllers;

use App\Models\Book;

class HomeController extends Controller
{
    public function __invoke()
    {
        //

        return view('front.home');
    }
}
