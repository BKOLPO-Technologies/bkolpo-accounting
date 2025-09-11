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

// function convertNumberToWords($number)
// {
//     $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
//     return ucfirst($f->format($number)) . ' only';
// }

function convertNumberToWords($number)
{
    $number = (float) preg_replace('/[^\d.]/', '', $number); 
    $number = number_format($number, 2, '.', '');
    $parts = explode('.', $number);
    $intPart = (int)$parts[0];
    $decimalPart = isset($parts[1]) ? (int)$parts[1] : 0;

    $crore = floor($intPart / 10000000);
    $lakh = floor(($intPart % 10000000) / 100000);
    $thousand = floor(($intPart % 100000) / 1000);
    $hundred = floor(($intPart % 1000) / 100);
    $rest = $intPart % 100;

    $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
    $words = [];

    if ($crore) {
        $words[] = ucfirst($f->format($crore)) . ' crore';
    }
    if ($lakh) {
        $words[] = $f->format($lakh) . ' lac';
    }
    if ($thousand) {
        $words[] = $f->format($thousand) . ' thousand';
    }
    if ($hundred) {
        $words[] = $f->format($hundred) . ' hundred';
    }
    if ($rest) {
        $words[] = $f->format($rest);
    }

    $taka = implode(' ', $words) . ' taka';

    if ($decimalPart > 0) {
        $poisha = $f->format($decimalPart) . ' poisha';
        $taka .= ' and ' . $poisha;
    }

    return ucwords($taka . ' only.');
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




