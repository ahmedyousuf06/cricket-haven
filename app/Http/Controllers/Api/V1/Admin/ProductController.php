<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\ProductStoreRequest;
use App\Http\Requests\Api\V1\Admin\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->with(['variants.attributes', 'images', 'ratingSummary']);

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($categoryId = $request->query('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($brandId = $request->query('brand_id')) {
            $query->where('brand_id', $brandId);
        }

        if ($vendorId = $request->query('vendor_id')) {
            $query->where('vendor_id', $vendorId);
        }

        if ($minPrice = $request->query('min_price')) {
            $query->whereHas('variants', function ($q) use ($minPrice) {
                $q->where('price', '>=', $minPrice);
            });
        }

        if ($maxPrice = $request->query('max_price')) {
            $query->whereHas('variants', function ($q) use ($maxPrice) {
                $q->where('price', '<=', $maxPrice);
            });
        }

        if ($request->boolean('has_stock')) {
            $query->whereHas('variants', function ($q) {
                $q->where('stock', '>', 0);
            });
        }

        $sort = $request->query('sort', 'newest');
        $sortMap = [
            'newest' => ['created_at', 'desc'],
            'oldest' => ['created_at', 'asc'],
            'name_asc' => ['name', 'asc'],
            'name_desc' => ['name', 'desc'],
        ];
        [$sortField, $sortDir] = $sortMap[$sort] ?? $sortMap['newest'];
        $query->orderBy($sortField, $sortDir);

        $products = $query->paginate(15);

        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {
        $product->load(['variants.attributes', 'images', 'ratingSummary']);

        return new ProductResource($product);
    }

    public function store(ProductStoreRequest $request)
    {
        $product = DB::transaction(function () use ($request) {
            $product = Product::query()->create($request->only([
                'name',
                'slug',
                'brand_id',
                'category_id',
                'vendor_id',
                'description',
                'short_description',
                'status',
            ]));

            foreach ($request->input('variants', []) as $variantData) {
                $product->variants()->create([
                    'sku' => $variantData['sku'],
                    'price' => $variantData['price'],
                    'compare_at_price' => $variantData['compare_at_price'] ?? null,
                    'stock' => $variantData['stock'],
                    'weight_grams' => $variantData['weight_grams'] ?? null,
                    'status' => $variantData['status'],
                    'carts_count' => 0,
                ]);
            }

            return $product;
        });

        $product->load(['variants.attributes', 'images', 'ratingSummary']);

        return new ProductResource($product);
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        $product = DB::transaction(function () use ($request, $product) {
            $product->update($request->only([
                'name',
                'slug',
                'brand_id',
                'category_id',
                'vendor_id',
                'description',
                'short_description',
                'status',
            ]));

            foreach ($request->input('variants', []) as $variantData) {
                $variant = isset($variantData['id'])
                    ? ProductVariant::query()->where('product_id', $product->id)->find($variantData['id'])
                    : null;

                $payload = [
                    'sku' => $variantData['sku'],
                    'price' => $variantData['price'],
                    'compare_at_price' => $variantData['compare_at_price'] ?? null,
                    'stock' => $variantData['stock'],
                    'weight_grams' => $variantData['weight_grams'] ?? null,
                    'status' => $variantData['status'],
                ];

                if ($variant) {
                    $variant->update($payload);
                } else {
                    $product->variants()->create(array_merge($payload, [
                        'carts_count' => 0,
                    ]));
                }
            }

            return $product;
        });

        $product->load(['variants.attributes', 'images', 'ratingSummary']);

        return new ProductResource($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted.',
        ]);
    }
}
