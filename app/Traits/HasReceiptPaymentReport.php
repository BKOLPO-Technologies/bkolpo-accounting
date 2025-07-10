<?php

namespace App\Traits;
use App\Models\Payment;
use App\Models\ProjectReceipt;

trait HasReceiptPaymentReport
{
    public function getReceiptPaymentTransactions($fromDate, $toDate)
    {
        $receipts = ProjectReceipt::whereBetween('payment_date', [$fromDate, $toDate])
            ->get()
            ->map(function ($receipt) {
                return (object)[
                    'client' => $receipt->client->name,
                    'type' => 'Receipt',
                    'payment_date' => $receipt->payment_date,
                    'invoice_no' => $receipt->invoice_no ?? '',
                    'total_amount' => $receipt->total_amount ?? $receipt->amount ?? 0,
                    'pay_amount' => $receipt->pay_amount ?? 0,
                    'due_amount' => ($receipt->total_amount ?? $receipt->amount ?? 0) - ($receipt->pay_amount ?? 0),
                ];
            });

        $payments = Payment::whereBetween('payment_date', [$fromDate, $toDate])
            ->get()
            ->map(function ($payment) {
                return (object)[
                    'client' => $payment->supplier->name,
                    'type' => 'Payment',
                    'payment_date' => $payment->payment_date,
                    'invoice_no' => $payment->invoice_no ?? '',
                    'total_amount' => $payment->total_amount ?? $payment->amount ?? 0,
                    'pay_amount' => $payment->pay_amount ?? 0,
                    'due_amount' => ($payment->total_amount ?? $payment->amount ?? 0) - ($payment->pay_amount ?? 0),
                ];
            });

        $transactions = $receipts->merge($payments)->sortBy('payment_date')->values();
        return $transactions;
        // dd($transactions);

    }
}
