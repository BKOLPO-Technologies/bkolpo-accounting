@extends('layouts.admin')
<style>
    @media print {
        #filter-form {
            display: none !important;
        }
    }
</style>
@section('admin')

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Invoice</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Invoice</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          
          <div id="printable-area">
            <div class="invoice p-3 mb-3">

              <div class="banner mb-3" style="text-align: right; background-color:rgb(134, 134, 136);">
                  <img src="{{ asset('bkolpo-logo.png') }}" alt="BKOLPO Logo" style="height: 40px; margin-top: 0;">
              </div>

              <div class="text-center mb-1" style="font-weight: bold; text-decoration: underline;">
                  <h4>Work Order</h4>
              </div>

              <div class="row-1">
                <div>Ref: HWT/WO/24-25/1</div>
                <div>Date: 11/01/2025</div>
                <div>To</div>
                <div>Hydratech Water Technology</div>
                <div>156, South Kamalpur, Dhaka-1217</div>
              </div>

              <div class="subject">
                <h6 class="mt-4 mb-4" style="font-weight: bold;">
                  Subject- Purchase Order For ETP & ECC (Environmental Clearence Certificate) of ETP From DoE.
                </h6>
              </div>

              <div class="row-3 mb-4" style="font-weight: bold;">
                <div>Attention: Md Akidul Islam</div>
                <div>Proprietor</div>
                <div>Hydratech Water Technology</div>
              </div>

              <div class="row-4" style="font-weight: bold;">
                <div style="margin-bottom: 7px">Sir, Please Supply This Item:-</div>
                <div>Address: Robi Battery & Recycling, Koti, Chomohoni, Koshba.</div>
                <div>Contact Person: Engr. Aminul Islam Shikder - 01881708817</div>
                <div>Excutive Director & COO</div>
              </div>

              <br>
              <!-- 
              <div class="row">
                <div class="col-12">
                  <h4>
                    <i class="fas fa-globe"></i> AdminLTE, Inc.
                    <small class="float-right">Date: 2/10/2014</small>
                  </h4>
                </div>
              </div>
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                  From
                  <address>
                    <strong>Admin, Inc.</strong><br>
                    795 Folsom Ave, Suite 600<br>
                    San Francisco, CA 94107<br>
                    Phone: (804) 123-5432<br>
                    Email: info@almasaeedstudio.com
                  </address>
                </div>
                <div class="col-sm-4 invoice-col">
                  To
                  <address>
                    <strong>John Doe</strong><br>
                    795 Folsom Ave, Suite 600<br>
                    San Francisco, CA 94107<br>
                    Phone: (555) 539-1037<br>
                    Email: john.doe@example.com
                  </address>
                </div>
                <div class="col-sm-4 invoice-col">
                  <b>Invoice #007612</b><br>
                  <br>
                  <b>Order ID:</b> 4F3S8J<br>
                  <b>Payment Due:</b> 2/22/2014<br>
                  <b>Account:</b> 968-34567
                </div>
              </div> -->

              <!-- <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table table-bordered">
                        <thead class="">
                            <tr>
                                <th>Sl. No.</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                                <th>Rate</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>El snort testosterone trophy driving gloves handsome</td>
                                <td>1</td>
                                <td>Job</td>
                                <td>1,800,000</td>
                                <td>1,800,000</td>
                            </tr>
                        </tbody>
                        <tfoot class="">
                            <tr>
                                <td colspan="5" class="text-end fw-bold">Total:</td>
                                <td class="fw-bold">1,800,000</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
              </div> -->

              <style>
                  .custom-table {
                      border: 1px solid black; /* Outer border */
                  }
                  .custom-table th,
                  .custom-table td {
                      border: 1px solid black !important; /* Inner cell borders */
                  }
                  .custom-table thead {
                      background-color: #f8f9fa; /* Optional: Light gray background for the header */
                  }
              </style>

              <div class="row">
                  <div class="col-12 table-responsive">
                      <table class="table custom-table">
                          <thead>
                              <tr>
                                  <th>Sl. No.</th>
                                  <th>Description</th>
                                  <th>Quantity</th>
                                  <th>Unit</th>
                                  <th>Rate</th>
                                  <th>Amount</th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr>
                                  <td>1</td>
                                  <td>El snort testosterone trophy driving gloves handsome</td>
                                  <td>1</td>
                                  <td>Job</td>
                                  <td>1,800,000</td>
                                  <td>1,800,000</td>
                              </tr>
                          </tbody>
                          <tfoot>
                              <tr>
                                  <td colspan="5" class="text-center" style="font-weight: bold;">Total Amount</td>
                                  <td class="fw-bold">1,800,000</td>
                              </tr>
                          </tfoot>
                      </table>
                  </div>
              </div>

              <div><span style="font-weight: bold;">In Word:</span> Eighteen Lac Taka Only.</div>

              <div class="row-7 mt-4">
                  <div style="font-weight: bold;">Payment Trams:</div>
                  <ul>
                      <li>30% Advance with work order.</li>
                      <li>Rest of 50% will pay after delivery.</li>
                      <li>Rest of 20% will pay after complete full work within 3 Month.</li>
                  </ul>
              </div>

              <div class="row-8 mt-5">
                <div class="mb-4">Best Regards</div>
                <div>Shohel Alam</div>
                <div>CEO</div>
                <div>Bkolpo Construction Ltd.</div>
              </div>

              <div class="row-9 text-center mt-3" style="font-size: 12px;">
                  <div>BKOLPO Construction Limited | Tokyo Tower ( 6th Floor ) | Cherag Ali | Tongi | Gazipur-1711 | Bangladesh |</div>
                  <div>Mobile: +8801730-742238-88 | E-mail : construction@bkolpo.com.bd </div>
              </div>


            </div>
          </div>

        </div>
      </div>
    </div>
  </section>

  <div class="row no-print">
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
  const options = { 
    day: '2-digit', 
    month: 'long', 
    year: 'numeric' 
  };
  const currentDate = new Date().toLocaleDateString('en-US', options);
  document.getElementById('current-date').textContent = 'Date: ' + currentDate;

</script>


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