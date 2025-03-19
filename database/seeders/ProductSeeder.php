<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure categories and units exist
        if (Category::count() == 0) {
            $this->command->warn("No categories found! Please run `php artisan db:seed --class=CategorySeeder` first.");
            return;
        }

        if (Unit::count() == 0) {
            $this->command->warn("No units found! Please run `php artisan db:seed --class=UnitSeeder` first.");
            return;
        }

        // Fetch existing categories by slug and units by name
        $categories = Category::pluck('id', 'slug');
        $units = Unit::pluck('id', 'name'); // Assuming unique unit names

        // Define products with respective categories and units
        $products = [
            ['name' => 'IT Equipment', 'category' => 'civil-construction', 'unit' => 'Piece', 'price' => 5000, 'quantity' => 15, 'status' => 1, 'image' => null],
            ['name' => 'Bali', 'category' => 'civil-construction', 'unit' => 'Kilogram', 'price' => 200, 'quantity' => 100, 'status' => 1, 'image' => null],
            ['name' => 'Khoya', 'category' => 'civil-construction', 'unit' => 'Kilogram', 'price' => 150, 'quantity' => 50, 'status' => 1, 'image' => null],
            ['name' => 'Cement Bags', 'category' => 'still-structure', 'unit' => 'Bag', 'price' => 350, 'quantity' => 200, 'status' => 1, 'image' => null],
            ['name' => 'Steel Rods', 'category' => 'still-structure', 'unit' => 'Meter', 'price' => 1200, 'quantity' => 50, 'status' => 1, 'image' => null],
            ['name' => 'Brick', 'category' => 'still-structure', 'unit' => 'Piece', 'price' => 25, 'quantity' => 1000, 'status' => 1, 'image' => null],
            ['name' => 'Wood Planks', 'category' => 'interior-design', 'unit' => 'Meter', 'price' => 300, 'quantity' => 80, 'status' => 1, 'image' => null],
            ['name' => 'Paint', 'category' => 'interior-design', 'unit' => 'Liter', 'price' => 500, 'quantity' => 60, 'status' => 1, 'image' => null],
        ];

        // Insert products
        foreach ($products as $product) {
            if (!isset($categories[$product['category']])) {
                $this->command->warn("Category '{$product['category']}' not found. Skipping product: {$product['name']}");
                continue;
            }

            if (!isset($units[$product['unit']])) {
                $this->command->warn("Unit '{$product['unit']}' not found. Skipping product: {$product['name']}");
                continue;
            }

            Product::updateOrCreate(
                ['name' => $product['name']], // Prevent duplicate products
                [
                    'category_id' => $categories[$product['category']],
                    'unit_id' => $units[$product['unit']],
                    'price' => $product['price'],
                    'quantity' => $product['quantity'],
                    'status' => $product['status'],
                    'image' => $product['image'],
                ]
            );
        }
    }
}
