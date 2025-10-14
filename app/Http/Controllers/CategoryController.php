<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json([
            'categories' => $categories,
        ]);
    }

    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->all());

        return response()->json([
            'category' => $category,
        ]);
    }

    public function update(CategoryRequest $request, $id)
	{
		$category = Category::findOrFail($id);
		$category->update($request->all());

		return response()->json([
			'category' => $category,
		]);
	}
    
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
