<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\VendorStoreRequest;
use App\Http\Requests\Api\V1\Admin\VendorUpdateRequest;
use App\Http\Resources\VendorResource;
use App\Models\Vendor;

class VendorController extends Controller
{
    public function index()
    {
        return VendorResource::collection(Vendor::query()->paginate(50));
    }

    public function store(VendorStoreRequest $request)
    {
        $vendor = Vendor::query()->create($request->validated());

        return new VendorResource($vendor);
    }

    public function show(Vendor $vendor)
    {
        return new VendorResource($vendor);
    }

    public function update(VendorUpdateRequest $request, Vendor $vendor)
    {
        $vendor->update($request->validated());

        return new VendorResource($vendor);
    }

    public function destroy(Vendor $vendor)
    {
        $vendor->delete();

        return response()->json([
            'message' => 'Vendor deleted.',
        ]);
    }
}
