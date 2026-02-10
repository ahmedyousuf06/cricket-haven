<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $orderItems = DB::table('order_items')->whereNotNull('product_variant_id')->get();
        $orderUserMap = DB::table('orders')->pluck('user_id', 'id');

        $groupedByProduct = $orderItems->groupBy('product_variant_id');
        $variantToProduct = DB::table('product_variants')->pluck('product_id', 'id');

        $reviewsByProduct = [];

        foreach ($groupedByProduct as $variantId => $items) {
            $productId = $variantToProduct[$variantId] ?? null;
            if (!$productId) {
                continue;
            }

            $take = rand(1, 2);
            $picked = $items->shuffle()->take($take);

            foreach ($picked as $item) {
                $userId = $orderUserMap[$item->order_id] ?? null;
                if (!$userId) {
                    continue;
                }
                $rating = rand(4, 5);

                DB::table('reviews')->insert([
                    'product_id' => $productId,
                    'product_variant_id' => $variantId,
                    'user_id' => $userId,
                    'order_id' => $item->order_id,
                    'rating' => $rating,
                    'title' => $faker->sentence(3),
                    'comment' => $faker->paragraph(2),
                    'is_approved' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $reviewsByProduct[$productId][] = $rating;
            }
        }

        foreach ($reviewsByProduct as $productId => $ratings) {
            DB::table('product_rating_summary')->updateOrInsert(
                ['product_id' => $productId],
                [
                    'avg_rating' => round(array_sum($ratings) / count($ratings), 2),
                    'review_count' => count($ratings),
                ]
            );
        }
    }
}
