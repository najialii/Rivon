<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Jentry extends Model
{
    //
   protected $fillable = [
            'journal_transaction_id',
            'account_id', 
            'reference_type', 
            'reference_id', 
            'credit', 
            'debit', 
            'description_ar', 
            'description_en', 
            'currency'
        ];


           public function account(): BelongsTo
        {
            return $this->belongsTo(Account::class);
        }

        public function transaction(): BelongsTo
        {
            return $this->belongsTo(JournalTransaction::class, 'journal_transaction_id');
        }

        public function reference()
        {
            return $this->morphTo();
        }


}
