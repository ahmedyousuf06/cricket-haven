<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\ProductBundleStoreRequest;
use App\Http\Requests\Api\V1\Admin\ProductBundleUpdateRequest;
use App\Http\Resources\ProductBundleResource;
use App\Models\ProductBundle;
use Illuminate\Support\Facades\DB;

class ProductBundleController extends Controller
{
    public function index()
    {
        $bundles = ProductBundle::query()->with(['items', 'images'])->paginate(20);

        return ProductBundleResource::collection($bundles);
    }

    public function store(ProductBundleStoreRequest $request)
    {
        $bundle = DB::transaction(function () use ($request) {
            $bundle = ProductBundle::query()->create($request->only([
                'name',
                'slug',
                'description',
                'price',
                'status',
            ]));

            foreach ($request->input('items', []) as $item) {
                $bundle->items()->create([
                    'product_variant_id' => $item['product_variant_id'],
                    'qty' => $item['qty'],
                ]);
            }

            foreach ($request->input('images', []) as $image) {
                $bundle->images()->create([
                    'path' => $image['path'],
                    'sort_order' => $image['sort_order'] ?? 0,
                ]);
            }

            return $bundle;
        });

        $bundle->load(['items', 'images']);

        return new ProductBundleResource($bundle);
    }

    public function show(ProductBundle $bundle)
    {
        $bundle->load(['items', 'images']);

        return new ProductBundleResource($bundle);
    }

    public function update(ProductBundleUpdateRequest $request, ProductBundle $bundle)
    {
        $bundle = DB::transaction(function () use ($request, $bundle) {
            $bundle->update($request->only([
                'name',
                'slug',
                'description',
                'price',
                'status',
            ]));

            if ($request->has('items')) {
                $bundle->items()->delete();
                foreach ($request->input('items', []) as $item) {
                    $bundle->items()->create([
                        'product_variant_id' => $item['product_variant_id'],
                        'qty' => $item['qty'],
                    ]);
                }
            }

            if ($request->has('images')) {
                $bundle->images()->delete();
                foreach ($request->input('images', []) as $image) {
                    $bundle->images()->create([
                        'path' => $image['path'],
                        'sort_order' => $image['sort_order'] ?? 0,
                    ]);
                }
            }

            return $bundle;
        });

        $bundle->load(['items', 'images']);

        return new ProductBundleResource($bundle);
    }

    public function destroy(ProductBundle $bundle)
    {
        $bundle->delete();

        return response()->json([
            'message' => 'Bundle deleted.',
        ]);
    }
}
