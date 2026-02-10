<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BulkPricingRulesSeeder extends Seeder
{
    public function run(): void
    {
        $variants = DB::table('product_variants')->select('id', 'price')->inRandomOrder()->limit(10)->get();

        foreach ($variants as $variant) {
            DB::table('bulk_pricing_rules')->insert([
                [
                    'product_variant_id' => $variant->id,
                    'min_qty' => 5,
                    'price_per_unit' => round($variant->price * 0.90, 2),
                ],
                [
                    'product_variant_id' => $variant->id,
                    'min_qty' => 10,
                    'price_per_unit' => round($variant->price * 0.80, 2),
                ],
            ]);
        }
    }
}
