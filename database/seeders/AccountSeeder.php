<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Account::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $accounts = [
            // Assets
            [
                'name_ar' => 'النقدية والبنوك',
                'name_en' => 'Cash and Banks',
                'account_type' => 'asset',
                'code' => '1000',
                'description_ar' => 'حسابات النقدية والبنوك',
                'description_en' => 'Cash and bank accounts',
                'currency' => 'USD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_ar' => 'النقدية',
                'name_en' => 'Cash on Hand',
                'account_type' => 'asset',
                'code' => '1010',
                'description_ar' => 'النقدية في الخزنة',
                'description_en' => 'Cash in safe',
                'currency' => 'USD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_ar' => 'الحسابات البنكية',
                'name_en' => 'Bank Accounts',
                'account_type' => 'asset',
                'code' => '1020',
                'description_ar' => 'الحسابات البنكية للشركة',
                'description_en' => 'Company bank accounts',
                'currency' => 'USD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_ar' => 'المخزون',
                'name_en' => 'Inventory',
                'account_type' => 'asset',
                'code' => '1100',
                'description_ar' => 'قيمة المخزون المتوفر',
                'description_en' => 'Value of available inventory',
                'currency' => 'USD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_ar' => 'الحسابات المدينة',
                'name_en' => 'Accounts Receivable',
                'account_type' => 'asset',
                'code' => '1200',
                'description_ar' => 'الديون المستحقة للعملاء',
                'description_en' => 'Amounts due from customers',
                'currency' => 'USD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Liabilities
            [
                'name_ar' => 'الحسابات الدائنة',
                'name_en' => 'Accounts Payable',
                'account_type' => 'liability',
                'code' => '2000',
                'description_ar' => 'الديون المستحقة للموردين',
                'description_en' => 'Amounts owed to suppliers',
                'currency' => 'USD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_ar' => 'الضرائب المستحقة',
                'name_en' => 'Taxes Payable',
                'account_type' => 'liability',
                'code' => '2100',
                'description_ar' => 'الضرائب المستحقة الدفع',
                'description_en' => 'Taxes owed to authorities',
                'currency' => 'USD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Equity
            [
                'name_ar' => 'رأس المال',
                'name_en' => 'Capital',
                'account_type' => 'equity',
                'code' => '3000',
                'description_ar' => 'رأس مال الشركة',
                'description_en' => 'Company capital',
                'currency' => 'USD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_ar' => 'الأرباح المحتجزة',
                'name_en' => 'Retained Earnings',
                'account_type' => 'equity',
                'code' => '3100',
                'description_ar' => 'الأرباح المحتجزة',
                'description_en' => 'Retained earnings',
                'currency' => 'USD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Revenue
            [
                'name_ar' => 'الإيرادات التشغيلية',
                'name_en' => 'Operating Revenue',
                'account_type' => 'revenue',
                'code' => '4000',
                'description_ar' => 'الإيرادات من المبيعات',
                'description_en' => 'Revenue from sales',
                'currency' => 'USD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_ar' => 'مبيعات المنتجات',
                'name_en' => 'Product Sales',
                'account_type' => 'revenue',
                'code' => '4010',
                'description_ar' => 'إيرادات من بيع المنتجات',
                'description_en' => 'Revenue from product sales',
                'currency' => 'USD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_ar' => 'إيرادات الخدمات',
                'name_en' => 'Service Revenue',
                'account_type' => 'revenue',
                'code' => '4020',
                'description_ar' => 'إيرادات من الخدمات',
                'description_en' => 'Revenue from services',
                'currency' => 'USD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Expenses
            [
                'name_ar' => 'المصروفات التشغيلية',
                'name_en' => 'Operating Expenses',
                'account_type' => 'expense',
                'code' => '5000',
                'description_ar' => 'المصروفات التشغيلية للشركة',
                'description_en' => 'Company operating expenses',
                'currency' => 'USD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_ar' => 'تكلفة البضاعة المباعة',
                'name_en' => 'Cost of Goods Sold',
                'account_type' => 'expense',
                'code' => '5010',
                'description_ar' => 'تكلفة البضاعة المباعة',
                'description_en' => 'Cost of goods sold',
                'currency' => 'USD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_ar' => 'المصروفات الإدارية',
                'name_en' => 'Administrative Expenses',
                'account_type' => 'expense',
                'code' => '5020',
                'description_ar' => 'المصروفات الإدارية والعمومية',
                'description_en' => 'Administrative and general expenses',
                'currency' => 'USD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_ar' => 'مصروفات البيع والتسويق',
                'name_en' => 'Sales and Marketing Expenses',
                'account_type' => 'expense',
                'code' => '5030',
                'description_ar' => 'مصروفات البيع والتسويق',
                'description_en' => 'Sales and marketing expenses',
                'currency' => 'USD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Account::insert($accounts);
    }
}
