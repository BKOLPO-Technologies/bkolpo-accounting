<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['name' => 'Piece'],
            ['name' => 'Kilogram'],
            ['name' => 'Liter'],
            ['name' => 'Meter'],
            ['name' => 'Bag'],
            ['name' => 'Box'],
        ];

        foreach ($units as $unit) {
            Unit::updateOrCreate(['name' => $unit['name']], $unit);
        }
    }
}
