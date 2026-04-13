<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('costs', function (Blueprint $table) {
            $table->foreignId('expense_account_id')->nullable()->after('cost_type')->constrained('accounts')->nullOnDelete();
            $table->index(['expense_account_id']);
        });
    }

    public function down(): void
    {
        Schema::table('costs', function (Blueprint $table) {
            $table->dropForeign(['expense_account_id']);
            $table->dropIndex(['expense_account_id']);
            $table->dropColumn('expense_account_id');
        });
    }
};

