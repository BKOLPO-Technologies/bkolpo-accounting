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

        // Fetch existing categories
        $categories = Category::pluck('id', 'name');

        $products = [
            ['name' => 'Smartphone', 'category' => 'Electronics', 'price' => 500, 'quantity' => 10, 'status' => 1, 'image' => null],
            ['name' => 'T-shirt', 'category' => 'Fashion', 'price' => 600, 'quantity' => 50, 'status' => 1, 'image' => null],
            ['name' => 'Microwave Oven', 'category' => 'Home Appliances', 'price' => 150, 'quantity' => 5, 'status' => 1, 'image' => null],
            ['name' => 'Novel Book', 'category' => 'Books', 'price' => 700, 'quantity' => 30, 'status' => 1, 'image' => null],
            ['name' => 'Toy Car', 'category' => 'Toys', 'price' => 800, 'quantity' => 20, 'status' => 1, 'image' => null],
        ];

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
