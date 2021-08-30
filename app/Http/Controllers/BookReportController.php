<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Mail\ReportBook;
use Illuminate\Http\Request;

class BookReportController extends Controller
{
    public function create(Book $book)
    {
        return view('front.book.report.create', compact('book'));
    }

    public function store(Request $request, Book $book)
    {
        return redirect()->route('books.show', $book)->with('success', 'Report sent successfully.');
    }
}
