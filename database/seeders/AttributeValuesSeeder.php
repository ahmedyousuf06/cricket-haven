<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeValuesSeeder extends Seeder
{
    public function run(): void
    {
        $attributeIdByName = DB::table('attributes')->pluck('id', 'name');

        $values = [
            'size' => ['Junior', 'Small', 'Medium', 'Large', 'XL'],
            'weight' => ['2.8lb', '2.9lb', '3.0lb', '3.1lb', '3.2lb'],
            'handedness' => ['RH', 'LH'],
            'material' => ['English Willow', 'Kashmir Willow', 'PU Leather', 'PVC', 'Cotton', 'Polyester', 'Composite', 'Rubber'],
            'color' => ['Red', 'White', 'Blue', 'Black', 'Green', 'Yellow'],
        ];

        foreach ($values as $attributeName => $valueList) {
            $attributeId = $attributeIdByName[$attributeName] ?? null;
            if (!$attributeId) {
                continue;
            }

            $rows = [];
            foreach ($valueList as $value) {
                $rows[] = [
                    'attribute_id' => $attributeId,
                    'value' => $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('attribute_values')->insert($rows);
        }
    }
}
