<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartsSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')->where('email', '!=', 'admin@example.com')->pluck('id')->all();
        $variantIds = DB::table('product_variants')->pluck('id')->all();
        $bundleIds = DB::table('product_bundles')->pluck('id')->all();

        $cartUsers = collect($users)->shuffle()->take(6);
        foreach ($cartUsers as $userId) {
            $cartId = DB::table('carts')->insertGetId([
                'user_id' => $userId,
                'session_id' => (string) Str::uuid(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $itemsCount = rand(1, 3);
            $used = [];
            for ($i = 0; $i < $itemsCount; $i++) {
                $variantId = $variantIds[array_rand($variantIds)];
                $bundleId = rand(1, 10) > 8 ? $bundleIds[array_rand($bundleIds)] : null;
                $key = $variantId . ':' . ($bundleId ?? 'none');
                if (isset($used[$key])) {
                    continue;
                }
                $used[$key] = true;
                $price = DB::table('product_variants')->where('id', $variantId)->value('price');

                DB::table('cart_items')->insert([
                    'cart_id' => $cartId,
                    'product_variant_id' => $variantId,
                    'bundle_id' => $bundleId,
                    'qty' => rand(1, 2),
                    'unit_price' => $price,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
