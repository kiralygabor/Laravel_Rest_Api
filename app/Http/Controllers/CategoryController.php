<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    /**
     * @api {get} /categories Kategóriák listázása
     * @apiName GetCategories
     * @apiGroup Category
     * @apiVersion 1.0.0
     *
     * @apiSuccess {Object[]} categories A kategóriák listája.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "categories": [
     *     {
     *       "id": 1,
     *       "name": "Regény",
     *       "created_at": "2025-10-14T12:00:00Z",
     *       "updated_at": "2025-10-14T12:00:00Z"
     *     }
     *   ]
     * }
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json([
            'categories' => $categories,
        ]);
    }

    /**
     * @api {post} /categories Új kategória létrehozása
     * @apiName CreateCategory
     * @apiGroup Category
     * @apiVersion 1.0.0
     *
     * @apiBody {String} name A kategória neve.
     *
     * @apiSuccess {Object} category A létrehozott kategória adatai.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "category": {
     *     "id": 2,
     *     "name": "Sci-fi",
     *     "created_at": "2025-10-14T12:30:00Z",
     *     "updated_at": "2025-10-14T12:30:00Z"
     *   }
     * }
     *
     * @apiError (422) ValidationError Hibás vagy hiányzó mezők.
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->all());

        return response()->json([
            'category' => $category,
        ]);
    }

    /**
     * @api {put} /categories/:id Kategória frissítése
     * @apiName UpdateCategory
     * @apiGroup Category
     * @apiVersion 1.0.0
     *
     * @apiParam {Number} id A kategória azonosítója.
     * @apiBody {String} name A kategória új neve.
     *
     * @apiSuccess {Object} category A frissített kategória adatai.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "category": {
     *     "id": 2,
     *     "name": "Történelmi",
     *     "created_at": "2025-10-14T12:00:00Z",
     *     "updated_at": "2025-10-14T13:00:00Z"
     *   }
     * }
     *
     * @apiError (404) NotFound A kategória nem található.
     */
    public function update(CategoryRequest $request, $id)
	{
		$category = Category::findOrFail($id);
		$category->update($request->all());

		return response()->json([
			'category' => $category,
		]);
	}

    /**
     * @api {delete} /categories/:id Kategória törlése
     * @apiName DeleteCategory
     * @apiGroup Category
     * @apiVersion 1.0.0
     *
     * @apiParam {Number} id A törlendő kategória azonosítója.
     *
     * @apiSuccess {String} message Törlés visszaigazolása.
     * @apiSuccess {Number} id A törölt kategória azonosítója.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "message": "Category deleted successfully",
     *   "id": 2
     * }
     *
     * @apiError (404) NotFound A kategória nem található.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json([
            'message' => 'Category deleted successfully',
            'id' => $id
        ]);
    }
}
