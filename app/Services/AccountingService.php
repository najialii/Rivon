<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Cost;
use App\Models\Invoice;
use App\Models\Salary;

class AccountingService
{
    public static function postInvoiceIssue(Invoice $invoice): void
    {
        $invoice->loadMissing('items');

        if ($invoice->items->isEmpty()) {
            return;
        }

        $subtotal = (float) $invoice->subtotal;
        $taxTotal = (float) $invoice->tax_total;
        $total = (float) $invoice->total_amount;

        if ($total <= 0) {
            return;
        }

        $lines = [
            [
                'account_code' => '1200',
                'debit' => $total,
                'credit' => 0,
                'description_en' => "Invoice {$invoice->invoice_number} issued",
                'description_ar' => "إصدار فاتورة {$invoice->invoice_number}",
            ],
            [
                'account_code' => '4010',
                'debit' => 0,
                'credit' => $subtotal > 0 ? $subtotal : $total,
                'description_en' => "Sales revenue for invoice {$invoice->invoice_number}",
                'description_ar' => "إيراد مبيعات للفاتورة {$invoice->invoice_number}",
            ],
        ];

        if ($taxTotal > 0) {
            $lines[] = [
                'account_code' => '2100',
                'debit' => 0,
                'credit' => $taxTotal,
                'description_en' => "Tax for invoice {$invoice->invoice_number}",
                'description_ar' => "ضريبة للفاتورة {$invoice->invoice_number}",
            ];
        }

        FinanceService::postTransaction(
            reference: $invoice,
            event: 'invoice_issued',
            entryDate: $invoice->issue_date?->copy() ?? now(),
            currency: $invoice->currency,
            memoEn: "Invoice {$invoice->invoice_number} issued",
            memoAr: "إصدار فاتورة {$invoice->invoice_number}",
            lines: $lines,
        );
    }

    public static function postInvoicePayment(Invoice $invoice): void
    {
        $total = (float) $invoice->total_amount;

        if ($total <= 0) {
            return;
        }

        FinanceService::postTransaction(
            reference: $invoice,
            event: 'invoice_paid',
            entryDate: now(),
            currency: $invoice->currency,
            memoEn: "Invoice {$invoice->invoice_number} paid",
            memoAr: "سداد فاتورة {$invoice->invoice_number}",
            lines: [
                [
                    'account_code' => '1010',
                    'debit' => $total,
                    'credit' => 0,
                    'description_en' => "Cash received for invoice {$invoice->invoice_number}",
                    'description_ar' => "استلام نقدية للفاتورة {$invoice->invoice_number}",
                ],
                [
                    'account_code' => '1200',
                    'debit' => 0,
                    'credit' => $total,
                    'description_en' => "A/R cleared for invoice {$invoice->invoice_number}",
                    'description_ar' => "تسوية ذمم مدينة للفاتورة {$invoice->invoice_number}",
                ],
            ],
        );
    }

    public static function postSalaryPayment(Salary $salary): void
    {
        $amount = (float) $salary->amount;

        if ($amount <= 0) {
            return;
        }

        FinanceService::postTransaction(
            reference: $salary,
            event: 'salary_paid',
            entryDate: $salary->created_at?->copy() ?? now(),
            currency: $salary->currency,
            memoEn: "Salary payment for Employee #{$salary->employee_id}",
            memoAr: "صرف راتب للموظف رقم: {$salary->employee_id}",
            lines: [
                [
                    'account_code' => '5040',
                    'debit' => $amount,
                    'credit' => 0,
                    'description_en' => "Salary expense for Employee #{$salary->employee_id}",
                    'description_ar' => "مصروف راتب للموظف رقم: {$salary->employee_id}",
                ],
                [
                    'account_code' => '1010',
                    'debit' => 0,
                    'credit' => $amount,
                    'description_en' => "Cash paid for Employee #{$salary->employee_id}",
                    'description_ar' => "دفع نقدي للموظف رقم: {$salary->employee_id}",
                ],
            ],
        );
    }

    public static function postCost(Cost $cost): void
    {
        $amount = (float) $cost->cost_price;

        if ($amount <= 0) {
            return;
        }

        $expenseAccountCode = $cost->expense_account_id
            ? Account::query()->whereKey($cost->expense_account_id)->value('code')
            : self::mapCostTypeToExpenseAccountCode((string) $cost->cost_type);

        FinanceService::postTransaction(
            reference: $cost,
            event: 'cost_recorded',
            entryDate: $cost->created_at?->copy() ?? now(),
            currency: $cost->currency,
            memoEn: "Cost recorded: {$cost->name_en}",
            memoAr: "تسجيل مصروف: {$cost->name_ar}",
            lines: [
                [
                    'account_code' => $expenseAccountCode,
                    'debit' => $amount,
                    'credit' => 0,
                    'description_en' => $cost->description_en ?? $cost->name_en,
                    'description_ar' => $cost->description_ar ?? $cost->name_ar,
                ],
                [
                    'account_code' => '1010',
                    'debit' => 0,
                    'credit' => $amount,
                    'description_en' => "Cash paid for cost: {$cost->name_en}",
                    'description_ar' => "دفع نقدي للمصروف: {$cost->name_ar}",
                ],
            ],
        );
    }

    protected static function mapCostTypeToExpenseAccountCode(string $costType): string
    {
        return match ($costType) {
            'marketing' => '5030',
            default => '5020',
        };
    }
}

