<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer; // Import Customer instead of using User for trade
use App\Models\Jentry;
use App\Services\InvoiceService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // 1. Clean up existing data (Order matters for Foreign Keys)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Customer::truncate();
        Order::truncate();
        Invoice::truncate();
        InvoiceItem::truncate();
        Jentry::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Create Sample Customers (matching your new Schema)
        $customerData = [
            [
                'name_en' => 'Global Trade Corp',
                'name_ar' => 'شركة التجارة العالمية',
                'c_type' => 'wholesale',
                'phone' => '966500000001',
                'email' => 'contact@globaltrade.com',
            ],
            [
                'name_en' => 'Ahmed Al-Saud',
                'name_ar' => 'أحمد السعود',
                'c_type' => 'individual',
                'phone' => '966500000002',
                'email' => 'ahmed@saudi.me',
            ],
            [
                'name_en' => 'Ethio Import Export',
                'name_ar' => 'إثيو للاستيراد والتصدير',
                'c_type' => 'wholesale',
                'phone' => '251911000001',
                'email' => 'info@ethiotrade.et',
            ],
        ];

        foreach ($customerData as $data) {
            Customer::create($data);
        }

        // 3. Get IDs from the Customer table
        $customerIds = Customer::pluck('id')->toArray();
        $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];

        // 4. Create 20 sample orders linked to Customers
        for ($i = 0; $i < 20; $i++) {
            $order = Order::create([
                'product_id' => rand(1, 9), // Ensure you have products in DB
                'customer_id' => $customerIds[array_rand($customerIds)],
                'quantity' => rand(1, 5),
                'total_price' => rand(500, 3000),
                'order_date' => $faker->dateTimeBetween('-3 months', 'now'),
                'status' => $statuses[array_rand($statuses)],
            ]);

            // Convert some orders to invoices via your Service
            if ($i % 3 == 0 && $order->status !== 'cancelled') {
                $invoice = InvoiceService::convertOrderToInvoice($order);
                
                if ($i % 4 == 0) {
                    $invoice->update(['status' => 'paid']);
                }
            }
        }

        // 5. Create sample journal entries
        $journalEntries = [
            [
                'account_id' => 2, // Cash on Hand
                'reference_type' => Invoice::class,
                'reference_id' => 1,
                'debit' => 1500.00,
                'credit' => 0,
                'description_ar' => 'دفعة فاتورة رقم 1',
                'description_en' => 'Payment for invoice #1',
                'currency' => 'USD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'account_id' => 11, // Product Sales
                'reference_type' => Invoice::class,
                'reference_id' => 1,
                'debit' => 0,
                'credit' => 1500.00,
                'description_ar' => 'إيراد بيع فاتورة رقم 1',
                'description_en' => 'Sales revenue for invoice #1',
                'currency' => 'USD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Jentry::insert($journalEntries);
    }
}