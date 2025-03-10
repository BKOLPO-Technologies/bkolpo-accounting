@extends('layouts.admin')
@section('admin')


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!-- <h1>DataTables</h1> -->
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: black;">Home</a></li>
                        <li class="breadcrumb-item"><a href="#" style="text-decoration: none; color: black;">Bank Cash Manage</a></li>
                        <li class="breadcrumb-item active">All</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>


    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- <div class="card-header">
                        <h3 class="card-title">DataTable with minimal features & hover style</h3>
                    </div> -->
                    <!-- /.card-header -->
                    <div class="card-body">


                        <!-- ------------------------------------------------------------------------------------------------------------- -->
                        <!-- Invoice Company Details -->
                        <div id="invoice-company-details" class="row mt-2">
                            <div class="col-md-6 col-sm-12 text-xs-center text-md-left">
                                <p></p>
                                <img src="https://accounts.bkolpo.com/userfiles/company/17368344351733151568.png"
                                    class="img-responsive p-1 m-b-2" style="max-height: 120px;">

                            </div>
                            <div class="col-md-6 col-sm-12 text-xs-center text-md-right">
                                <h2>INVOICE</h2>
                                <p class="pb-1"> SRN #1001</p>
                                <p class="pb-1">Reference:</p>
                                <ul class="px-0 list-unstyled">
                                    <li>Gross Amount</li>
                                    <li class="lead text-bold-800">৳ 193,800.00</li>
                                </ul>
                            </div>
                        </div>
                        <!--/ Invoice Company Details -->

                        <!-- Invoice Customer Details -->
                        <div id="invoice-customer-details" class="row">
                            <div class="col-sm-12 text-xs-center text-md-left">
                                <p class="text-muted"> Bill To</p>
                            </div>
                            <div class="col-md-6 col-sm-12 text-xs-center text-md-left">
                                <ul class="px-0 list-unstyled">


                                    <li class="text-bold-800"><a
                                            href="https://accounts.bkolpo.com/customers/view?id=4"><strong
                                                class="invoice_a">BKOLPO CONSTRUCTION LIMITED</strong></a></li>
                                    <li>BKOLPO CONSTRUCTION LIMITED</li>
                                    <li>Tokyo Tower (6th Floor),Cheragali, Tongi, Dhaka,</li>
                                    <li>Bangladesh,</li>
                                    <li> Phone: 1110002474</li>
                                    <li> Email: bkolpo@gmail.com </li>
                                </ul>

                            </div>
                            <div class="offset-md-3 col-md-3 col-sm-12 text-xs-center text-md-left">
                                <p><span class="text-muted">Invoice Date :</span> 19-01-2025</p>
                                <p><span class="text-muted">Due Date :</span> 19-01-2025</p>
                                <p><span class="text-muted">Terms :</span> Payment on receipt</p>
                            </div>
                        </div>
                        <!--/ Invoice Customer Details -->

                        <!-- Invoice Items Details -->
                        <div id="invoice-items-details" class="pt-2">
                            <div class="row">
                                <div class="table-responsive col-sm-12">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Description</th>
                                                <th class="text-xs-left">Rate</th>
                                                <th class="text-xs-left">Qty</th>
                                                <th class="text-xs-left">Tax</th>
                                                <th class="text-xs-left"> Discount</th>
                                                <th class="text-xs-left">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th scope="row">1</th>
                                                <td>Rebar 500DWR 10mm</td>

                                                <td>৳ 92,000.00</td>
                                                <td>1</td>
                                                <td>৳ 1,840.00 (2.00%)</td>
                                                <td>৳ 0.00 (0.00%)</td>
                                                <td>৳ 93,840.00</td>
                                            </tr>
                                            <tr>
                                                <td colspan=5>123</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">2</th>
                                                <td>Rebar 500DWR 16mm</td>

                                                <td>৳ 98,000.00</td>
                                                <td>1</td>
                                                <td>৳ 1,960.00 (2.00%)</td>
                                                <td>৳ 0.00 (0.00%)</td>
                                                <td>৳ 99,960.00</td>
                                            </tr>
                                            <tr>
                                                <td colspan=5>This is Rebar 500DWR 16mm</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <p></p>
                            <div class="row">
                                <div class="col-md-7 col-sm-12 text-xs-center text-md-left">


                                    <div class="row">
                                        <div class="col-md-8">
                                            <p
                                                class="lead">Payment Status:
                                                <u><strong
                                                        id="pstatus">Due</strong></u>
                                            </p>
                                            <p class="lead">Payment Method: <u><strong
                                                        id="pmethod"></strong></u>
                                            </p>

                                            <p class="lead mt-1"><br>Note:</p>
                                            <code>
                                            </code>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 col-sm-12">
                                    <p class="lead">Summary</p>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td>Sub Total</td>
                                                    <td class="text-xs-right"> ৳ 190,000.00</td>
                                                </tr>
                                                <tr>
                                                    <td>Tax</td>
                                                    <td class="text-xs-right">৳ 3,800.00</td>
                                                </tr>
                                                <tr>
                                                    <td> Discount</td>
                                                    <td class="text-xs-right">৳ 0.00</td>
                                                </tr>
                                                <tr>
                                                    <td> Shipping</td>
                                                    <td class="text-xs-right">৳ 0.00</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-bold-800">Total</td>
                                                    <td class="text-bold-800 text-xs-right"> ৳ 193,800.00</td>
                                                </tr>
                                                <tr>
                                                    <td>Payment Made</td>
                                                    <td class="pink text-xs-right">
                                                        (-) <span id="paymade">৳ 0.00</span></td>
                                                </tr>
                                                <tr class="bg-grey bg-lighten-4">
                                                    <td class="text-bold-800">Balance Due</td>
                                                    <td class="text-bold-800 text-xs-right"> <span id="paydue">৳ 193,800.00</span></strong></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-xs-center">
                                        <p>Authorized person</p>
                                        <img src="/var/www/html/accounting/userfiles/employee_sign/sign.png" alt="signature" class="height-100" />
                                        <h6>(BusinessOwner)</h6>
                                        <p class="text-muted">Business Owner</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Footer -->

                        <div id="invoice-footer">
                            <p class="lead">Credit Transactions:</p>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Method</th>
                                        <th>Amount</th>
                                        <th>Note</th>


                                    </tr>
                                </thead>
                                <tbody id="activity">

                                </tbody>
                            </table>

                            <div class="row">

                                <div class="col-md-7 col-sm-12">

                                    <h6>Terms & Condition</h6>
                                    <p> <strong>Payment on receipt</strong><br>Payment due on receipt</p>
                                </div>

                            </div>

                        </div>
                        <!--/ Invoice Footer -->
                        <hr>
                        <pre>Public Access URL: https://accounts.bkolpo.com/billing/view?id=1001&token=9984a2f8ff5fb2f9704db3fefd2984e07c0be195</pre>

                        <div class="row">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Files</th>


                                    </tr>
                                </thead>
                                <tbody id="activity">

                                </tbody>
                            </table>
                            <!-- The fileinput-button span is used to style the file input field as button -->
                            <span class="btn btn-success fileinput-button">
                                <i class="glyphicon glyphicon-plus"></i>
                                <span>Select files...</span>
                                <!-- The file input field used as target for the file upload widget -->
                                <input id="fileupload" type="file" name="files[]" multiple>
                            </span>
                            <br>
                            <pre>Allowed: gif, jpeg, png, docx, docs, txt, pdf, xls </pre>
                            <br>
                            <!-- The global progress bar -->
                            <div id="progress" class="progress">
                                <div class="progress-bar progress-bar-success"></div>
                            </div>
                            <!-- The container for the uploaded files -->
                            <table id="files" class="files"></table>
                            <br>
                        </div>
                        <!-- ----------------------------------------------------------------------------------------------------------------------------------------------------- -->

                        
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

    </section>
    <!-- /.content -->


</div>
<!-- /.content-wrapper -->

@endsection