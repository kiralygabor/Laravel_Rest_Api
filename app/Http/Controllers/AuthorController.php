<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use App\Http\Requests\AuthorRequest;

class AuthorController extends Controller
{
    /**
     * @api {get} /authors Lista lekérdezése
     * @apiName GetAuthors
     * @apiGroup Author
     * @apiVersion 1.0.0
     *
     * @apiSuccess {Object[]} authors Lista az összes szerzőről.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "authors": [
     *     {
     *       "id": 1,
     *       "name": "Jókai Mór",
     *       "created_at": "2025-10-14T12:00:00Z",
     *       "updated_at": "2025-10-14T12:00:00Z"
     *     }
     *   ]
     * }
     */
    public function index()
    {
        $authors = Author::all();
        return response()->json([
            'authors' => $authors,
        ]);
    }

    /**
     * @api {get} /authors/:id/books Szerző könyveinek lekérdezése
     * @apiName GetAuthorBooks
     * @apiGroup Author
     * @apiVersion 1.0.0
     *
     * @apiParam {Number} id A szerző egyedi azonosítója.
     *
     * @apiSuccess {Object} author A szerző adatai.
     * @apiSuccess {Object[]} books A szerző könyveinek listája.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "author": {
     *     "id": 1,
     *     "name": "Jókai Mór"
     *   },
     *   "books": [
     *     {
     *       "id": 5,
     *       "title": "Az arany ember",
     *       "published_at": "1872-01-01",
     *       "created_at": "2025-10-14T12:00:00Z",
     *       "updated_at": "2025-10-14T12:00:00Z"
     *     }
     *   ]
     * }
     *
     * @apiError (404) NotFound A szerző nem található.
     */
    public function books($id)
    {
        $author = Author::findOrFail($id);
        $books = $author->books;
 
        return response()->json([
            'author' => [
                'id' => $author->id,
                'name' => $author->name,
            ],
            'books' => $books,
        ]);
    }

    /**
     * @api {delete} /authors/:id/books/:book_id Egy szerző könyvének törlése
     * @apiName DeleteAuthorBook
     * @apiGroup Author
     * @apiVersion 1.0.0
     *
     * @apiParam {Number} id A szerző egyedi azonosítója.
     * @apiParam {Number} book_id A törlendő könyv azonosítója.
     *
     * @apiSuccess {String} message Törlés visszaigazolása.
     * @apiSuccess {Number} book_id A törölt könyv azonosítója.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "message": "Book deleted successfully",
     *   "book_id": 5
     * }
     *
     * @apiError (404) NotFound A könyv nem található a megadott szerzőnél.
     */
    public function deleteBook($id, $book_id)
    {
        $author = Author::findOrFail($id);
        $book = $author->books()->where('id', $book_id)->first();
 
        if (!$book) {
            return response()->json([
                'error' => 'Book not found for this author',
            ], 404);
        }
 
        $book->delete();
 
        return response()->json([
            'message' => 'Book deleted successfully',
            'book_id' => $book_id,
        ]);
    }

    /**
     * @api {post} /authors Új szerző létrehozása
     * @apiName CreateAuthor
     * @apiGroup Author
     * @apiVersion 1.0.0
     *
     * @apiBody {String} name A szerző neve.
     *
     * @apiSuccess {Object} author A létrehozott szerző adatai.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 201 Created
     * {
     *   "author": {
     *     "id": 2,
     *     "name": "Petőfi Sándor",
     *     "created_at": "2025-10-14T12:30:00Z",
     *     "updated_at": "2025-10-14T12:30:00Z"
     *   }
     * }
     *
     * @apiError (422) ValidationError Hibás vagy hiányzó mezők.
     */
    public function store(AuthorRequest $request)
    {
        $author = Author::create($request->all());

        return response()->json([
            'author' => $author,
        ], 201);
    }

    /**
     * @api {put} /authors/:id Szerző frissítése
     * @apiName UpdateAuthor
     * @apiGroup Author
     * @apiVersion 1.0.0
     *
     * @apiParam {Number} id A szerző egyedi azonosítója.
     * @apiBody {String} name A szerző frissített neve.
     *
     * @apiSuccess {Object} author A frissített szerző adatai.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "author": {
     *     "id": 2,
     *     "name": "Arany János",
     *     "created_at": "2025-10-14T12:30:00Z",
     *     "updated_at": "2025-10-14T13:00:00Z"
     *   }
     * }
     *
     * @apiError (404) NotFound A szerző nem található.
     */
    public function update(AuthorRequest $request, $id)
	{
		$author = Author::findOrFail($id);
		$author->update($request->all());

		return response()->json([
			'author' => $author,
		]);
	}

    /**
     * @api {delete} /authors/:id Szerző törlése
     * @apiName DeleteAuthor
     * @apiGroup Author
     * @apiVersion 1.0.0
     *
     * @apiParam {Number} id A törlendő szerző azonosítója.
     *
     * @apiSuccess {String} message Törlés visszaigazolása.
     * @apiSuccess {Number} id A törölt szerző azonosítója.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "message": "Author deleted successfully",
     *   "id": 2
     * }
     *
     * @apiError (404) NotFound A szerző nem található.
     */
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
