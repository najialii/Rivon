<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journal_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('entry_number')->unique();
            $table->date('entry_date');
            $table->string('currency');
            $table->string('status')->default('posted');
            $table->string('event')->nullable();
            $table->text('memo_en')->nullable();
            $table->text('memo_ar')->nullable();

            $table->nullableMorphs('reference');

            $table->timestamp('posted_at')->nullable();
            $table->foreignId('posted_by')->nullable()->constrained('users')->nullOnDelete();

            $table->foreignId('reversal_of_id')->nullable()->constrained('journal_transactions')->nullOnDelete();
            $table->timestamp('voided_at')->nullable();
            $table->foreignId('voided_by')->nullable()->constrained('users')->nullOnDelete();

            $table->unique(['reference_type', 'reference_id', 'event']);
            $table->index(['entry_date', 'status']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_transactions');
    }
};

