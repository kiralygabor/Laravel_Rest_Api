<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Http\Requests\BookRequest;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return response()->json([
            'books' => $books,
        ]);
    }

    public function store(BookRequest $request)
    {
        $book = Book::create($request->all());

        return response()->json([
            'book' => $book,
        ]);
    }

    public function update(BookRequest $request, $id)
	{
		$book = Book::findOrFail($id);
		$book->update($request->all());

		return response()->json([
			'book' => $book,
		]);
	}
    
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();
        return response()->json([
            'message' => 'Book deleted successfully',
            'id' => $id
        ]);
    }
}
