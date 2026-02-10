<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $brandIds = DB::table('brands')->pluck('id')->all();
        $vendorIds = DB::table('vendors')->pluck('id')->all();
        $categoryIdsBySlug = DB::table('categories')->pluck('id', 'slug');

        $attributeIdByName = DB::table('attributes')->pluck('id', 'name');
        $attributeValues = DB::table('attribute_values')->get()->groupBy('attribute_id');

        $productTypes = [
            'english-willow-bats' => ['Pro', 'Elite', 'Classic', 'Power', 'Prime'],
            'kashmir-willow-bats' => ['Select', 'Storm', 'Edge', 'Strike', 'Legend'],
            'junior-bats' => ['Junior', 'Youth', 'Starter', 'Spark', 'Lite'],
            'leather-cricket-balls' => ['Match', 'Test', 'County', 'Club', 'Elite'],
            'tennis-practice-balls' => ['Trainer', 'Practice', 'Coaching', 'Soft', 'Lite'],
            'white-cricket-balls' => ['Limited', 'Pro', 'Premier', 'Day/Night', 'Series'],
            'helmets' => ['Guard', 'Shield', 'Pro', 'Lite', 'Apex'],
            'batting-pads' => ['Flex', 'Pro', 'Elite', 'Comfort', 'Ultra'],
            'wicketkeeping-pads' => ['Keeper', 'Agile', 'Pro', 'Compact', 'Guard'],
            'gloves' => ['Grip', 'Pro', 'Elite', 'Comfort', 'Ultra'],
            'abdominal-guards' => ['Guard', 'Shield', 'Pro', 'Comfort', 'Lite'],
            'jerseys' => ['Home', 'Away', 'Training', 'Pro', 'Classic'],
            'pants' => ['Match', 'Training', 'Elite', 'Pro', 'Classic'],
            'shoes' => ['Spike', 'Sprint', 'Velocity', 'Control', 'Edge'],
            'bat-grips' => ['Cushion', 'Pro', 'Tacky', 'Comfort', 'Ultra'],
            'bat-covers' => ['Pro', 'Sleeve', 'Classic', 'Premium', 'Travel'],
            'stumps-bails' => ['Club', 'Match', 'Pro', 'Elite', 'Tournament'],
            'bags-backpacks' => ['Wheelie', 'Duffle', 'Backpack', 'Pro', 'Travel'],
        ];

        $priceRanges = [
            'english-willow-bats' => [180, 380],
            'kashmir-willow-bats' => [120, 240],
            'junior-bats' => [60, 140],
            'leather-cricket-balls' => [18, 45],
            'tennis-practice-balls' => [8, 25],
            'white-cricket-balls' => [20, 55],
            'helmets' => [70, 180],
            'batting-pads' => [60, 160],
            'wicketkeeping-pads' => [70, 170],
            'gloves' => [40, 120],
            'abdominal-guards' => [12, 35],
            'jerseys' => [25, 70],
            'pants' => [30, 80],
            'shoes' => [60, 150],
            'bat-grips' => [6, 20],
            'bat-covers' => [12, 35],
            'stumps-bails' => [35, 120],
            'bags-backpacks' => [45, 140],
        ];

        $attributeValueId = function (string $attributeName) use ($attributeIdByName, $attributeValues): array {
            $attributeId = $attributeIdByName[$attributeName] ?? null;
            if (!$attributeId) {
                return [];
            }
            return $attributeValues[$attributeId]->pluck('id', 'value')->all();
        };

        $sizeValues = $attributeValueId('size');
        $weightValues = $attributeValueId('weight');
        $handednessValues = $attributeValueId('handedness');
        $materialValues = $attributeValueId('material');
        $colorValues = $attributeValueId('color');

        foreach ($productTypes as $categorySlug => $namePool) {
            $categoryId = $categoryIdsBySlug[$categorySlug] ?? null;
            if (!$categoryId) {
                continue;
            }

            for ($i = 1; $i <= 3; $i++) {
                $brandId = $faker->randomElement($brandIds);
                $vendorId = $faker->randomElement($vendorIds);
                $name = $faker->randomElement($namePool) . ' ' . strtoupper($faker->bothify('??')) . ' ' . Str::headline(str_replace('-', ' ', $categorySlug));
                $slug = Str::slug($name . '-' . $i . '-' . $categorySlug);

                $productId = DB::table('products')->insertGetId([
                    'name' => $name,
                    'slug' => $slug,
                    'brand_id' => $brandId,
                    'category_id' => $categoryId,
                    'vendor_id' => $vendorId,
                    'description' => $faker->paragraphs(2, true),
                    'short_description' => $faker->sentence(10),
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('product_images')->insert([
                    [
                        'product_id' => $productId,
                        'path' => 'products/' . $slug . '/1.jpg',
                        'is_primary' => true,
                        'sort_order' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'product_id' => $productId,
                        'path' => 'products/' . $slug . '/2.jpg',
                        'is_primary' => false,
                        'sort_order' => 2,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ]);

                $variantCount = $faker->numberBetween(2, 3);
                for ($v = 1; $v <= $variantCount; $v++) {
                    [$minPrice, $maxPrice] = $priceRanges[$categorySlug];
                    $price = $faker->randomFloat(2, $minPrice, $maxPrice);
                    $compareAt = $faker->boolean(30) ? $price + $faker->randomFloat(2, 5, 30) : null;

                    $variantId = DB::table('product_variants')->insertGetId([
                        'product_id' => $productId,
                        'sku' => strtoupper(Str::random(3)) . '-' . str_pad((string) $productId, 4, '0', STR_PAD_LEFT) . '-' . $v,
                        'price' => $price,
                        'compare_at_price' => $compareAt,
                        'stock' => $faker->numberBetween(5, 120),
                        'weight_grams' => $this->guessWeight($categorySlug, $faker),
                        'status' => 'active',
                        'carts_count' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $this->attachAttributes(
                        $variantId,
                        $categorySlug,
                        $sizeValues,
                        $weightValues,
                        $handednessValues,
                        $materialValues,
                        $colorValues,
                        $faker
                    );
                }
            }
        }
    }

    private function guessWeight(string $categorySlug, $faker): ?int
    {
        $ranges = [
            'english-willow-bats' => [1100, 1300],
            'kashmir-willow-bats' => [1050, 1250],
            'junior-bats' => [800, 1000],
            'leather-cricket-balls' => [155, 165],
            'tennis-practice-balls' => [50, 90],
            'white-cricket-balls' => [155, 165],
            'helmets' => [600, 900],
            'batting-pads' => [800, 1400],
            'wicketkeeping-pads' => [700, 1200],
            'gloves' => [350, 700],
            'abdominal-guards' => [80, 200],
            'jerseys' => [200, 350],
            'pants' => [220, 380],
            'shoes' => [600, 1000],
            'bat-grips' => [60, 120],
            'bat-covers' => [150, 400],
            'stumps-bails' => [1000, 2000],
            'bags-backpacks' => [800, 1600],
        ];

        if (!isset($ranges[$categorySlug])) {
            return null;
        }

        [$min, $max] = $ranges[$categorySlug];
        return $faker->numberBetween($min, $max);
    }

    private function attachAttributes(
        int $variantId,
        string $categorySlug,
        array $sizeValues,
        array $weightValues,
        array $handednessValues,
        array $materialValues,
        array $colorValues,
        $faker
    ): void {
        $rows = [];

        $attach = function (array $valueMap, string $attributeName, ?string $value = null) use (&$rows, $variantId): void {
            if (!$valueMap) {
                return;
            }
            $picked = $value ?? array_rand($valueMap);
            if (!isset($valueMap[$picked])) {
                return;
            }
            $rows[] = [
                'product_variant_id' => $variantId,
                'attribute_id' => DB::table('attributes')->where('name', $attributeName)->value('id'),
                'attribute_value_id' => $valueMap[$picked],
            ];
        };

        $batCategories = ['english-willow-bats', 'kashmir-willow-bats', 'junior-bats'];
        $ballCategories = ['leather-cricket-balls', 'tennis-practice-balls', 'white-cricket-balls'];
        $apparelCategories = ['jerseys', 'pants', 'shoes'];
        $protectiveCategories = ['helmets', 'batting-pads', 'wicketkeeping-pads', 'gloves', 'abdominal-guards'];

        if (in_array($categorySlug, $batCategories, true)) {
            $attach($handednessValues, 'handedness');
            $attach($weightValues, 'weight');
            $attach($materialValues, 'material', $categorySlug === 'kashmir-willow-bats' ? 'Kashmir Willow' : 'English Willow');
            $attach($colorValues, 'color');
        } elseif (in_array($categorySlug, $ballCategories, true)) {
            $attach($materialValues, 'material', 'PU Leather');
            $attach($colorValues, 'color', $categorySlug === 'white-cricket-balls' ? 'White' : 'Red');
        } elseif (in_array($categorySlug, $apparelCategories, true)) {
            $attach($sizeValues, 'size');
            $attach($materialValues, 'material', 'Polyester');
            $attach($colorValues, 'color');
        } elseif (in_array($categorySlug, $protectiveCategories, true)) {
            $attach($sizeValues, 'size');
            $attach($materialValues, 'material', 'Composite');
            $attach($colorValues, 'color');
        } else {
            $attach($colorValues, 'color');
        }

        if ($rows) {
            DB::table('product_variant_attributes')->insert($rows);
        }
    }
}
