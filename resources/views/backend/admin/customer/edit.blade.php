@extends('layouts.admin', ['pageTitle' => 'Edit Customer'])

@section('admin')

<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-6">
                    <!-- Placeholder for heading -->
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: black;">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.customer.index') }}" style="text-decoration: none; color: black;">Customer</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
    <form method="POST" id="data_form" class="form-horizontal" action="{{ route('admin.customer.update', $customer->id) }}">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Billing Address -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Billing Address</h5>
                    </div>
                    <div class="card-body">
                        @foreach ([
                            ['name', 'Name', 'Name', true],
                            ['company', 'Company', 'Company', false],
                            ['phone', 'Phone', 'Phone', true],
                            ['email', 'Email', 'Email', true],
                            ['address', 'Address', 'Address', true],
                            ['city', 'City', 'City', false],
                            ['region', 'Region', 'Region', false],
                            ['country', 'Country', 'Country', false],
                            ['postbox', 'PostBox', 'PostBox', false],
                            ['taxid', 'TAX ID', 'TAX ID', false],
                        ] as [$id, $label, $placeholder, $required])
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="{{ $id }}">{{ $label }}</label>
                                <div class="col-sm-9">
                                    <input type="text" 
                                        placeholder="{{ $placeholder }}" 
                                        class="form-control {{ $required ? 'required' : '' }}" 
                                        name="{{ $id }}" 
                                        id="mcustomer_{{ $id }}" 
                                        value="{{ old($id, $customer->$id) }}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Shipping Address</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <div class="form-check">
                                    <input type="checkbox" 
                                        class="form-check-input" 
                                        id="copy_address" 
                                        name="same_as_billing"
                                        {{ old('same_as_billing', $customer->same_as_billing) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="copy_address">Same As Billing</label>
                                </div>
                                <small>Please leave the Shipping Address blank if you do not want to print it on the invoice.</small>
                            </div>
                        </div>

                        @foreach ([
                            ['name_s', 'Name', 'Name'],
                            ['phone_s', 'Phone', 'Phone'],
                            ['email_s', 'Email', 'Email'],
                            ['address_s', 'Address', 'Address'],
                            ['city_s', 'City', 'City'],
                            ['region_s', 'Region', 'Region'],
                            ['country_s', 'Country', 'Country'],
                            ['postbox_s', 'PostBox', 'PostBox'],
                        ] as [$id, $label, $placeholder])
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="{{ $id }}">{{ $label }}</label>
                                <div class="col-sm-9">
                                    <input type="text" 
                                        placeholder="{{ $placeholder }}" 
                                        class="form-control" 
                                        name="{{ $id }}" 
                                        id="mcustomer_{{ $id }}" 
                                        value="{{ old($id, $customer->$id) }}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="row mt-3">
            <div class="col-sm-12">
                <button type="submit" id="submit-data" class="btn btn-success">Update Customer</button>
                <a href="{{ route('admin.customer.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>

    </section>
</div>

@endsection
