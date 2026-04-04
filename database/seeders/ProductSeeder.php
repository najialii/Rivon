<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Price;
use App\Models\Inventory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::truncate();
        Price::truncate();
        Inventory::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = Faker::create();
        
        $products = [
            // Apple Products
            [
                'sku' => 'AAP-MBP14-2024',
                'name_ar' => 'ماك بوك برو 14 بوصة',
                'name_en' => 'MacBook Pro 14"',
                'description_ar' => 'أقوى ماك بوك برو مع شريحة M3',
                'description_en' => 'Most powerful MacBook Pro with M3 chip',
                'img_path' => 'products/macbook-pro-14.jpg',
                'brand_id' => 1, // Apple
                'category_id' => 1, // Laptops
                'munit' => 'piece',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sku' => 'AAP-IP15-2024',
                'name_ar' => 'آيفون 15 برو',
                'name_en' => 'iPhone 15 Pro',
                'description_ar' => 'آيفون 15 برو مع تيتانيوم',
                'description_en' => 'iPhone 15 Pro with titanium',
                'img_path' => 'products/iphone-15-pro.jpg',
                'brand_id' => 1, // Apple
                'category_id' => 2, // Smartphones
                'munit' => 'piece',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sku' => 'AAP-IPAD-2024',
                'name_ar' => 'آيباد برو 12.9',
                'name_en' => 'iPad Pro 12.9"',
                'description_ar' => 'آيباد برو بشاشة كبيرة',
                'description_en' => 'iPad Pro with large display',
                'img_path' => 'products/ipad-pro-12.jpg',
                'brand_id' => 1, // Apple
                'category_id' => 3, // Tablets
                'munit' => 'piece',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Samsung Products
            [
                'sku' => 'SAM-GS24U-2024',
                'name_ar' => 'جالاكسي S24 الترا',
                'name_en' => 'Galaxy S24 Ultra',
                'description_ar' => 'هاتف جالاكسي S24 الترا المميز',
                'description_en' => 'Premium Galaxy S24 Ultra phone',
                'img_path' => 'products/galaxy-s24-ultra.jpg',
                'brand_id' => 2, // Samsung
                'category_id' => 2, // Smartphones
                'munit' => 'piece',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sku' => 'SAM-TAB-S9-2024',
                'name_ar' => 'جالاكسي تاب S9',
                'name_en' => 'Galaxy Tab S9',
                'description_ar' => 'جهاز لوحي جالاكسي تاب S9',
                'description_en' => 'Galaxy Tab S9 tablet',
                'img_path' => 'products/galaxy-tab-s9.jpg',
                'brand_id' => 2, // Samsung
                'category_id' => 3, // Tablets
                'munit' => 'piece',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Sony Products
            [
                'sku' => 'SNY-WH1000XM5',
                'name_ar' => 'سوني WH-1000XM5',
                'name_en' => 'Sony WH-1000XM5',
                'description_ar' => 'سماعات سوني الرائعة لإلغاء الضوضاء',
                'description_en' => 'Sony premium noise-canceling headphones',
                'img_path' => 'products/sony-wh1000xm5.jpg',
                'brand_id' => 3, // Sony
                'category_id' => 7, // Headphones
                'munit' => 'piece',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Dell Products
            [
                'sku' => 'DEL-XPS15-2024',
                'name_ar' => 'ديل XPS 15',
                'name_en' => 'Dell XPS 15',
                'description_ar' => 'كمبيوتر محمول ديل XPS 15 عالي الأداء',
                'description_en' => 'Dell XPS 15 high-performance laptop',
                'img_path' => 'products/dell-xps-15.jpg',
                'brand_id' => 5, // Dell
                'category_id' => 1, // Laptops
                'munit' => 'piece',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // HP Products
            [
                'sku' => 'HP-OMEN-16-2024',
                'name_ar' => 'HP Omen 16',
                'name_en' => 'HP Omen 16',
                'description_ar' => 'كمبيوتر محمول HP Omen للألعاب',
                'description_en' => 'HP Omen gaming laptop',
                'img_path' => 'products/hp-omen-16.jpg',
                'brand_id' => 6, // HP
                'category_id' => 1, // Laptops
                'munit' => 'piece',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Microsoft Products
            [
                'sku' => 'MS-SURF-PRO-9',
                'name_ar' => 'سيرفس برو 9',
                'name_en' => 'Surface Pro 9',
                'description_ar' => 'جهاز سيرفس برو 9 متعدد الاستخدامات',
                'description_en' => 'Surface Pro 9 versatile device',
                'img_path' => 'products/surface-pro-9.jpg',
                'brand_id' => 8, // Microsoft
                'category_id' => 3, // Tablets
                'munit' => 'piece',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Product::insert($products);

        // Create prices for each product
        foreach ($products as $index => $product) {
            $basePrice = $faker->numberBetween(500, 3000);
            Price::create([
                'product_id' => $index + 1,
                'price' => $basePrice,
                'currency' => 'USD',
                'wholesale_price' => $basePrice * 0.85,
                'retail_price' => $basePrice * 1.15,
                'wholesale_min_price' => $basePrice * 0.75,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create inventory for each product
            Inventory::create([
                'product_id' => $index + 1,
                'total_qty' => $faker->numberBetween(10, 100),
                'wholesale_recived_qty' => $faker->numberBetween(5, 50),
                'retail_recived_qty' => $faker->numberBetween(5, 50),
                'location' => 'Warehouse A',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
