<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\VendorStoreRequest;
use App\Http\Requests\Api\V1\Admin\VendorUpdateRequest;
use App\Http\Resources\VendorResource;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $query = Vendor::query();

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('contact_email', 'like', "%{$search}%");
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        return VendorResource::collection($query->paginate(50));
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
