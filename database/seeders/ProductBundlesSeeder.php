<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductBundlesSeeder extends Seeder
{
    public function run(): void
    {
        $bundles = [
            [
                'name' => 'Beginner Kit',
                'description' => 'Starter set with bat, pads, gloves, and ball.',
                'price' => 199.00,
                'status' => 'active',
            ],
            [
                'name' => 'Pro Starter Pack',
                'description' => 'Pro-level gear bundle for serious players.',
                'price' => 349.00,
                'status' => 'active',
            ],
            [
                'name' => 'Wicketkeeper Set',
                'description' => 'Keeper pads, gloves, and helmet bundle.',
                'price' => 259.00,
                'status' => 'active',
            ],
        ];

        $bundleIds = [];
        foreach ($bundles as $bundle) {
            $bundleIds[] = DB::table('product_bundles')->insertGetId([
                'name' => $bundle['name'],
                'slug' => Str::slug($bundle['name']),
                'description' => $bundle['description'],
                'price' => $bundle['price'],
                'status' => $bundle['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $variantIds = DB::table('product_variants')->pluck('id')->all();
        foreach ($bundleIds as $bundleId) {
            $items = collect($variantIds)->shuffle()->take(4);
            foreach ($items as $variantId) {
                DB::table('product_bundle_items')->insert([
                    'bundle_id' => $bundleId,
                    'product_variant_id' => $variantId,
                    'qty' => rand(1, 2),
                ]);
            }

            DB::table('bundle_images')->insert([
                'bundle_id' => $bundleId,
                'path' => 'bundles/' . $bundleId . '/1.jpg',
                'sort_order' => 1,
            ]);
        }
    }
}
