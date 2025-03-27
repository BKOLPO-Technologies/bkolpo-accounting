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
            ['name' => 'PCS'],
            ['name' => 'KG'],
            ['name' => 'LTR'],
            ['name' => 'MTR'],
            ['name' => 'BAG'],
            ['name' => 'BOX'],
            ['name' => 'SFT'],
            ['name' => 'NOS'],
            ['name' => 'CFT'],
            ['name' => 'DRUM'],
            ['name' => 'GALOON'],
        ];

        foreach ($units as $unit) {
            Unit::updateOrCreate(['name' => $unit['name']], $unit);
        }
    }
}
