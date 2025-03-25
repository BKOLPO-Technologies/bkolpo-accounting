<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\CompanyInformation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $company = CompanyInformation::first();

        config([
            'company.name' => $company->company_name ?? 'Bkolpo Construction Group',
            'company.branch' => $company->company_branch ?? 'Tongi,Gazipur',
            'company.type' => $company->company_type ?? 'Construction Company',

            'company.logo' => $company->logo ?? '',
            'company.banner' => $company->banner ?? '',
            'company.invoice_header' => $company->invoice_header ?? '',

            'company.country' => $company->country ?? 'Bangladesh (বাংলাদেশ)',
            'company.address' => $company->address ?? 'Tokyo Tower(6th Floor) CeragAli, Tongi, Gazipur-1711, Bangladesh',
            'company.address_optional' => $company->address_optional ?? '',
            'company.email' => $company->email ?? 'info@bkolpo.com.bd',
            'company.phone' => $company->phone ?? '+8801730742238',
            'company.city' => $company->city ?? 'Gazipur',
            'company.state' => $company->state ?? 'Bangladesh',
            'company.post_code' => $company->post_code ?? '1000',

            'company.stock_warning' => $company->stock_warning ?? '10',
            'company.currency_symbol' => $company->currency_symbol ?? '৳',

            'company.sms_api_code' => $company->sms_api_code ?? '',
            'company.sms_sender_id' => $company->sms_sender_id ?? '',
            'company.sms_provider' => $company->sms_provider ?? '',
            'company.sms_setting' => $company->sms_setting ?? '',

            'company.created_by' => $company->created_by ?? '',
            'company.updated_by' => $company->updated_by ?? '',
            'company.deleted_by' => $company->deleted_by ?? '',

            'company.status' => $company->status ?? 1,
        ]);
    }
}
