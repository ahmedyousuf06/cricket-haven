<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentsSeeder extends Seeder
{
    public function run(): void
    {
        $orders = DB::table('orders')->get();

        foreach ($orders as $order) {
            $status = in_array($order->status, ['pending'], true) ? 'pending' : 'success';

            DB::table('payments')->insert([
                'order_id' => $order->id,
                'provider' => 'stripe',
                'provider_ref' => 'pi_' . Str::upper(Str::random(12)),
                'status' => $status,
                'amount' => $order->total,
                'currency' => 'USD',
                'created_at' => now(),
            ]);
        }
    }
}
