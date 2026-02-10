<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BrandsSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'SG',
            'Kookaburra',
            'Gray-Nicolls',
            'Adidas',
            'Puma',
            'New Balance',
            'SS',
            'GM',
            'MRF',
            'Spartan',
            'Masuri',
            'DSC',
        ];

        $rows = [];
        foreach ($brands as $brand) {
            $rows[] = [
                'name' => $brand,
                'slug' => Str::slug($brand),
                'logo_path' => 'brands/' . Str::slug($brand) . '.png',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('brands')->insert($rows);
    }
}
