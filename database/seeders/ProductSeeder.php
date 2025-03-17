<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure categories exist before seeding products
        if (Category::count() == 0) {
            $this->command->warn("No categories found! Please run `php artisan db:seed --class=CategorySeeder` first.");
            return;
        }

        // Fetch existing categories by slug
        $categories = Category::pluck('id', 'slug');

        // Define products and their respective categories
        $products = [
            ['name' => 'IT Equipment', 'category' => 'civil-construction', 'price' => 5000, 'quantity' => 15, 'status' => 1, 'image' => null],
            ['name' => 'Bali', 'category' => 'civil-construction', 'price' => 200, 'quantity' => 100, 'status' => 1, 'image' => null],
            ['name' => 'Khoya', 'category' => 'civil-construction', 'price' => 150, 'quantity' => 50, 'status' => 1, 'image' => null],
            ['name' => 'Cement Bags', 'category' => 'still-structure', 'price' => 350, 'quantity' => 200, 'status' => 1, 'image' => null],
            ['name' => 'Steel Rods', 'category' => 'still-structure', 'price' => 1200, 'quantity' => 50, 'status' => 1, 'image' => null],
            ['name' => 'Brick', 'category' => 'still-structure', 'price' => 25, 'quantity' => 1000, 'status' => 1, 'image' => null],
            ['name' => 'Wood Planks', 'category' => 'interior-design', 'price' => 300, 'quantity' => 80, 'status' => 1, 'image' => null],
            ['name' => 'Paint', 'category' => 'interior-design', 'price' => 500, 'quantity' => 60, 'status' => 1, 'image' => null],
        ];

        // Loop through each product and add it to the database
        foreach ($products as $product) {
            if (!isset($categories[$product['category']])) {
                $this->command->warn("Category '{$product['category']}' not found. Skipping product: {$product['name']}");
                continue;
            }

            Product::updateOrCreate(
                ['name' => $product['name']], // Prevent duplicate products
                [
                    'category_id' => $categories[$product['category']],
                    'price' => $product['price'],
                    'quantity' => $product['quantity'],
                    'status' => $product['status'],
                    'image' => $product['image'],
                ]
            );
        }
    }
}
