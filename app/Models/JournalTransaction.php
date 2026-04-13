<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class JournalTransaction extends Model
{
    protected $fillable = [
        'entry_number',
        'entry_date',
        'currency',
        'status',
        'event',
        'memo_en',
        'memo_ar',
        'reference_type',
        'reference_id',
        'posted_at',
        'posted_by',
        'reversal_of_id',
        'voided_at',
        'voided_by',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'posted_at' => 'datetime',
        'voided_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::saving(function (self $transaction) {
            if ($transaction->status === 'posted' && !$transaction->posted_at) {
                $transaction->posted_at = now();
            }
        });
    }

    public function lines(): HasMany
    {
        return $this->hasMany(Jentry::class, 'journal_transaction_id');
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    public function reversalOf(): BelongsTo
    {
        return $this->belongsTo(self::class, 'reversal_of_id');
    }

    public function postedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function voidedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'voided_by');
    }

    public function isPosted(): bool
    {
        return $this->status === 'posted' && $this->voided_at === null;
    }
}
