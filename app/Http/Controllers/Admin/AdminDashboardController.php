<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function __invoke()
    {
        //
        $books = 'challenge';

        return view('admin.index', compact('books'));
    }
}
