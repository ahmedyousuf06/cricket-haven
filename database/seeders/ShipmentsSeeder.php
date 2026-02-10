<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ShipmentsSeeder extends Seeder
{
    public function run(): void
    {
        $orders = DB::table('orders')->whereIn('status', ['shipped', 'delivered'])->get();

        foreach ($orders as $order) {
            DB::table('shipments')->insert([
                'order_id' => $order->id,
                'carrier' => 'UPS',
                'tracking_number' => '1Z' . Str::upper(Str::random(12)),
                'status' => $order->status === 'delivered' ? 'delivered' : 'shipped',
                'shipped_at' => now()->subDays(rand(1, 5)),
            ]);
        }
    }
}
