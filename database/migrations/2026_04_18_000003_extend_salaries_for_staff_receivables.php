<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Extend the `salaries` table to support Staff Receivable records.
 *
 * Design decisions:
 *  - transaction_type → 'salary' (default) keeps all existing rows valid.
 *                       'receivable' = money owed BY the staff member to the company.
 *                       'advance'    = salary advance given to the staff member.
 *                       'collection' = money collected BY staff on behalf of the company.
 *  - status           → 'paid' (default) keeps existing salary rows valid.
 *                       'pending'  = not yet settled.
 *                       'partial'  = partially settled.
 *                       'settled'  = fully settled.
 *                       'cancelled'= voided.
 *  - due_date         → nullable; when the receivable/advance is due.
 *  - amount_settled   → nullable decimal; how much has been recovered so far.
 *  - notes            → nullable text for additional context.
 *  - receivable_account_id → nullable FK to accounts; the GL account to debit/credit.
 *
 * Existing rows:
 *  - transaction_type = 'salary'  (default)
 *  - status           = 'paid'    (default — salaries are paid immediately)
 *  - all new columns  = NULL / default
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salaries', function (Blueprint $table) {
            // Discriminator column
            $table->string('transaction_type')->default('salary')->after('currency');
            // Values: 'salary' | 'receivable' | 'advance' | 'collection'

            // Lifecycle status
            $table->string('status')->default('paid')->after('transaction_type');
            // Values: 'paid' | 'pending' | 'partial' | 'settled' | 'cancelled'

            // When the receivable / advance is due
            $table->date('due_date')->nullable()->after('status');

            // Running total of how much has been recovered / settled
            $table->decimal('amount_settled', 15, 2)->nullable()->after('due_date');

            // Free-text notes / reason
            $table->text('notes')->nullable()->after('amount_settled');

            // GL account for the receivable (e.g. "Staff Advances" or "Staff Receivables")
            $table->foreignId('receivable_account_id')
                ->nullable()
                ->after('notes')
                ->constrained('accounts')
                ->nullOnDelete();

            // Indexes
            $table->index('transaction_type');
            $table->index(['transaction_type', 'status']);
            $table->index('due_date');
        });
    }

    public function down(): void
    {
        Schema::table('salaries', function (Blueprint $table) {
            $table->dropForeign(['receivable_account_id']);
            $table->dropIndex(['transaction_type']);
            $table->dropIndex(['salaries_transaction_type_status_index']);
            $table->dropIndex(['due_date']);
            $table->dropColumn([
                'transaction_type',
                'status',
                'due_date',
                'amount_settled',
                'notes',
                'receivable_account_id',
            ]);
        });
    }
};
