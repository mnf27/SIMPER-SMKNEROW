<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Book;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return view('guru.books.index', compact('books'));
    }
}
