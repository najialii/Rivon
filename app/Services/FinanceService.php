<?php

namespace App\Services;

use App\Models\J_entries;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FinanceService
{
    /**
     * Creates a balanced double-entry transaction automatically.
     */
    public static function processTransaction(
        Model $reference, 
        int $debitAccountId, 
        int $creditAccountId, 
        float $amount, 
        string $descEn, 
        string $descAr, 
        string $currency = 'USD'
    ) {
        // Use a DB transaction to ensure both entries save, or neither do.
        DB::transaction(function () use ($reference, $debitAccountId, $creditAccountId, $amount, $descEn, $descAr, $currency) {
            
            // 1. The Debit Entry (Money going INTO an account/asset)
            J_entries::create([
                'account_id'     => $debitAccountId,
                'reference_type' => get_class($reference),
                'reference_id'   => $reference->id,
                'debit'          => $amount,
                'credit'         => 0,
                'currancy'       => $currency,
                'description_en' => $descEn,
                'description_ar' => $descAr,
            ]);

            // 2. The Credit Entry (Money coming OUT of an account/liability)
            J_entries::create([
                'account_id'     => $creditAccountId,
                'reference_type' => get_class($reference),
                'reference_id'   => $reference->id,
                'debit'          => 0,
                'credit'         => $amount,
                'currancy'       => $currency,
                'description_en' => $descEn,
                'description_ar' => $descAr,
            ]);
        });
    }
}