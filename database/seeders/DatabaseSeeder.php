<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategoriesSeeder::class,
            BrandsSeeder::class,
            VendorsSeeder::class,
            AttributesSeeder::class,
            AttributeValuesSeeder::class,
            ProductsSeeder::class,
            ProductBundlesSeeder::class,
            BulkPricingRulesSeeder::class,
            UsersSeeder::class,
            CartsSeeder::class,
            OrdersSeeder::class,
            PaymentsSeeder::class,
            ShipmentsSeeder::class,
            ReviewsSeeder::class,
        ]);
    }
}
