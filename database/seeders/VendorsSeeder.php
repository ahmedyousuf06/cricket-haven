<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorsSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = [
            [
                'name' => 'Default Vendor',
                'contact_email' => 'vendor@example.com',
                'contact_phone' => '+1-555-0100',
                'address' => '100 Main St, Newark, NJ',
                'status' => 'active',
            ],
            [
                'name' => 'Cricket Pro Supplies',
                'contact_email' => 'sales@cricketprosupplies.com',
                'contact_phone' => '+1-555-0111',
                'address' => '200 Willow Ave, Edison, NJ',
                'status' => 'active',
            ],
            [
                'name' => 'Elite Cricket Gear',
                'contact_email' => 'hello@elitecricketgear.com',
                'contact_phone' => '+1-555-0222',
                'address' => '45 Cricket Blvd, San Jose, CA',
                'status' => 'active',
            ],
            [
                'name' => 'Boundary Sports',
                'contact_email' => 'info@boundarysports.com',
                'contact_phone' => '+1-555-0333',
                'address' => '78 Pitch Road, Dallas, TX',
                'status' => 'active',
            ],
        ];

        $rows = [];
        foreach ($vendors as $vendor) {
            $rows[] = array_merge($vendor, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('vendors')->insert($rows);
    }
}
