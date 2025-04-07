@extends('layouts.admin')

@section('admin')

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <h1>Purchase Order - Subcontractor</h1>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div id="printable-area">
          <div class="invoice-box" style="padding: 30px; border: 1px solid #ccc; background: #fff;">
            <div class="row mb-4">
              <div class="col-2">
                <img src="{{ asset('bkolpo-logo.png') }}" alt="BKOLPO Logo" style="height: 40px; margin-top: 0;">
              </div>
              <div class="col-7">
                <div class="custom-header" style="font-weight: bold; margin-bottom: 15px;"><h3>Smc jjled tv</h3></div>
                <div>Document Number: 323080015</div>
                <div>Document Date: 16/08/2023</div>
                <div>Delivery Address: Site Delivery</div>
              </div>
              <div class="col-3 text-right">
                <div class="fs-1" style="font-size: 1.5rem; font-weight: bold;">Purchase Order - Subcontractor</div>
              </div>
            </div>

            <div class="row mb-4">
              <div class="col-6">
                <div class="custom-header" style="font-weight: bold; margin-bottom: 15px;">Vendor:</div>
                <p><strong>Vendor Code:</strong> S18055</p>
                <p><strong>Vendor Name:</strong> BKOLPO CONSTRUCTION LTD.</p>
                <p><strong>Address:</strong> TOKYO TOWER (6TH FLOOR), CHERAG ALI, TONGI.</p>
              </div>
              <div class="col-6 text-right">
                <div class="custom-header" style="font-weight: bold; margin-bottom: 15px;">Document Info:</div>
                <p><strong>Document Number:</strong> 323080015</p>
                <p><strong>Document Date:</strong> 16/08/2023</p>
                <p><strong>Delivery Address:</strong> Site Delivery</p>
              </div>
            </div>

            {{-- <div class="table-responsive">
              <table class="custom-invoice-table" style="border: 2px solid #000000; border-collapse: separate; border-spacing: 0; width: 100%;">
                <thead>
                  <tr>
                    <th style="padding: 8px 12px; border-right: 1px solid #000000; font-size: 14px;">Item Code</th>
                    <th style="padding: 8px 12px; border-right: 1px solid #000000; font-size: 14px;">Description</th>
                    <th style="padding: 8px 12px; border-right: 1px solid #000000; font-size: 14px;">Qty</th>
                    <th style="padding: 8px 12px; border-right: 1px solid #000000; font-size: 14px;">UoM</th>
                    <th style="padding: 8px 12px; border-right: 1px solid #000000; font-size: 14px;">Unit Price</th>
                    <th style="padding: 8px 12px; border-right: 1px solid #000000; font-size: 14px;">Total</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach(range(1, 6) as $i)
                  <tr>
                    <td style="padding: 8px 12px; border-right: 1px solid #000000;">SUB010001</td>
                    <td style="padding: 8px 12px; border-right: 1px solid #000000;">Dismantling of serviceable/damaged brick</td>
                    <td style="padding: 8px 12px; border-right: 1px solid #000000;">30</td>
                    <td style="padding: 8px 12px; border-right: 1px solid #000000;">M3</td>
                    <td style="padding: 8px 12px; border-right: 1px solid #000000;">538.00 BDT</td>
                    <td style="padding: 8px 12px; border-right: 1px solid #000000;">16,140.00 BDT</td>
                  </tr>
                  @endforeach
                </tbody>

              </table>
            </div> --}}

            <div class="card-body">
              <table class="table table-striped border">
                  <thead>
                      <tr>
                        <th>Serial</th>
                        <th>Description</th>
                        <th>Qty</th>
                        <th>Unit</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                      </tr>
                  </thead>
                  <tbody>
                      @foreach(range(1, 6) as $i)
                          <tr>
                            <td >{{ $i }}</td>
                            <td >Dismantling of serviceable/damaged brick</td>
                            <td >30</td>
                            <td >M3</td>
                            <td >538.00 BDT</td>
                            <td >16,140.00 BDT</td>
                          </tr>
                      @endforeach
                  </tbody>
              </table>

              {{-- --- --}}
              <div class="row">
                <div class="col-8"></div>
                <div class="col-4">
            
                    <div class="table-responsive">
                        <table class="table border">
                            <tr>
                                <th style="width:50%">Total Amount:</th>
                                <td>16,140.00 BDT</td>
                            </tr>
                            <tr>
                                <th>Discount:</th>
                                <td>16,140.00 BDT</td>
                            </tr>
                            <tr>
                                <th>Transport Cost:</th>
                                <td>16,140.00 BDT</td>
                            </tr>
                            <tr>
                                <th>Carrying:</th>
                                <td>16,140.00 BDT</td>
                            </tr>
                            <tr>
                                <th>Vat:</th>
                                <td>16,140.00 BDT</td>
                            </tr>
                            <tr>
                                <th>Tax:</th>
                                <td>16,140.00 BDT</td>
                            </tr>
                            
                            <tr>
                                <th>Grand Total:</th>
                                <td>16,140.00 BDT</td>
                            </tr>
                        </table>
                    </div>
                </div>
              </div>
              {{-- --- --}}
            </div>

            {{-- <hr> --}}
            
            {{-- <div class="card-body">
              <div class="row">
                  <div class="col-8"></div>
                  <div class="col-4">
              
                      <div class="table-responsive">
                          <table class="table border">
                              <tr>
                                  <th style="width:50%">Total Amount:</th>
                                  <td></td>
                              </tr>
                              <tr>
                                  <th>Total Discount:</th>
                                  <td></td>
                              </tr>
                              <tr>
                                  <th>Transport Cost:</th>
                                  <td></td>
                              </tr>
                              <tr>
                                  <th>Carrying:</th>
                                  <td></td>
                              </tr>
                              <tr>
                                  <th>Vat:</th>
                                  <td></td>
                              </tr>
                              <tr>
                                  <th>Tax:</th>
                                  <td></td>
                              </tr>
                              
                              <tr>
                                  <th>Total:</th>
                                  <td></td>
                              </tr>
                          </table>
                      </div>
                  </div>
              </div>
            </div> --}}

          </div>
      </div>
    </div>
  </section>

  <div class="row no-print ml-2 mt-3">
    <div class="col-12">
      <button class="btn btn-primary" onclick="printBalanceSheet()">
        <i class="fa fa-print"></i> Print
      </button>
    </div>
  </div>
</div>

@endsection

@push('js')
<script>
    function printBalanceSheet() {

      var printContent = document.getElementById("printable-area").innerHTML;
      var originalContent = document.body.innerHTML;

      document.body.innerHTML = printContent;
      window.print();
      document.body.innerHTML = originalContent;
    }
</script>
@endpush