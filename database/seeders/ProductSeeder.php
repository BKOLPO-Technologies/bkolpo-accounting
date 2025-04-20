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
        $units = Unit::pluck('id', 'id'); // Assuming unique unit names

        // Define products with respective categories and units
        $products = [
            ['name' => 'IT Equipment', 'category' => 'civil-construction', 'unit_id' => '1', 'price' => 5000, 'quantity' => 15,'group_name'=>'sales', 'status' => 1, 'image' => null],
            ['name' => 'Bali', 'category' => 'civil-construction', 'unit_id' => '2', 'price' => 200, 'quantity' => 100,'group_name'=>'sales', 'status' => 1, 'image' => null],
            ['name' => 'Khoya', 'category' => 'civil-construction', 'unit_id' => '3', 'price' => 150, 'quantity' => 50,'group_name'=>'sales', 'status' => 1, 'image' => null],
            ['name' => 'Cement Bags', 'category' => 'still-structure', 'unit_id' => '4', 'price' => 350, 'quantity' => 200,'group_name'=>'sales', 'status' => 1, 'image' => null],
            ['name' => 'Steel Rods', 'category' => 'still-structure', 'unit_id' => '5', 'price' => 1200, 'quantity' => 50,'group_name'=>'purchases', 'status' => 1, 'image' => null],
            ['name' => 'Brick', 'category' => 'still-structure', 'unit_id' => '6', 'price' => 25, 'quantity' => 1000,'group_name'=>'purchases', 'status' => 1, 'image' => null],
            ['name' => 'Wood Planks', 'category' => 'interior-design', 'unit_id' => '7', 'price' => 300, 'quantity' => 80,'group_name'=>'purchases', 'status' => 1, 'image' => null],
            ['name' => 'Paint', 'category' => 'interior-design', 'unit_id' => '8', 'price' => 500, 'quantity' => 60,'group_name'=>'purchases', 'status' => 1, 'image' => null],
        ];

        // Insert products
        foreach ($products as $product) {
            if (!isset($categories[$product['category']])) {
                $this->command->warn("Category '{$product['category']}' not found. Skipping product: {$product['name']}");
                continue;
            }

            if (!isset($units[$product['unit_id']])) {
                $this->command->warn("Unit '{$product['unit_id']}' not found. Skipping product: {$product['name']}");
                continue;
            }

            // Generate a unique product code
            $productCode = $this->generateProductCode();

            // Create or update the product with the product_code
            Product::updateOrCreate(
                ['name' => $product['name']], // Prevent duplicate products
                [
                    'category_id' => $categories[$product['category']],
                    'unit_id' => $units[$product['unit_id']],
                    'price' => $product['price'],
                    'quantity' => $product['quantity'],
                    'group_name' => $product['group_name'],
                    'status' => $product['status'],
                    'image' => $product['image'],
                    'product_code' => $productCode, // Add the product_code
                ]
            );
        }
    }

    /**
     * Generate a unique product code in the format PRDF1PJ8.
     *
     * @return string
     */
    private function generateProductCode(): string
    {
        // Generate a unique code, you can customize this logic as needed
        $prefix = 'PRDF';
        $randomPart = strtoupper(bin2hex(random_bytes(3))); // Generate a random part (6 hex chars)
        return $prefix . $randomPart;
    }

}
