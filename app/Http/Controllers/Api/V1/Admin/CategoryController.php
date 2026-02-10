<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\CategoryStoreRequest;
use App\Http\Requests\Api\V1\Admin\CategoryUpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return CategoryResource::collection(Category::query()->with('children')->paginate(50));
    }

    public function store(CategoryStoreRequest $request)
    {
        $category = Category::query()->create($request->validated());

        return new CategoryResource($category);
    }

    public function show(Category $category)
    {
        $category->load('children');

        return new CategoryResource($category);
    }

    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $category->update($request->validated());

        return new CategoryResource($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'message' => 'Category deleted.',
        ]);
    }
}
