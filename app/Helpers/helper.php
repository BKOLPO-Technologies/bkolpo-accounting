<?php

use Rakibhstu\Banglanumber\NumberToBangla;

use App\Models\JournalVoucherDetail;

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


if (!function_exists('bdt')) {
    function bdt()
    {
        return 'BDT'; // Taka symbol
    }
}

