<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Category::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categories = [
            [
                'name_ar' => 'أجهزة كمبيوتر محمولة',
                'name_en' => 'Laptops',
                'description_ar' => 'أجهزة كمبيوتر محمولة من مختلف الماركات',
                'description_en' => 'Laptops from various brands',
                'slug' => 'laptops',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_ar' => 'هواتف ذكية',
                'name_en' => 'Smartphones',
                'description_ar' => 'هواتف ذكية حديثة',
                'description_en' => 'Modern smartphones',
                'slug' => 'smartphones',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_ar' => 'أجهزة لوحية',
                'name_en' => 'Tablets',
                'description_ar' => 'أجهزة لوحية للعمل والترفيه',
                'description_en' => 'Tablets for work and entertainment',
                'slug' => 'tablets',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_ar' => 'شاشات',
                'name_en' => 'Monitors',
                'description_ar' => 'شاشات كمبيوتر عالية الجودة',
                'description_en' => 'High quality computer monitors',
                'slug' => 'monitors',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_ar' => 'لوحات مفاتيح',
                'name_en' => 'Keyboards',
                'description_ar' => 'لوحات مفاتيح ميكانيكية وعادية',
                'description_en' => 'Mechanical and regular keyboards',
                'slug' => 'keyboards',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_ar' => 'فأرات حاسوب',
                'name_en' => 'Mice',
                'description_ar' => 'فأرات حاسوب لاسلكية وسلكية',
                'description_en' => 'Wireless and wired computer mice',
                'slug' => 'mice',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_ar' => 'سماعات',
                'name_en' => 'Headphones',
                'description_ar' => 'سماعات صوتية عالية الجودة',
                'description_en' => 'High quality audio headphones',
                'slug' => 'headphones',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_ar' => 'طابعات',
                'name_en' => 'Printers',
                'description_ar' => 'طابعات ليزر وحبر',
                'description_en' => 'Laser and ink printers',
                'slug' => 'printers',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Category::insert($categories);
    }
}
