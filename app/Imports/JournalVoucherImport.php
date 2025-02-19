<?php

namespace App\Imports;

use App\Models\JournalVoucher;
use App\Models\JournalVoucherDetail;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Validation\ValidationException as LaravelValidationException;

class JournalVoucherImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $header = $rows->shift(); // Remove header row
    
        $voucherMap = []; // Store created vouchers to avoid duplicates
        $totalDebit = 0;  // Initialize total debit for all rows
        $totalCredit = 0; // Initialize total credit for all rows
    
        foreach ($rows as $row) {
            $companyId   = $row[0] ?? null;
            $branchId    = $row[1] ?? null;
            $excelDate   = $row[2] ?? null;
            $ledgerId    = $row[3] ?? null;
            $referenceNo = $row[4] ?? null;
            $description = $row[5] ?? null;
            $debit       = $row[6] ?? 0;
            $credit      = $row[7] ?? 0;
    
            // ✅ Handle Excel Date Conversion
            if (is_numeric($excelDate)) {
                $date = Carbon::instance(Date::excelToDateTimeObject($excelDate))->format('Y-m-d');
            } else {
                $date = Carbon::parse($excelDate)->format('Y-m-d');
            }
    
            // Add to total debit and credit
            $totalDebit += $debit;
            $totalCredit += $credit;
    
            // ✅ Ensure unique voucher number
            if (!isset($voucherMap[$companyId][$branchId][$date])) {
                $voucherNo = 'BKOLPO-' . mt_rand(100000, 999999);
                $voucher = JournalVoucher::firstOrCreate([
                    'transaction_code' => $voucherNo, 
                    'company_id'       => $companyId,
                    'branch_id'        => $branchId,
                    'transaction_date' => $date,
                    'status'           => 0 // Status 0 => Draft Voucher
                ]);
                $voucherMap[$companyId][$branchId][$date] = $voucher->id;
            }
    
            // Insert Journal Entry Details
            JournalVoucherDetail::create([
                'journal_voucher_id' => $voucherMap[$companyId][$branchId][$date],
                'ledger_id'          => $ledgerId,
                'reference_no'       => $referenceNo,
                'description'        => $description,
                'debit'              => $debit,
                'credit'             => $credit,
            ]);
        }
    
        // ✅ After looping through all rows, check if total debit equals total credit
        if ($totalDebit !== $totalCredit) {
            $difference = abs($totalDebit - $totalCredit); // Calculate the mismatch amount
    
            // Throw a validation error with detailed message
            throw LaravelValidationException::withMessages([
                'error' => "Mismatch in total debit and credit amounts. Total Debit: {$totalDebit}, Total Credit: {$totalCredit}, Difference: {$difference}.",
            ]);
        }
    }
}
