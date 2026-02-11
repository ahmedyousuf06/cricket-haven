<?php

namespace App\Http\Controllers\Api\V1\Buyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Buyer\ReviewStoreRequest;
use App\Http\Requests\Api\V1\Buyer\ReviewUpdateRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $query = Review::query()
            ->where('user_id', request()->user()->id)
            ->with(['product', 'productVariant', 'user', 'order', 'images']);

        if ($rating = request()->query('rating')) {
            $query->where('rating', $rating);
        }

        if (request()->has('is_approved')) {
            $query->where('is_approved', request()->boolean('is_approved'));
        }

        $sort = request()->query('sort', 'newest');
        $sortMap = [
            'newest' => ['created_at', 'desc'],
            'oldest' => ['created_at', 'asc'],
            'rating_asc' => ['rating', 'asc'],
            'rating_desc' => ['rating', 'desc'],
        ];
        [$sortField, $sortDir] = $sortMap[$sort] ?? $sortMap['newest'];
        $query->orderBy($sortField, $sortDir);

        $reviews = $query->paginate(15);

        return ReviewResource::collection($reviews);
    }

    public function store(ReviewStoreRequest $request)
    {
        $review = Review::query()->create([
            'product_id' => $request->input('product_id'),
            'product_variant_id' => $request->input('product_variant_id'),
            'user_id' => $request->user()->id,
            'order_id' => $request->input('order_id'),
            'rating' => $request->input('rating'),
            'title' => $request->input('title'),
            'comment' => $request->input('comment'),
            'is_approved' => false,
        ]);

        $review->load(['product', 'productVariant', 'user', 'order', 'images']);

        return new ReviewResource($review);
    }

    public function update(ReviewUpdateRequest $request, Review $review)
    {
        $this->authorizeReview($review);

        $review->update([
            'rating' => $request->input('rating'),
            'title' => $request->input('title'),
            'comment' => $request->input('comment'),
        ]);

        $review->load(['product', 'productVariant', 'user', 'order', 'images']);

        return new ReviewResource($review);
    }

    public function destroy(Review $review)
    {
        $this->authorizeReview($review);
        $review->delete();

        return response()->json([
            'message' => 'Review deleted.',
        ]);
    }

    private function authorizeReview(Review $review): void
    {
        if ((int) $review->user_id !== (int) request()->user()->id) {
            abort(403, 'Forbidden');
        }
    }
}
