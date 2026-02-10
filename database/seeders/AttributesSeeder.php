<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributesSeeder extends Seeder
{
    public function run(): void
    {
        $attributes = [
            'size',
            'weight',
            'handedness',
            'material',
            'color',
        ];

        $rows = [];
        foreach ($attributes as $name) {
            $rows[] = [
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('attributes')->insert($rows);
    }
}
