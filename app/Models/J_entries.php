<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class J_entries extends Model
{
    //
protected $fillable = [
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

    public function reference()
    {
        return $this->morphTo();
    }
    
        }
