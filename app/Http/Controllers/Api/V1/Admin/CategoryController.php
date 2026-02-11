<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\CategoryStoreRequest;
use App\Http\Requests\Api\V1\Admin\CategoryUpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query()->with(['parent', 'children']);

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($parentId = $request->query('parent_id')) {
            $query->where('parent_id', $parentId);
        }

        if ($status = $request->query('status')) {
            if (Schema::hasColumn('categories', 'status')) {
                $query->where('status', $status);
            }
        }

        return CategoryResource::collection($query->paginate(50));
    }

    public function store(CategoryStoreRequest $request)
    {
        $category = Category::query()->create($request->validated());

        return new CategoryResource($category);
    }

    public function show(Category $category)
    {
        $category->load(['parent', 'children']);

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
