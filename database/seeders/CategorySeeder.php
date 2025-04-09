<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Civil Construction', 'slug' => Str::slug('Civil Construction'),'vat'=>5,'tax'=>10, 'image' => null, 'status' => 1],
            ['name' => 'Still Structure', 'slug' => Str::slug('Still Structure'),'vat'=>10,'tax'=>15, 'image' => null, 'status' => 1],
            ['name' => 'Interior Design', 'slug' => Str::slug('Interior Design'),'vat'=>15,'tax'=>20, 'image' => null, 'status' => 1],
        ];

        //DB::table('categories')->insert($categories);

        // foreach ($categories as $category) {
        //     Category::create($category);
        // }

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']], // Condition to check if it exists
                $category // Data to insert or update
            );
        }
    }
}
