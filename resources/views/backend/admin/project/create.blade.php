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
                        <form method="post" id="data_form" class="form-horizontal">

                            <h5>Add Project</h5>
                            <hr>

                            <div class="form-group row">

                                <label class="col-sm-2 col-form-label" for="name">Title</label>

                                <div class="col-sm-10">
                                    <input type="text" placeholder="Project Title"
                                        class="form-control margin-bottom  required" name="name">
                                </div>
                            </div>

                            <div class="form-group row">

                                <label class="col-sm-2 col-form-label" for="name">Status</label>

                                <div class="col-sm-4">
                                    <select name="status" class="form-control">
                                        <option value='Waiting'>Waiting</option>
                                        <option value='Pending'>Pending</option>
                                        <option value='Terminated'>Terminated</option>
                                        <option value='Finished'>Finished</option>
                                        <option value='Progress'>In Progress</option>
                                    </select>
                                </div>
                            </div>

                            <!-- <div class="form-group row"> -->

                                <!-- <label class="col-sm-2 col-form-label" for="progress">In Progress (in %)</label> -->

                                <!-- <div class="col-sm-10">
                                    <input type="range" min="0" max="100" value="0" class="slider" id="progress" name="progress">
                                    <p><span id="prog"></span></p>
                                </div> -->

                                <!-- <div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div> -->

                                <!-- <style>
                                    .custom-progress {
                                        width: 100%;
                                        height: 20px;
                                        background-color: #e9ecef; 
                                        border-radius: 10px;
                                        overflow: hidden; 
                                        position: relative;
                                        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.2);
                                    }

                                    .custom-progress-bar {
                                        height: 100%;
                                        background-color: #007bff; 
                                        width: 0%; 
                                        transition: width 0.4s ease; 
                                    }

                                    .custom-progress-bar[aria-valuenow="50"] {
                                        width: 50%;
                                    }
                                </style>

                                <div class="col-sm-10 custom-progress">
                                    <div class="custom-progress-bar" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%;"></div>
                                </div> -->


                            <!-- </div> -->

                            <div class="form-group row">

                                <label class="col-sm-2 col-form-label"
                                    for="pay_cat">Priority</label>

                                <div class="col-sm-4">
                                    <select name="priority" class="form-control">
                                        <option value='Low'>Low</option>
                                        <option value='Medium'>Medium</option>
                                        <option value='High'>High</option>
                                        <option value='Urgent'>Urgent</option>
                                    </select>


                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label"
                                    for="pay_cat">Customer</label>

                                <div class="col-sm-10">
                                    <select name="customer" class="form-control" id="customer_statement">
                                        <option value="0">Select Customer</option>

                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">

                                <label class="col-sm-2 col-form-label"
                                    for="name">Customer Can View</label>

                                <div class="col-sm-4">
                                    <select name="customerview" class="form-control">
                                        <option value='true'>True</option>
                                        <option value='false'>False</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">

                                <label class="col-sm-2 col-form-label"
                                    for="name">Customer Can Comment</label>

                                <div class="col-sm-4">
                                    <select name="customercomment" class="form-control">
                                        <option value='true'>True</option>
                                        <option value='false'>False</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">

                                <label class="col-sm-2 col-form-label" for="worth">Budget</label>

                                <div class="col-sm-4">
                                    <input type="number" placeholder="Budget"
                                        class="form-control margin-bottom  required" name="worth">
                                </div>
                            </div>

                            <div class="form-group row">

                                <label class="col-sm-2 col-form-label"
                                    for="pay_cat">Assign to</label>

                                <div class="col-sm-8">
                                    <select name="employee[]" class="form-control required select-box" multiple="multiple">
                                        <option value='10'>BusinessOwner</option><option value='12'>Ahanaf Shahriar</option><option value='13'>Asraful Islam</option><option value='14'>Rakibul Islam</option><option value='15'>Rifat Zahan Zim</option><option value='16'>Nazrul Islam</option>                        </select>
                                </div>
                            </div>

                            <div class="form-group row">

                                <label class="col-sm-2 col-form-label" for="phase">Phase</label>

                                <div class="col-sm-10">
                                    <input type="text" placeholder="Phase A,B,C"
                                        class="form-control margin-bottom  required" name="phase">
                                </div>
                            </div>


                            <div class="form-group row">

                                <label class="col-sm-2 control-label"
                                    for="edate">Start Date</label>

                                <div class="col-sm-2">
                                    <input type="text" class="form-control required"
                                        placeholder="Start Date" name="sdate"
                                        data-toggle="datepicker" autocomplete="false">
                                </div>
                            </div>


                            <div class="form-group row">

                                <label class="col-sm-2 control-label"
                                    for="edate">Due Date</label>

                                <div class="col-sm-2">
                                    <input type="text" id="pdate_2" class="form-control required edate"
                                        placeholder="End Date" name="edate"
                                        autocomplete="false" value="19-02-2025">
                                </div>
                            </div>

                            <div class="form-group row">

                                <label class="col-sm-2 col-form-label"
                                    for="name">Link to calendar</label>

                                <div class="col-sm-4">
                                    <select name="link_to_cal" class="form-control" id="link_to_cal">
                                        <option value='0'>No</option>
                                        <option value='1'>Mark Deadline(End Date)</option>
                                        <option value='2'>Mark Start to End Date</option>
                                    </select>
                                </div>
                            </div>

                            <div id="hidden_div" class="row form-group" style="display: none">
                                <label class="col-md-2 control-label" for="color">Color</label>
                                <div class="col-md-4">
                                    <input id="color" name="color" type="text" class="form-control input-md"
                                        readonly="readonly"/>
                                    <span class="help-block">Click to pick a color</span>
                                </div>
                            </div>

                            <div class="form-group row">

                                <label class="col-sm-2 control-label"
                                    for="content">Note</label>

                                <div class="col-sm-10">
                                    <textarea class="summernote"
                                            placeholder=" Note"
                                            autocomplete="false" rows="10" name="content"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">

                                <label class="col-sm-2 col-form-label" for="tags">Tags</label>

                                <div class="col-sm-10">
                                    <input type="text" placeholder="Tags"
                                        class="form-control margin-bottom  required" name="tags">
                                </div>
                            </div>
                            <div class="form-group row">

                                <label class="col-sm-2 col-form-label"
                                    for="name">Task Communication</label>

                                <div class="col-sm-4">
                                    <select name="ptype" class="form-control">
                                        <option value='0'>No</option>
                                        <option value='1'>Emails to team</option>
                                        <option value='2'>Emails to team, customer</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">

                                <label class="col-sm-2 col-form-label"></label>

                                <div class="col-sm-4">
                                    <input type="submit" id="submit-data" class="btn btn-success margin-bottom"
                                        value="Add" data-loading-text="Adding...">
                                    <input type="hidden" value="projects/addproject" id="action-url">

                                </div>
                            </div>


                        </form>
                    </div>
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

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->
<!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> -->

