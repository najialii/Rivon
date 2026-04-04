<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create Invoices Table
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade'); // Add order relationship
            $table->date('issue_date');
            $table->date('due_date')->nullable();
            
            // Financial Columns (Essential for Rivon Accounting)
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_total', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('tax_percentage', 5, 2)->default(15.00);
            
            $table->string('currency')->default('USD');
            $table->string('status')->default('draft'); // draft, sent, paid, cancelled
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 2. Create Invoice Items Table
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->string('description');
            $table->decimal('quantity', 12, 2)->default(1);
            $table->decimal('unit_price', 15, 2);
            $table->decimal('subtotal', 15, 2); // Qty * Price
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
    }
};