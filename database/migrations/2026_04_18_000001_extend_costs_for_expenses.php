<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Extend the `costs` table to support standalone Expense records.
 *
 * Existing rows are unaffected:
 *  - expense_date  → nullable, defaults to NULL (old rows have no date)
 *  - paid_by_user_id → nullable FK to users (old rows have no payer)
 *  - status        → defaults to 'approved' so old rows stay valid
 *  - is_standalone → false by default; true marks a direct Expense entry
 *                    (as opposed to a cost linked to a product/order_item)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('costs', function (Blueprint $table) {
            // When the expense occurred
            $table->date('expense_date')->nullable()->after('cost_type');

            // Who paid / recorded the expense (staff member)
            $table->foreignId('paid_by_user_id')
                ->nullable()
                ->after('expense_date')
                ->constrained('users')
                ->nullOnDelete();

            // Workflow status for the expense
            $table->string('status')->default('approved')->after('paid_by_user_id');
            // Possible values: 'pending' | 'approved' | 'rejected' | 'paid'

            // Distinguishes a standalone Expense entry from a product/order cost
            $table->boolean('is_standalone')->default(false)->after('status');

            // Optional: receipt or supporting document path
            $table->string('receipt_path')->nullable()->after('is_standalone');

            // Index for common queries
            $table->index(['is_standalone', 'status']);
            $table->index('expense_date');
        });
    }

    public function down(): void
    {
        Schema::table('costs', function (Blueprint $table) {
            $table->dropForeign(['paid_by_user_id']);
            $table->dropIndex(['is_standalone', 'status']);
            $table->dropIndex(['expense_date']);
            $table->dropColumn([
                'expense_date',
                'paid_by_user_id',
                'status',
                'is_standalone',
                'receipt_path',
            ]);
        });
    }
};
