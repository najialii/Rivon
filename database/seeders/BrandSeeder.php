<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Brand::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $brands = [
            [
                'name_ar' => 'أبل',
                'name_en' => 'Apple',
                'slug' => 'apple',
                'logo' => 'brands/apple.png',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_ar' => 'سامسونج',
                'name_en' => 'Samsung',
                'slug' => 'samsung',
                'logo' => 'brands/samsung.png',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_ar' => 'سوني',
                'name_en' => 'Sony',
                'slug' => 'sony',
                'logo' => 'brands/sony.png',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_ar' => 'إل جي',
                'name_en' => 'LG',
                'slug' => 'lg',
                'logo' => 'brands/lg.png',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_ar' => 'دل',
                'name_en' => 'Dell',
                'slug' => 'dell',
                'logo' => 'brands/dell.png',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_ar' => 'إتش بي',
                'name_en' => 'HP',
                'slug' => 'hp',
                'logo' => 'brands/hp.png',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_ar' => 'لينوفو',
                'name_en' => 'Lenovo',
                'slug' => 'lenovo',
                'logo' => 'brands/lenovo.png',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_ar' => 'مايكروسوفت',
                'name_en' => 'Microsoft',
                'slug' => 'microsoft',
                'logo' => 'brands/microsoft.png',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Brand::insert($brands);
    }
}
