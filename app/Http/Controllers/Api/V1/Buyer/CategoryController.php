<?php

namespace App\Http\Controllers\Api\V1\Buyer;

use App\Http\Controllers\Controller;
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

        if ($status = $request->query('status')) {
            if (Schema::hasColumn('categories', 'status')) {
                $query->where('status', $status);
            }
        }

        $sort = $request->query('sort', 'name_asc');
        $sortMap = [
            'name_asc' => ['name', 'asc'],
            'name_desc' => ['name', 'desc'],
            'newest' => ['created_at', 'desc'],
            'oldest' => ['created_at', 'asc'],
        ];
        [$sortField, $sortDir] = $sortMap[$sort] ?? $sortMap['name_asc'];
        $query->orderBy($sortField, $sortDir);

        return CategoryResource::collection($query->paginate(50));
    }
}
