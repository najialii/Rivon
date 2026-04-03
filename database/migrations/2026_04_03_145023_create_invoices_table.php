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
    // 1. The Main Invoice Table
    Schema::create('invoices', function (Blueprint $table) {
        $table->id();
        $table->string('invoice_number')->unique();
        $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
        $table->date('issue_date');
        $table->date('due_date')->nullable();
        $table->decimal('total_amount', 15, 2)->default(0);
        $table->string('status')->default('draft'); // draft, sent, paid, partial, cancelled
        $table->string('currency')->default('USD');
        $table->text('notes')->nullable();
        $table->timestamps();
    });

    // 2. The Invoice Items (Rows)
    Schema::create('invoice_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
        $table->string('description');
        $table->integer('quantity')->default(1);
        $table->decimal('unit_price', 15, 2);
        $table->decimal('subtotal', 15, 2);
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