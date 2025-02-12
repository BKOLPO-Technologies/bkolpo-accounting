<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ env('APP_NAME') }} | {{ $pageTitle ?? 'Dashboard' }}</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('backend/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('backend/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('backend/plugins/select2/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{ asset('backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('backend/plugins/icheck-bootstrap/icheck-bootstrap.min.css ') }}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{ asset('backend/plugins/jqvmap/jqvmap.min.css ') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('backend/dist/css/adminlte.min.css ') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('backend/plugins/overlayScrollbars/css/OverlayScrollbars.min.css ') }}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{ asset('backend/plugins/summernote/summernote-bs4.min.css ') }}">

  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">


  <!-- Toastr css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.min.css">

  <!-- Font Awesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  
  <!-- Bootstrap Datepicker -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
  <!-- Daterangepicker CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.1/daterangepicker.css">
  
  @stack('css')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <!--======== Navber Start =========-->
    @include('backend.admin.body.navber')
    <!--======== Navber End =========-->

    <!--======== Sidebar Start =========-->
    @include('backend.admin.body.sidebar')
    <!--======== Sidebar End =========-->

    <!--======== Main Content Start =========-->
    @yield('admin')
    <!--======== Main Content End =========-->
    
   <!--======== Footer Start =========-->
   @include('backend.admin.body.footer')
   <!--======== Footer End =========-->
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('backend/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('backend/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- Select2 -->
<script src="{{ asset('backend/plugins/select2/js/select2.full.min.js')}}"></script>

<!-- DataTables  & Plugins -->
<script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('backend/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('backend/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{ asset('backend/plugins/jszip/jszip.min.js')}}"></script>
<script src="{{ asset('backend/plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{ asset('backend/plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{ asset('backend/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{ asset('backend/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{ asset('backend/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>

<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true,
      "lengthChange": true,
      "autoWidth": false,
      "pageLength": 10,
      "buttons": [
        {
          extend: "copy",
          className: "btn btn-primary",
          text: '<i class="fa fa-copy"></i> Copy' // Add Font Awesome icon
        },
        {
          extend: "csv",
          className: "btn btn-success",
          text: '<i class="fa fa-file-csv"></i> CSV' // Add Font Awesome icon
        },
        {
          extend: "excel",
          className: "btn btn-info",
          text: '<i class="fa fa-file-excel"></i> Excel' // Add Font Awesome icon
        },
        {
          extend: "pdf",
          className: "btn btn-danger",
          text: '<i class="fa fa-file-pdf"></i> PDF' // Add Font Awesome icon
        },
        {
          extend: "print",
          className: "btn btn-warning",
          text: '<i class="fa fa-print"></i> Print' // Add Font Awesome icon
        },
        {
          extend: "colvis",
          className: "btn btn-dark",
          text: '<i class="fa fa-eye"></i> Column Visibility' // Add Font Awesome icon
        }
      ],
      "lengthMenu": [10, 25, 50, 100]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    $('#example2').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "lengthMenu": [10, 25, 50, 100]
    });
    // trial balance report 
    $("#example3").DataTable({
        "paging": false,
        "lengthChange": true,
        "searching": false,
        "ordering": false,
        "info": false,
        "autoWidth": false,
        "responsive": true,
        "buttons": [
            {
                extend: "excel",
                className: "btn btn-info",
                text: '<i class="fa fa-file-excel"></i> Excel'
            },
            {
                extend: "print",
                className: "btn btn-warning",
                text: '<i class="fa fa-print"></i> Print',
                title: '', // Remove the default page title
                exportOptions: {
                    columns: ':visible', // Ensures all visible columns are printed
                    modifier: {
                        page: 'all'
                    }
                },
                customize: function (win) {
                    // Center align content for the print view
                    $(win.document.body).css('text-align', 'center');

                    // Make the table full width with borders
                    $(win.document.body).find('table').addClass('table table-bordered').css('width', '100%');

                    // Ensure table headers are centered in the print preview
                    $(win.document.body).find('th').css('text-align', 'center');

                    // Remove the default title from the print preview
                    $(win.document.head).find('title').remove();

                    // Append custom Trial Balance Header before the table in print
                    $(win.document.body).prepend(`
                        <div class="text-center mb-3">
                            <h2 class="mb-1">{{ config('app.name') }}</h2>
                            <p class="mb-0"><strong>Trial Balance Report</strong></p>
                            <p class="mb-0">Date: {{ now()->format('d M, Y') }}</p>
                        </div>
                    `);

                    // Ensure the table footer (tfoot) is shown in print view
                    var tfoot = $('tfoot').clone(); // Clone the existing footer
                    $(win.document.body).find('table').append(tfoot); // Append it to the printed table

                    // Adjust the footer for the print view
                    $(win.document.body).find('tfoot tr').addClass('fw-bold');

                    // Custom column width for print view
                    $(win.document.body).find('th, td').each(function() {
                        if ($(this).text().trim() === 'Sl') {
                            $(this).css('width', '5%'); // Smaller Sl column
                        }
                        if ($(this).text().trim() === 'Ledger Name') {
                            $(this).css('width', '40%'); // Larger Ledger Name column
                        }
                        if ($(this).text().includes('Debit') || $(this).text().includes('Credit')) {
                            $(this).css('width', '10%'); // Smaller Debit and Credit columns
                        }
                    });

                    // Ensure footer is displayed correctly at the bottom
                    $(win.document.body).find('tfoot').css({
                        "position": "relative",
                        "bottom": "0px",
                        "width": "100%",
                        "text-align": "center",
                        "font-weight": "bold",
                        "border-top": "2px solid black"
                    });
                }
            },
        ],
    }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');
    // balance shit report
    $("#example4").DataTable({
        "paging": false,
        "lengthChange": true,
        "searching": false,
        "ordering": false,
        "info": false,
        "autoWidth": false,
        "responsive": true,
        "buttons": [
            {
                extend: "excel",
                className: "btn btn-info",
                text: '<i class="fa fa-file-excel"></i> Excel'
            },
            {
                extend: "print",
                className: "btn btn-warning",
                text: '<i class="fa fa-print"></i> Print',
                title: '', // Remove the default page title
                exportOptions: {
                    columns: ':visible', // Ensures all visible columns are printed
                    modifier: {
                        page: 'all'
                    }
                },
                customize: function (win) {
                    // Center align content for the print view
                    $(win.document.body).css('text-align', 'center');

                    // Make the table full width with borders
                    $(win.document.body).find('table').addClass('table table-bordered').css('width', '100%');

                    // Ensure table headers are centered in the print preview
                    $(win.document.body).find('th').css('text-align', 'center');

                    // Remove the default title from the print preview
                    $(win.document.head).find('title').remove();

                    // Append custom Balance Shit Header before the table in print
                    $(win.document.body).prepend(`
                        <div class="text-center mb-3">
                            <h2 class="mb-1">{{ config('app.name') }}</h2>
                            <p class="mb-0"><strong>Balance Shit Report</strong></p>
                            <p class="mb-0">Date: {{ now()->format('d M, Y') }}</p>
                        </div>
                    `);

                    // Ensure the table footer (tfoot) is shown in print view
                    var tfoot = $('tfoot').clone(); // Clone the existing footer
                    $(win.document.body).find('table').append(tfoot); // Append it to the printed table

                    // Adjust the footer for the print view
                    $(win.document.body).find('tfoot tr').addClass('fw-bold');

                    // Custom column width for print view
                    $(win.document.body).find('th, td').each(function() {
                        if ($(this).text().trim() === 'Sl') {
                            $(this).css('width', '5%'); // Smaller Sl column
                        }
                        if ($(this).text().trim() === 'Ledger Name') {
                            $(this).css('width', '40%'); // Larger Ledger Name column
                        }
                        if ($(this).text().includes('Debit') || $(this).text().includes('Credit')) {
                            $(this).css('width', '10%'); // Smaller Debit and Credit columns
                        }
                    });

                    // Ensure footer is displayed correctly at the bottom
                    $(win.document.body).find('tfoot').css({
                        "position": "relative",
                        "bottom": "0px",
                        "width": "100%",
                        "text-align": "center",
                        "font-weight": "bold",
                        "border-top": "2px solid black"
                    });
                }
            },
        ],
    }).buttons().container().appendTo('#example4_wrapper .col-md-6:eq(0)');
  });