<script type="text/javascript">

    $(function () {
        $('.select-box').select2();

        $('.summernote').summernote({
            height: 250,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['fullscreen', ['fullscreen']],
                ['codeview', ['codeview']]
            ]
        });
    });

    $("#customer_statement").select2({
        minimumInputLength: 4,
        tags: [],
        // ajax: {
        //     url: baseurl + 'search/customer_select',
        //     dataType: 'json',
        //     type: 'POST',
        //     quietMillis: 50,
        //     data: function (customer) {
        //         return {
        //             customer: customer
        //         };
        //     },
        //     processResults: function (data) {
        //         return {
        //             results: $.map(data, function (item) {
        //                 return {
        //                     text: item.name,
        //                     id: item.id
        //                 }
        //             })
        //         };
        //     },
        // }
    });

    $('.edate').datepicker({autoHide: true, format: 'dd-mm-yyyy'});
    var slider = $('#progress');
    var textn = $('#prog');
    textn.text(slider.val() + '%');
    $(document).on('change', slider, function (e) {
        e.preventDefault();
        textn.text($('#progress').val() + '%');

    });
</script>

<script>
    function updateProgress(value) {
    const progressBar = document.querySelector('.custom-progress-bar');
    progressBar.style.width = value + '%';
    progressBar.setAttribute('aria-valuenow', value);
}

// Example: Set progress to 75%
updateProgress(75);

</script>

@endsection