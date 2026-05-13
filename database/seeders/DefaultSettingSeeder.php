<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Page;
use App\Models\ShippingClass;
use App\Models\Attribute;
use App\Models\AttributeItem;

class DefaultSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Page::create([
            'title'     => 'Home Page',
            'slug'      => 'home',
            'content'   => 'This is the home page content.',
        ]);

        ShippingClass::insert([
            ['name' => 'Lightweight', 'description' => 'Items under 500g', 'inside_rate' => 60.00, 'outside_rate' => 120.00],
            ['name' => 'Standard', 'description' => 'Normal size items', 'inside_rate' => 80.00, 'outside_rate' => 150.00],
            ['name' => 'Bulky / Heavy', 'description' => 'Large or heavy items', 'inside_rate' => 150.00, 'outside_rate' => 300.00],
            ['name' => 'Fragile', 'description' => 'Handle with care', 'inside_rate' => 120.00, 'outside_rate' => 250.00],
        ]);


        /**---------------------------------------------------
         * Category
         * ---------------------------------------------------
         */
        $categories = [
            // Main Categories
            ['id' => 1, 'parent_id' => null, 'name' => 'Electronics', 'slug' => Str::slug('Electronics'), 'image' => 'electronics.jpg', 'status' => 1],
            ['id' => 2, 'parent_id' => null, 'name' => 'Fashion', 'slug' => Str::slug('Fashion'), 'image' => 'fashion.jpg', 'status' => 1],
            ['id' => 3, 'parent_id' => null, 'name' => 'Home & Kitchen', 'slug' => Str::slug('Home & Kitchen'), 'image' => 'home-kitchen.jpg', 'status' => 1],
            ['id' => 4, 'parent_id' => null, 'name' => 'Beauty & Personal Care', 'slug' => Str::slug('Beauty & Personal Care'), 'image' => 'beauty.jpg', 'status' => 1],
            ['id' => 5, 'parent_id' => null, 'name' => 'Sports & Outdoors', 'slug' => Str::slug('Sports & Outdoors'), 'image' => 'sports.jpg', 'status' => 1],
            ['id' => 6, 'parent_id' => null, 'name' => 'Automotive', 'slug' => Str::slug('Automotive'), 'image' => 'automotive.jpg', 'status' => 1],
            ['id' => 7, 'parent_id' => null, 'name' => 'Books & Stationery', 'slug' => Str::slug('Books & Stationery'), 'image' => 'books.jpg', 'status' => 1],
            ['id' => 8, 'parent_id' => null, 'name' => 'Toys & Games', 'slug' => Str::slug('Toys & Games'), 'image' => 'toys.jpg', 'status' => 1],
            ['id' => 9, 'parent_id' => null, 'name' => 'Health & Wellness', 'slug' => Str::slug('Health & Wellness'), 'image' => 'health.jpg', 'status' => 1],
            ['id' => 10, 'parent_id' => null, 'name' => 'Groceries', 'slug' => Str::slug('Groceries'), 'image' => 'groceries.jpg', 'status' => 1],

            // Sub Categories (5 examples)
            ['id' => 11, 'parent_id' => 1, 'name' => 'Mobile Phones', 'slug' => Str::slug('Mobile Phones'), 'image' => 'mobiles.jpg', 'status' => 1],
            ['id' => 12, 'parent_id' => 1, 'name' => 'Laptops', 'slug' => Str::slug('Laptops'), 'image' => 'laptops.jpg', 'status' => 1],
            ['id' => 13, 'parent_id' => 2, 'name' => 'Men Clothing', 'slug' => Str::slug('Men Clothing'), 'image' => 'men-clothing.jpg', 'status' => 1],
            ['id' => 14, 'parent_id' => 2, 'name' => 'Women Clothing', 'slug' => Str::slug('Women Clothing'), 'image' => 'women-clothing.jpg', 'status' => 1],
            ['id' => 15, 'parent_id' => 3, 'name' => 'Kitchen Appliances', 'slug' => Str::slug('Kitchen Appliances'), 'image' => 'kitchen-appliances.jpg', 'status' => 1],
        ];
        DB::table('categories')->insert($categories);

        /**---------------------------------------------------
         * Unit
         * ---------------------------------------------------
         */
        $units = [
            // Quantity Units
            ['id' => 1, 'name' => 'Piece', 'short_name' => 'pcs', 'unit_type' => 'quantity', 'status' => 1],
            ['id' => 2, 'name' => 'Box', 'short_name' => 'box', 'unit_type' => 'quantity', 'status' => 1],
            ['id' => 3, 'name' => 'Dozen', 'short_name' => 'dz', 'unit_type' => 'quantity', 'status' => 1],

            // Weight Units
            ['id' => 4, 'name' => 'Kilogram', 'short_name' => 'kg', 'unit_type' => 'weight', 'status' => 1],
            ['id' => 5, 'name' => 'Gram', 'short_name' => 'g', 'unit_type' => 'weight', 'status' => 1],
            ['id' => 6, 'name' => 'Ton', 'short_name' => 'ton', 'unit_type' => 'weight', 'status' => 1],

            // Length Units
            ['id' => 7, 'name' => 'Meter', 'short_name' => 'm', 'unit_type' => 'length', 'status' => 1],
            ['id' => 8, 'name' => 'Centimeter', 'short_name' => 'cm', 'unit_type' => 'length', 'status' => 1],

            // Volume Units
            ['id' => 9, 'name' => 'Liter', 'short_name' => 'l', 'unit_type' => 'volume', 'status' => 1],
            ['id' => 10, 'name' => 'Milliliter', 'short_name' => 'ml', 'unit_type' => 'volume', 'status' => 1],
        ];
        DB::table('units')->insert($units);


        /**---------------------------------------------------
         * Attribute
         * ---------------------------------------------------
         */
        // 1. Create Color attribute
        $color = Attribute::updateOrCreate(
            ['name' => 'Color'],
            ['display_type' => 'color', 'has_image' => true]
        );

        // 12 colors
        $colors = [
            'Red', 'Blue', 'Green', 'Yellow', 'Black', 'White',
            'Orange', 'Purple', 'Pink', 'Brown', 'Gray', 'Cyan'
        ];

        foreach ($colors as $index => $colorName) {
            AttributeItem::updateOrCreate(
                ['attribute_id' => $color->id, 'name' => $colorName],
                ['sort_order' => $index + 1]
            );
        }

        // 2. Create Size attribute
        $size = Attribute::updateOrCreate(
            ['name' => 'Size'],
            ['display_type' => 'dropdown', 'has_image' => false]
        );

        $sizes = ['S', 'M', 'L', 'XL', 'XXL'];

        foreach ($sizes as $index => $sizeName) {
            AttributeItem::updateOrCreate(
                ['attribute_id' => $size->id, 'name' => $sizeName],
                ['sort_order' => $index + 1]
            );
        }

        /**---------------------------------------------------
         * Warranty
         * ---------------------------------------------------
         */

        $warranties = [
            ['id' => 1, 'name' => 'No Warranty', 'duration' => 0, 'period' => 'day', 'description' => 'Product has no warranty', 'status' => 1],

            ['id' => 2, 'name' => '7 Days Replacement Warranty', 'duration' => 7, 'period' => 'day', 'description' => '7 days replacement warranty', 'status' => 1],

            ['id' => 3, 'name' => '1 Month Service Warranty', 'duration' => 1, 'period' => 'month', 'description' => '1 month service warranty', 'status' => 1],

            ['id' => 4, 'name' => '6 Months Warranty', 'duration' => 6, 'period' => 'month', 'description' => '6 months manufacturer warranty', 'status' => 1],

            ['id' => 5, 'name' => '1 Year Warranty', 'duration' => 1, 'period' => 'year', 'description' => '1 year official warranty', 'status' => 1],

            ['id' => 6, 'name' => '2 Years Warranty', 'duration' => 2, 'period' => 'year', 'description' => '2 years extended warranty', 'status' => 1],
        ];

        DB::table('warranties')->insert($warranties);

    }
}
