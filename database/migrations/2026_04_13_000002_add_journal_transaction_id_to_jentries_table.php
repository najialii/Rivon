<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jentries', function (Blueprint $table) {
            $table->foreignId('journal_transaction_id')
                ->nullable()
                ->after('id')
                ->constrained('journal_transactions')
                ->nullOnDelete();

            $table->index(['journal_transaction_id']);
        });
    }

    public function down(): void
    {
        Schema::table('jentries', function (Blueprint $table) {
            $table->dropForeign(['journal_transaction_id']);
            $table->dropIndex(['journal_transaction_id']);
            $table->dropColumn('journal_transaction_id');
        });
    }
};

