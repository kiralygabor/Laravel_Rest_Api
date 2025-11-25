<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Http\Requests\BookRequest;

class BookController extends Controller
{
    /**
     * @api {get} /books Könyvek listázása
     * @apiName GetBooks
     * @apiGroup Book
     * @apiVersion 1.0.0
     *
     * @apiSuccess {Object[]} books A könyvek listája.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "books": [
     *     {
     *       "id": 1,
     *       "title": "Egri csillagok",
     *       "author_id": 1,
     *       "created_at": "2025-10-14T12:00:00Z",
     *       "updated_at": "2025-10-14T12:00:00Z"
     *     }
     *   ]
     * }
     */
    public function index()
    {
        $books = Book::all();
        return response()->json([
            'books' => $books,
        ]);
    }

    /**
     * @api {post} /books Új könyv létrehozása
     * @apiName CreateBook
     * @apiGroup Book
     * @apiVersion 1.0.0
     *
     * @apiBody {String} title A könyv címe.
     * @apiBody {Number} author_id A szerző azonosítója.
     *
     * @apiSuccess {Object} book A létrehozott könyv adatai.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "book": {
     *     "id": 2,
     *     "title": "Tüskevár",
     *     "author_id": 3,
     *     "created_at": "2025-10-14T12:30:00Z",
     *     "updated_at": "2025-10-14T12:30:00Z"
     *   }
     * }
     *
     * @apiError (422) ValidationError Hibás vagy hiányzó mezők.
     */
    public function store(BookRequest $request)
    {
        $book = Book::create($request->all());

        return response()->json([
            'book' => $book,
        ]);
    }

    /**
     * @api {put} /books/:id Könyv frissítése
     * @apiName UpdateBook
     * @apiGroup Book
     * @apiVersion 1.0.0
     *
     * @apiParam {Number} id A könyv egyedi azonosítója.
     * @apiBody {String} title A könyv címe.
     * @apiBody {Number} author_id A szerző azonosítója.
     *
     * @apiSuccess {Object} book A frissített könyv adatai.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "book": {
     *     "id": 2,
     *     "title": "Légy jó mindhalálig",
     *     "author_id": 4,
     *     "created_at": "2025-10-14T12:00:00Z",
     *     "updated_at": "2025-10-14T13:00:00Z"
     *   }
     * }
     *
     * @apiError (404) NotFound A könyv nem található.
     */
    public function update(BookRequest $request, $id)
	{
		$book = Book::findOrFail($id);
		$book->update($request->all());

		return response()->json([
			'book' => $book,
		]);
	}

    /**
     * @api {delete} /books/:id Könyv törlése
     * @apiName DeleteBook
     * @apiGroup Book
     * @apiVersion 1.0.0
     *
     * @apiParam {Number} id A törlendő könyv azonosítója.
     *
     * @apiSuccess {String} message Törlés visszaigazolása.
     * @apiSuccess {Number} id A törölt könyv azonosítója.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "message": "Book deleted successfully",
     *   "id": 2
     * }
     *
     * @apiError (404) NotFound A könyv nem található.
     */
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
