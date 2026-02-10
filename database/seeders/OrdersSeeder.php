<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersSeeder extends Seeder
{
    public function run(): void
    {
        $buyers = DB::table('users')->where('email', '!=', 'admin@example.com')->pluck('id')->all();
        $variants = DB::table('product_variants')->select('id', 'price', 'product_id', 'sku')->get();
        $bundles = DB::table('product_bundles')->select('id', 'name', 'price')->get();

        $orderCount = 8;
        for ($i = 0; $i < $orderCount; $i++) {
            $userId = $buyers[array_rand($buyers)];
            $status = collect(['pending', 'paid', 'processing', 'shipped', 'delivered'])->random();

            $orderId = DB::table('orders')->insertGetId([
                'user_id' => $userId,
                'subtotal' => 0,
                'tax' => 0,
                'shipping_cost' => 0,
                'total' => 0,
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $itemsCount = rand(2, 4);
            $subtotal = 0;

            for ($j = 0; $j < $itemsCount; $j++) {
                $useBundle = rand(1, 10) > 8 && $bundles->isNotEmpty();

                if ($useBundle) {
                    $bundle = $bundles->random();
                    $qty = rand(1, 2);
                    $price = $bundle->price ?? 199.00;
                    $subtotal += $price * $qty;

                    DB::table('order_items')->insert([
                        'order_id' => $orderId,
                        'product_variant_id' => null,
                        'bundle_id' => $bundle->id,
                        'item_type' => 'bundle',
                        'product_name_snapshot' => $bundle->name,
                        'sku_snapshot' => 'BUNDLE-' . $bundle->id,
                        'price_snapshot' => $price,
                        'qty' => $qty,
                    ]);
                } else {
                    $variant = $variants->random();
                    $qty = rand(1, 2);
                    $price = $variant->price;
                    $subtotal += $price * $qty;

                    $productName = DB::table('products')->where('id', $variant->product_id)->value('name');

                    DB::table('order_items')->insert([
                        'order_id' => $orderId,
                        'product_variant_id' => $variant->id,
                        'bundle_id' => null,
                        'item_type' => 'product',
                        'product_name_snapshot' => $productName,
                        'sku_snapshot' => $variant->sku,
                        'price_snapshot' => $price,
                        'qty' => $qty,
                    ]);
                }
            }

            $tax = round($subtotal * 0.08, 2);
            $shipping = $subtotal > 150 ? 0 : 12.00;
            $total = $subtotal + $tax + $shipping;

            DB::table('orders')->where('id', $orderId)->update([
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_cost' => $shipping,
                'total' => $total,
            ]);
        }
    }
}
