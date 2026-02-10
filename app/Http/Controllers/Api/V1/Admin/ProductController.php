<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\ProductStoreRequest;
use App\Http\Requests\Api\V1\Admin\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::query()
            ->with(['variants.attributes', 'images', 'ratingSummary'])
            ->latest()
            ->paginate(15);

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
