<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $tree = [
            'Bats' => [
                'English Willow Bats',
                'Kashmir Willow Bats',
                'Junior Bats',
            ],
            'Balls' => [
                'Leather Cricket Balls',
                'Tennis/Practice Balls',
                'White Cricket Balls',
            ],
            'Protective Gear' => [
                'Helmets',
                'Batting Pads',
                'Wicketkeeping Pads',
                'Gloves',
                'Abdominal Guards',
            ],
            'Apparel' => [
                'Jerseys',
                'Pants',
                'Shoes',
            ],
            'Accessories' => [
                'Bat Grips',
                'Bat Covers',
                'Stumps & Bails',
                'Bags & Backpacks',
            ],
        ];

        foreach ($tree as $parentName => $children) {
            $parentId = DB::table('categories')->insertGetId([
                'name' => $parentName,
                'slug' => Str::slug($parentName),
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($children as $child) {
                DB::table('categories')->insert([
                    'name' => $child,
                    'slug' => Str::slug($child),
                    'parent_id' => $parentId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
