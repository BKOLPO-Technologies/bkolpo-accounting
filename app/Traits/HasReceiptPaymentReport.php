<?php

namespace App\Traits;
use App\Models\Payment;
use App\Models\ProjectReceipt;

trait HasReceiptPaymentReport
{
    public function getReceiptPaymentTransactions($fromDate, $toDate)
    {
        $receipts = ProjectReceipt::
            whereBetween('payment_date', [$fromDate, $toDate])
            ->get()
            ->each(function ($receipt) {
                $receipt->type = 'Receipt';
                $receipt->invoice_no = $receipt->invoice_no ?? '';
                $receipt->total_amount = $receipt->total_amount ?? $receipt->amount ?? 0;
                $receipt->pay_amount = $receipt->pay_amount ?? 0;
                $receipt->due_amount = $receipt->total_amount - $receipt->pay_amount;
            });

            // dd($receipts);

        $payments = Payment::with('ledger')
            ->whereBetween('payment_date', [$fromDate, $toDate])
            ->get()
            ->each(function ($payment) {
                $payment->type = 'Payment';
                $payment->invoice_no = $payment->invoice_no ?? '';
                $payment->total_amount = $payment->total_amount ?? $payment->amount ?? 0;
                $payment->pay_amount = $payment->pay_amount ?? 0;
                $payment->due_amount = $payment->total_amount - $payment->pay_amount;
            });

        return $receipts->merge($payments)->sortBy('payment_date')->values();
    }
}
