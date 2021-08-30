<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Review;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        return view('front.user.books.list');
    }

    public function show(Book $book)
    {
        return view('front.book.show', compact('book'));
    }

    public function create()
    {
        return view('front.book.create');
    }

    public function store(Request $request)
    {
        $book = 'challenge';

        return redirect()->route('books.show', $book)->with('success', 'Book created.');
    }

    public function edit(Book $book)
    {
        return view('front.user.books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {

        return redirect()->route('books.show', $book)->with('success', 'Book updated.');
    }

    public function destroy(Book $book)
    {
        return redirect()->route('user.books.list')->with('success', 'Book deleted.');
    }
}
