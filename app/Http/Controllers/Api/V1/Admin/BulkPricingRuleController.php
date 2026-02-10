<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\BulkPricingRuleStoreRequest;
use App\Http\Requests\Api\V1\Admin\BulkPricingRuleUpdateRequest;
use App\Http\Resources\BulkPricingRuleResource;
use App\Models\BulkPricingRule;

class BulkPricingRuleController extends Controller
{
    public function index()
    {
        return BulkPricingRuleResource::collection(BulkPricingRule::query()->paginate(30));
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
