<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\BulkPricingRuleStoreRequest;
use App\Http\Requests\Api\V1\Admin\BulkPricingRuleUpdateRequest;
use App\Http\Resources\BulkPricingRuleResource;
use App\Models\BulkPricingRule;
use Illuminate\Http\Request;

class BulkPricingRuleController extends Controller
{
    public function index(Request $request)
    {
        $query = BulkPricingRule::query();

        if ($productVariantId = $request->query('product_variant_id')) {
            $query->where('product_variant_id', $productVariantId);
        }

        if ($minQty = $request->query('min_qty')) {
            $query->where('min_qty', '>=', $minQty);
        }

        return BulkPricingRuleResource::collection($query->paginate(30));
    }

    public function store(BulkPricingRuleStoreRequest $request)
    {
        $rule = BulkPricingRule::query()->create($request->validated());

        return new BulkPricingRuleResource($rule);
    }

    public function show(BulkPricingRule $bulkPricingRule)
    {
        return new BulkPricingRuleResource($bulkPricingRule);
    }

    public function update(BulkPricingRuleUpdateRequest $request, BulkPricingRule $bulkPricingRule)
    {
        $bulkPricingRule->update($request->validated());

        return new BulkPricingRuleResource($bulkPricingRule);
    }

    public function destroy(BulkPricingRule $bulkPricingRule)
    {
        $bulkPricingRule->delete();

        return response()->json([
            'message' => 'Bulk pricing rule deleted.',
        ]);
    }
}