</script>

<!-- ChartJS -->
<script src="{{ asset('backend/plugins/chart.js/Chart.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{ asset('backend/plugins/sparklines/sparkline.js')}}"></script>
<!-- JQVMap -->
<script src="{{ asset('backend/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{ asset('backend/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('backend/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{ asset('backend/plugins/moment/moment.min.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('backend/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{ asset('backend/plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('backend/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('backend/dist/js/adminlte.js')}}"></script>
<!-- AdminLTE for demo purposes -->
{{-- <script src="{{ asset('backend/dist/js/demo.js')}}"></script> --}}
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('backend/dist/js/pages/dashboard.js')}}"></script>

<!-- Daterangepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.1/daterangepicker.min.js"></script>
<script>
    $(document).ready(function(){
        // Bootstrap Datepicker
        $('#date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
        $('#from_date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
        $('#to_date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
    });
</script>



<!-- Toastr js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- jQuery and Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


<script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "3000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
</script>

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <script>
            toastr.error('{{ $error }}');
        </script>
    @endforeach
@endif

@if (session()->get('warning'))
    <script>
        toastr.warning('{{ session()->get('warning') }}');
    </script>
@endif

@if (session()->get('success'))
    <script>
        toastr.success('{{ session()->get('success') }}');
    </script>
@endif

@if (session()->get('error'))
    <script>
        toastr.error('{{ session()->get('error') }}');
    </script>
@endif

<!-- sweetalerat link -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<!-- sweetalerat delete data -->
<script type="text/javascript">
  $(function(){
      $(document).on('click','#delete',function(e){
        e.preventDefault();
        var link = $(this).attr("href");

        Swal.fire({
        title: 'Are you sure?',
        text: "Delete This Data!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
      if (result.isConfirmed) {
          window.location.href = link
          Swal.fire(
            'Deleted!',
            'Your file has been deleted.',
            'success'
          )
        }
      })
    });
  });
</script>
<!-- Bootstrap Datepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
@stack('js')
</body>
</html>
