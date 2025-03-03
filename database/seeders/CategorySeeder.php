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
            ['name' => 'Electronics', 'slug' => Str::slug('Electronics'), 'image' => null, 'status' => 1],
            ['name' => 'Fashion', 'slug' => Str::slug('Fashion'), 'image' => null, 'status' => 1],
            ['name' => 'Home Appliances', 'slug' => Str::slug('Home Appliances'), 'image' => null, 'status' => 1],
            ['name' => 'Books', 'slug' => Str::slug('Books'), 'image' => null, 'status' => 1],
            ['name' => 'Toys', 'slug' => Str::slug('Toys'), 'image' => null, 'status' => 1],
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
