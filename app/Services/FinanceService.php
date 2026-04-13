<?php

namespace App\Services;

use App\Models\Account;
use App\Models\AccountingPeriod;
use App\Models\JournalTransaction;
use App\Models\Jentry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FinanceService
{
    public static function postTransaction(
        ?Model $reference,
        ?string $event,
        Carbon $entryDate,
        string $currency,
        string $memoEn,
        string $memoAr,
        array $lines,
        ?int $postedByUserId = null,
        string $status = 'posted'
    ): JournalTransaction {
        return DB::transaction(function () use ($reference, $event, $entryDate, $currency, $memoEn, $memoAr, $lines, $postedByUserId, $status) {
            self::ensurePeriodIsOpen($entryDate);

            if ($reference) {
                $existing = JournalTransaction::query()
                    ->where('reference_type', $reference::class)
                    ->where('reference_id', $reference->getKey())
                    ->where('event', $event)
                    ->first();

                if ($existing) {
                    return $existing;
                }
            }

            $entryNumber = 'JE-' . $entryDate->format('Ymd') . '-' . strtoupper(Str::random(6));

            $transaction = JournalTransaction::create([
                'entry_number' => $entryNumber,
                'entry_date' => $entryDate->toDateString(),
                'currency' => $currency,
                'status' => $status,
                'event' => $event,
                'memo_en' => $memoEn,
                'memo_ar' => $memoAr,
                'reference_type' => $reference?->getMorphClass(),
                'reference_id' => $reference?->getKey(),
                'posted_at' => $status === 'posted' ? now() : null,
                'posted_by' => $status === 'posted' ? $postedByUserId : null,
            ]);

            $totalDebit = 0.0;
            $totalCredit = 0.0;

            foreach ($lines as $line) {
                $accountId = $line['account_id'] ?? null;
                $accountCode = $line['account_code'] ?? null;

                if (!$accountId && $accountCode) {
                    $accountId = self::getAccountIdByCode($accountCode);
                }

                $debit = (float) ($line['debit'] ?? 0);
                $credit = (float) ($line['credit'] ?? 0);

                if ($debit < 0 || $credit < 0 || ($debit > 0 && $credit > 0) || ($debit === 0.0 && $credit === 0.0)) {
                    throw new \InvalidArgumentException('Invalid journal line amounts.');
                }

                $totalDebit += $debit;
                $totalCredit += $credit;

                Jentry::create([
                    'journal_transaction_id' => $transaction->id,
                    'account_id' => $accountId,
                    'reference_type' => $reference ? get_class($reference) : 'manual',
                    'reference_id' => $reference?->getKey() ?? 0,
                    'debit' => $debit,
                    'credit' => $credit,
                    'currency' => $currency,
                    'description_en' => $line['description_en'] ?? $memoEn,
                    'description_ar' => $line['description_ar'] ?? $memoAr,
                ]);
            }

            if (round($totalDebit, 2) !== round($totalCredit, 2)) {
                throw new \InvalidArgumentException('Unbalanced journal transaction.');
            }

            return $transaction;
        });
    }

    public static function processTransaction(
        Model $reference,
        int $debitAccountId,
        int $creditAccountId,
        float $amount,
        string $descEn,
        string $descAr,
        string $currency = 'USD'
    ): JournalTransaction {
        return self::postTransaction(
            reference: $reference,
            event: null,
            entryDate: now(),
            currency: $currency,
            memoEn: $descEn,
            memoAr: $descAr,
            lines: [
                [
                    'account_id' => $debitAccountId,
                    'debit' => $amount,
                    'credit' => 0,
                    'description_en' => $descEn,
                    'description_ar' => $descAr,
                ],
                [
                    'account_id' => $creditAccountId,
                    'debit' => 0,
                    'credit' => $amount,
                    'description_en' => $descEn,
                    'description_ar' => $descAr,
                ],
            ],
        );
    }

    public static function getAccountIdByCode(string $code): int
    {
        $accountId = Account::query()->where('code', $code)->value('id');

        if (!$accountId) {
            throw new \RuntimeException("Missing account code: {$code}");
        }

        return (int) $accountId;
    }

    public static function reverseTransaction(JournalTransaction $transaction, ?int $reversedByUserId = null): JournalTransaction
    {
        return DB::transaction(function () use ($transaction, $reversedByUserId) {
            $transaction->loadMissing('lines');
            self::ensurePeriodIsOpen(now());

            $entryNumber = 'JE-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

            $reversal = JournalTransaction::create([
                'entry_number' => $entryNumber,
                'entry_date' => now()->toDateString(),
                'currency' => $transaction->currency,
                'status' => 'posted',
                'event' => 'reversal',
                'memo_en' => "Reversal of {$transaction->entry_number}",
                'memo_ar' => "عكس قيد {$transaction->entry_number}",
                'posted_at' => now(),
                'posted_by' => $reversedByUserId,
                'reversal_of_id' => $transaction->id,
            ]);

            foreach ($transaction->lines as $line) {
                Jentry::create([
                    'journal_transaction_id' => $reversal->id,
                    'account_id' => $line->account_id,
                    'reference_type' => 'reversal',
                    'reference_id' => $transaction->id,
                    'debit' => (float) $line->credit,
                    'credit' => (float) $line->debit,
                    'currency' => $transaction->currency,
                    'description_en' => $line->description_en,
                    'description_ar' => $line->description_ar,
                ]);
            }

            return $reversal;
        });
    }

    public static function ensurePeriodIsOpen(Carbon $date): void
    {
        $closed = AccountingPeriod::query()
            ->where('is_closed', true)
            ->whereDate('start_date', '<=', $date->toDateString())
            ->whereDate('end_date', '>=', $date->toDateString())
            ->exists();

        if ($closed) {
            throw new \RuntimeException('Accounting period is closed.');
        }
    }
}
