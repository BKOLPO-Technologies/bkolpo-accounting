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
                        <li class="breadcrumb-item"><a href="{{ route('admin.ledgername') }}" style="text-decoration: none; color: black;">Ledger Name Manage</a></li>
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
                    <div class="card-body">

                        <form method="post" id="data_form">

                            <div class="row">

                                <div class="col-sm-6 cmp-pnl">
                                    <div id="customerpanel" class="inner-cmp-pnl">
                                        <div class="form-group row">
                                            <div class="fcol-sm-12">
                                                <h3 class="title">
                                                    Bill To <a href='#'
                                                        class="btn btn-primary btn-sm rounded"
                                                        data-toggle="modal"
                                                        data-target="#addCustomer">
                                                        Add Client </a>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="frmSearch col-sm-12"><label for="cst"
                                                    class="caption">Search Client</label>
                                                <input type="text" class="form-control" name="cst" id="customer-box"
                                                    placeholder="Enter Customer Name or Mobile Number to search"
                                                    autocomplete="off" />

                                                <div id="customer-box-result"></div>
                                            </div>

                                        </div>
                                        <div id="customer">
                                            <div class="clientinfo">
                                                Client Details
                                                <hr>
                                                <input type="hidden" name="customer_id" id="customer_id" value="0">
                                                <div id="customer_name"></div>
                                            </div>
                                            <div class="clientinfo">

                                                <div id="customer_address1"></div>
                                            </div>

                                            <div class="clientinfo">

                                                <div id="customer_phone"></div>
                                            </div>
                                            <hr>
                                            <div id="customer_pass"></div>Warehouse <select
                                                id="warehouses"
                                                class="selectpicker form-control">
                                                <option value="0">All</option>
                                                <option value="1">Main Warehouse</option>
                                            </select>
                                        </div>


                                    </div>
                                </div>
                                <div class="col-sm-6 cmp-pnl">
                                    <div class="inner-cmp-pnl">


                                        <div class="form-group row">

                                            <div class="col-sm-12">
                                                <h3
                                                    class="title">Invoice Properties</h3>
                                            </div>

                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-6"><label for="invocieno"
                                                    class="caption">Invoice Number</label>

                                                <div class="input-group">
                                                    <div class="input-group-addon"><span class="icon-file-text-o"
                                                            aria-hidden="true"></span></div>
                                                    <input type="text" class="form-control" placeholder="Invoice #"
                                                        name="invocieno"
                                                        value="1002">
                                                </div>
                                            </div>
                                            <div class="col-sm-6"><label for="invocieno"
                                                    class="caption">Reference</label>

                                                <div class="input-group">
                                                    <div class="input-group-addon"><span class="icon-bookmark-o"
                                                            aria-hidden="true"></span></div>
                                                    <input type="text" class="form-control" placeholder="Reference #"
                                                        name="refer">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">

                                            <div class="col-sm-6"><label for="invociedate"
                                                    class="caption">Invoice Date</label>

                                                <div class="input-group">
                                                    <div class="input-group-addon"><span class="icon-calendar4"
                                                            aria-hidden="true"></span></div>
                                                    <input type="text" class="form-control required"
                                                        placeholder="Billing Date" name="invoicedate"
                                                        data-toggle="datepicker"
                                                        autocomplete="false">
                                                </div>
                                            </div>
                                            <div class="col-sm-6"><label for="invocieduedate"
                                                    class="caption">Invoice Due Date</label>

                                                <div class="input-group">
                                                    <div class="input-group-addon"><span class="icon-calendar-o"
                                                            aria-hidden="true"></span></div>
                                                    <input type="text" class="form-control required" id="tsn_due"
                                                        name="invocieduedate"
                                                        placeholder="Due Date" data-toggle="datepicker" autocomplete="false">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label for="taxformat"
                                                    class="caption">Tax</label>
                                                <select class="form-control" name="taxformat"
                                                    onchange="changeTaxFormat(this.value)"
                                                    id="taxformat">
                                                    <option value="on" seleted>&raquo;On</option>
                                                    <option value="on">On</option>
                                                    <option value="off">Off</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-6">

                                                <div class="form-group">
                                                    <label for="discountFormat"
                                                        class="caption"> Discount</label>
                                                    <select class="form-control" onchange="changeDiscountFormat(this.value)"
                                                        id="discountFormat">

                                                        <option value="%"> % Discount After TAX </option>
                                                        <option value="flat">Flat Discount After TAX</option>
                                                        <option value="b_p"> % Discount Before TAX</option>
                                                        <option value="bflat">Flat Discount Before TAX</option>
                                                        <!-- <option value="0">Off</option> -->
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label for="toAddInfo"
                                                    class="caption">Invoice Note</label>
                                                <textarea class="form-control" name="notes" rows="2"></textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div id="saman-row">
                                <table class="table-responsive tfr my_stripe">

                                    <thead>
                                        <tr class="item_header">
                                            <th width="30%" class="text-center">Item Name</th>
                                            <th width="8%" class="text-center"> Quantity</th>
                                            <th width="10%" class="text-center">Rate</th>
                                            <th width="10%" class="text-center"> Tax(%)</th>
                                            <th width="10%" class="text-center">Tax</th>
                                            <th width="7%" class="text-center"> Discount</th>
                                            <th width="10%" class="text-center">
                                                Amount (৳)
                                            </th>
                                            <th width="5%" class="text-center"> Action</th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" class="form-control text-center" name="product_name[]"
                                                    placeholder="Enter Product name or Code" id='productname-0'>
                                            </td>
                                            <td><input type="text" class="form-control req amnt" name="product_qty[]" id="amount-0"
                                                    onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()"
                                                    autocomplete="off" value="1"></td>
                                            <td><input type="text" class="form-control req prc" name="product_price[]" id="price-0"
                                                    onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()"
                                                    autocomplete="off"></td>
                                            <td><input type="text" class="form-control vat " name="product_tax[]" id="vat-0"
                                                    onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()"
                                                    autocomplete="off"></td>
                                            <td class="text-center" id="texttaxa-0">0</td>
                                            <td><input type="text" class="form-control discount" name="product_discount[]"
                                                    onkeypress="return isNumber(event)" id="discount-0"
                                                    onkeyup="rowTotal('0'), billUpyog()" autocomplete="off"></td>
                                            <td><span class="currenty">৳</span>
                                                <strong><span class='ttlText' id="result-0">0</span></strong>
                                            </td>
                                            <td class="text-center">

                                            </td>
                                            <input type="hidden" name="taxa[]" id="taxa-0" value="0">
                                            <input type="hidden" name="disca[]" id="disca-0" value="0">
                                            <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-0" value="0">
                                            <input type="hidden" class="pdIn" name="pid[]" id="pid-0" value="0">
                                        </tr>
                                        <tr>
                                            <td colspan="8"><textarea id="dpid-0" class="form-control" name="product_description[]" placeholder="Enter Product description" autocomplete="off"></textarea><br></td>
                                        </tr>

                                        <tr class="last-item-row sub_c">
                                            <td class="add-row">
                                                <button type="button" class="btn btn-success" aria-label="Left Align"
                                                    data-toggle="tooltip"
                                                    data-placement="top" title="Add product row" id="addproduct">
                                                    <i class="icon-plus-square"></i> Add Row </button>
                                            </td>
                                            <td colspan="7"></td>
                                        </tr>

                                        <tr class="sub_c" style="display: table-row;">
                                            <td colspan="6" align="right"><input type="hidden" value="0" id="subttlform"
                                                    name="subtotal"><strong> Total Tax</strong>
                                            </td>
                                            <td align="left" colspan="2"><span
                                                    class="currenty lightMode">৳</span>
                                                <span id="taxr" class="lightMode">0</span>
                                            </td>
                                        </tr>
                                        <tr class="sub_c" style="display: table-row;">
                                            <td colspan="6" align="right">
                                                <strong> Total Discount</strong>
                                            </td>
                                            <td align="left" colspan="2"><span
                                                    class="currenty lightMode">৳</span>
                                                <span id="discs" class="lightMode">0</span>
                                            </td>
                                        </tr>

                                        <tr class="sub_c" style="display: table-row;">
                                            <td colspan="6" align="right">
                                                <strong> Shipping</strong>
                                            </td>
                                            <td align="left" colspan="2"><input type="text" class="form-control shipVal"
                                                    onkeypress="return isNumber(event)"
                                                    placeholder="Value"
                                                    name="shipping" autocomplete="off"
                                                    onkeyup="updateTotal()"></td>
                                        </tr>

                                        <tr class="sub_c" style="display: table-row;">
                                            <td colspan="2">Payment Currency for your client <small>based on live market</small>
                                                <select name="mcurrency"
                                                    class="selectpicker form-control">
                                                    <option value="0">Default</option>
                                                    <option value="1">X (GBP)</option>
                                                </select>
                                            </td>
                                            <td colspan="4" align="right"><strong> Grand Total (<span
                                                        class="currenty lightMode">৳</span>)</strong>
                                            </td>
                                            <td align="left" colspan="2"><input type="text" name="total" class="form-control"
                                                    id="invoiceyoghtml" readonly="">

                                            </td>
                                        </tr>
                                        <tr class="sub_c" style="display: table-row;">
                                            <td colspan="2"> Payment Terms <select name="pterms"
                                                    class="selectpicker form-control">
                                                    <option value="1">Payment on receipt</option>
                                                </select></td>
                                            <td align="right" colspan="6"><input type="submit" class="btn btn-success sub-btn"
                                                    value=" Generate Invoice "
                                                    id="submit-data" data-loading-text="Creating...">

                                            </td>
                                        </tr>


                                    </tbody>
                                </table>
                            </div>

                            <input type="hidden" value="invoices/action" id="action-url">
                            <input type="hidden" value="search" id="billtype">
                            <input type="hidden" value="0" name="counter" id="ganak">
                            <input type="hidden" value="৳" name="currency">
                            <input type="hidden" value="%" name="taxformat" id="tax_format">
                            <input type="hidden" value="%" name="discountFormat" id="discount_format">
                            <input type="hidden" value="yes" name="tax_handle" id="tax_status">
                            <input type="hidden" value="yes" name="applyDiscount" id="discount_handle">

                        </form>

                    </div>
                </div>
            </div>

        </div>

    </section>
    <!-- /.content -->


</div>
<!-- /.content-wrapper -->

@endsection