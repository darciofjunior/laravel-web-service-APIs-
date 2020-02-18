<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\StoreUpdateCategoryFormUpdate;

class CategoryController extends Controller
{
    private $category, $totalPage = 10;

    public function __construct(Category $category) {
        $this->category = $category;
    }

    public function index(Request $request) {
        $categories = $this->category->getResults($request->name);

        return response()->json($categories);
    }

    public function show($id) {
        if(!$category = $this->category->find($id))
            return response()->json(['error' => 'Not found'], 404);

        return response()->json($category);
    }

    public function store(StoreUpdateCategoryFormUpdate $request) {
        $category = $this->category->create($request->all());
        return response()->json($category, 201);
    }

    public function update(StoreUpdateCategoryFormUpdate $request, $id) {
        if(!$category = $this->category->find($id))
            return response()->json(['error' => 'Not found'], 404);

        $category->update($request->all());

        return response()->json($category);
    }

    public function destroy($id) {
        if(!$category = $this->category->find($id))
            return response()->json(['error' => 'Not found'], 404);

        $category->delete();

        return response()->json(['success' => true], 204);
    }

    public function products($id) {
        /*if(!$category = $this->category->with(['products'])->find($id))
            return response()->json(['error' => 'Not found'], 404);

        $products = $category->products;*/

        if(!$category = $this->category->find($id))
            return response()->json(['error' => 'Not found'], 404);

        $products = $category->products()->paginate($this->totalPage);

        return response()->json([
            'category' => $category,
            'products' => $products,
        ]);
    }
}