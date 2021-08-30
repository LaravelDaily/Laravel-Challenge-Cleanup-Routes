<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Book $book)
    {
        //

        $book = 'challenge';

        return redirect()->route('books.show', $book);
    }
}
