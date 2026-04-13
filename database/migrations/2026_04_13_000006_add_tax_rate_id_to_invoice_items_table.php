<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->foreignId('tax_rate_id')->nullable()->after('invoice_id')->constrained('tax_rates')->nullOnDelete();
            $table->index(['tax_rate_id']);
        });
    }

    public function down(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropForeign(['tax_rate_id']);
            $table->dropIndex(['tax_rate_id']);
            $table->dropColumn('tax_rate_id');
        });
    }
};

