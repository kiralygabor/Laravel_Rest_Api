<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use App\Http\Requests\AuthorRequest;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::all();
        return response()->json([
            'authors' => $authors,
        ]);
    }

    public function store(AuthorRequest $request)
    {
        $author = Author::create($request->all());

        return response()->json([
            'author' => $author,
        ]);
    }

    public function update(AuthorRequest $request, $id)
	{
		$author = Author::findOrFail($id);
		$author->update($request->all());

		return response()->json([
			'author' => $author,
		]);
	}
    
    public function destroy($id)
    {
        $author = Author::findOrFail($id);
        $author->delete();
        return response()->json([
            'message' => 'Author deleted successfully',
            'id' => $id
        ]);
    }
}
