<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cost extends Model
{
    //
    protected $fillable = ['name_ar', 'name_en', 'description_ar', 'description_en', 'cost_price','currency','supply_id', 'cost_type'];
public function supply(): BelongsTo
{
    return $this->belongsTo(Supply::class);
}
    }
