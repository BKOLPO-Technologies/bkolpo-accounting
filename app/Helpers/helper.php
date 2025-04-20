<?php

use Rakibhstu\Banglanumber\NumberToBangla;

use App\Models\JournalVoucherDetail;
use App\Models\Company;
use App\Models\CompanyInformation;

function journalvoucher()
{
    return JournalVoucherDetail::query();
}


function zero($zero)
{
    $value = 6 - strlen($zero);
    if ($value == 5) {
        return '00000';
    } elseif ($value == 4) {
        return '0000';
    } elseif ($value == 3) {
        return '000';
    } elseif ($value == 2) {
        return '00';
    } elseif ($value == 1) {
        return '0';
    }
}

function number2word($number)
{
    $lang = 'en';

    if ($lang == 'en') {
        return Terbilang::make($number) . ' Taka Only';
    }
    if ($lang == 'bn') {
        $numto = new NumberToBangla();
        return $numto->bnMoney($number) . ' মাত্র';
    }
}

function convertNumberToWords($number)
{
    $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
    return ucfirst($f->format($number)) . ' only';
}

function en2bn($number)
{
    $lang = 'en';

    if ($lang == 'en') {
        return number_format($number, 2);
    }
    if ($lang == 'bn') {
        $numto = new NumberToBangla();
        return $numto->bnCommaLakh($number);
    }
}


// if (!function_exists('bdt')) {
//     function bdt()
//     {
//         // return 'BDT'; // Taka symbol
//         $companyInfo = get_company_info();
//         return $companyInfo && $companyInfo->currency_symbol ? $companyInfo->currency_symbol : 'BDT'; 
//     }
// }

if (!function_exists('bdt')) {
    function bdt()
    {
        // return 'BDT'; // Taka symbol
        $companyInfo = get_company();
        return $companyInfo && $companyInfo->currency_symbol ? $companyInfo->currency_symbol : 'BDT'; 
    }
}


if (!function_exists('get_company')) {
    function get_company()
    {
        return Company::first();
    }
}


if (!function_exists('get_company_info')) {
    function get_company_info()
    {
        return CompanyInformation::first();
    }
}




