<?php

namespace App\Http\Controllers\Admin;

use App\Models\Book;
use App\Models\Genre;
use App\Models\Author;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminBookController extends Controller
{
    public function index()
    {
        return view('admin.books.index', [
            'books' => Book::latest()->with('authors', 'genres')->paginate()
        ]);
    }

    public function create()
    {
        //

        return view('admin.books.create');
    }

    public function store(Request $request)
    {
        //

        return redirect()->route('admin.books.index')->with('success', 'Book created.');
    }

    public function edit(Book $book)
    {
        //

        return view('admin.books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        //

        return redirect()->route('admin.books.index')->with('success', 'Book updated.');
    }

    public function destroy(Book $book)
    {
        //

        return redirect()->route('admin.books.index')->with('success', 'Book deleted.');
    }

    public function approveBook(Book $book)
    {
        //

        return redirect()->route('admin.books.index')->with('success', 'Book approved.');
    }
}
