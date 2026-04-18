<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Extend the `payments` table to support Deposit records.
 *
 * Design decisions:
 *  - transaction_type  → 'payment' (default) keeps all existing rows valid.
 *                        'deposit' marks a new deposit entry.
 *  - source_type       → NULL for existing payment rows.
 *                        'public' = deposited by a walk-in / public user.
 *                        'staff'  = deposited by an internal staff member.
 *  - deposited_by_user_id → nullable FK to users; only populated for deposits
 *                           made by staff (source_type = 'staff').
 *  - invoice_id / customer_id are made nullable so a deposit can exist
 *    without being tied to a specific invoice or customer.
 *
 * Existing rows:
 *  - transaction_type  = 'payment'  (default)
 *  - source_type       = NULL
 *  - deposited_by_user_id = NULL
 *  - invoice_id / customer_id remain as-is (still populated for old rows)
 */
return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Make invoice_id and customer_id nullable so deposits
        //         don't need to reference an invoice or customer.
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('invoice_id')->nullable()->change();
            $table->foreignId('customer_id')->nullable()->change();
        });

        // Step 2: Add the new columns
        Schema::table('payments', function (Blueprint $table) {
            // Discriminator: 'payment' | 'deposit'
            $table->string('transaction_type')->default('payment')->after('id');

            // Who originated the deposit: 'public' | 'staff' | NULL (for payments)
            $table->string('source_type')->nullable()->after('transaction_type');

            // The staff user who made/recorded the deposit (only for source_type='staff')
            $table->foreignId('deposited_by_user_id')
                ->nullable()
                ->after('source_type')
                ->constrained('users')
                ->nullOnDelete();

            // Optional: the account the deposit was credited to
            $table->foreignId('deposit_account_id')
                ->nullable()
                ->after('deposited_by_user_id')
                ->constrained('accounts')
                ->nullOnDelete();

            // Indexes for common filter queries
            $table->index('transaction_type');
            $table->index(['transaction_type', 'source_type']);
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['deposited_by_user_id']);
            $table->dropForeign(['deposit_account_id']);
            $table->dropIndex(['transaction_type']);
            $table->dropIndex(['payments_transaction_type_source_type_index']);
            $table->dropColumn([
                'transaction_type',
                'source_type',
                'deposited_by_user_id',
                'deposit_account_id',
            ]);

            // Restore NOT NULL constraints
            $table->foreignId('invoice_id')->nullable(false)->change();
            $table->foreignId('customer_id')->nullable(false)->change();
        });
    }
};
