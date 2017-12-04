@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->

    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
    <!--Time Charger-->
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title"> List Contracts </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
            
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                <strong class="lead">Vehicle Details</strong><br>

                               @if(!empty($vehiclemaker))
                                    | &nbsp; &nbsp; <strong>Vehicle Make:</strong> <em>{{ $vehiclemaker->name }}</em> &nbsp;
                                    &nbsp;
                                @endif
                                @if(!empty($vehiclemodeler))
                                    -| &nbsp; &nbsp; <strong>Vehicle Model:</strong> <em>{{ $vehiclemodeler->name }}</em>
                                    &nbsp; &nbsp;
                                @endif
                                @if(!empty($vehicleTypes))
                                    -| &nbsp; &nbsp; <strong>Vehicle Type:</strong> <em>{{ $vehicleTypes->name }}</em> &nbsp;
                                    &nbsp;
                                @endif
                                @if(!empty($maintenance->vehicle_registration))
                                    -| &nbsp; &nbsp; <strong>Vehicle Registration:</strong>
                                    <em>{{ $maintenance->vehicle_registration }}</em> &nbsp; &nbsp;
                                @endif
                                @if(!empty($maintenance->year))
                                    -| &nbsp; &nbsp; <strong>Year:</strong> <em>{{ $maintenance->year }}</em> &nbsp;
                                    &nbsp;
                                @endif
                                @if(!empty($maintenance->vehicle_color))
                                    -| &nbsp; &nbsp; <strong>Vehicle Color:</strong>
                                    <em>{{ $maintenance->vehicle_color }}</em> &nbsp; &nbsp; -|
                                @endif

                            </p>
                        </div>
                    </div>

                    <form class="form-horizontal" method="POST" action="/vehicle_management/vehicle/Search">
                        {{ csrf_field() }}

                        <div class="box-body">

                            <!--  -->
                            <div class="col-md-8 col-md-offset-2">
                                <div>
                                    <div class="box-header with-border">
                                        <!--  <h3 class="box-title">Search for a Vehicle</h3> -->
                                    </div>
                                    <div class="box-body">

                                        <div class="form-group">
                                            <label for="vehicle_id" class="col-sm-3 control-label">Category Type</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-user"></i>
                                                    </div>
                                                    <select class="form-control select2" id="vehicle_id"
                                                            name="vehicle_id">
                                                        <option selected="selected" value="0">*** Select a Category Type
                                                            ***
                                                        </option>

                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('property_type') ? ' has-error' : '' }}">
                                            <label for="property_type" class="col-sm-3 control-label"> Property
                                                Type </label>

                                            <div class="col-sm-9">
                                                <label class="radio-inline" style="padding-left: 0px;"><input
                                                            type="radio" id="rdo_package" name="property_type" value="1"
                                                            checked> Active </label>
                                                <label class="radio-inline"><input type="radio" id="rdo_product"
                                                                                   name="property_type" value="2">
                                                    Inactive </label>
                                                <label class="radio-inline"><input type="radio" id="rdo_products"
                                                                                   name="property_type" value="3">
                                                    Archived </label>

                                            </div>
                                        </div>


                                        <div class="box-footer">
                                            <button type="button" class="btn btn-default pull-left" id="back_button">
                                                Back
                                            </button>
                                            <button type="submit" class="btn btn-primary pull-right"><i
                                                        class="fa fa-search"></i> List
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->

                            <!-- /.box-footer -->
                    </form>

                    <!--   </div> -->
                    <!-- /.box-body -->

                </div>
            </div>
            <!-- Include add new prime rate modal -->
        @include('Vehicles.partials.upload_newdocument_modal')
        @include('Vehicles.partials.edit_newdocument_modal')
        <!-- Include delete warning Modal form-->

        </div>


        @endsection

        @section('page_script')
            <script src="/custom_components/js/modal_ajax_submit.js"></script>
            <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
            <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
            <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
            <!-- iCheck -->
            <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
            <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
            <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
            <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
            <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
            <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js"
                    type="text/javascript"></script>
            <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
            <script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js"
                    type="text/javascript"></script>
            <!-- the main fileinput plugin file -->
            <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
            <!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
            <script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
            <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

            <!-- InputMask -->
            <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
            <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
            <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
            <script>
                function postData(id, data) {
                    if (data == 'actdeac') location.href = "/vehice/fleetcard_act/" + id;

                }

                $('#back_button').click(function () {
                    location.href = '/vehicle_management/viewdetails/{{ $maintenance->id }}';
                });


                $(function () {
                    $('#back_button').click(function () {
                        location.href = '/vehicle_management/viewdetails/{{ $maintenance->id }}';
                    });
                    var moduleId;
                    //Initialize Select2 Elements
                    $(".select2").select2();
                    $('.zip-field').hide();
                    $('.sex-field').hide();


                    //Tooltip

                    $('[data-toggle="tooltip"]').tooltip();

                    //Vertically center modals on page
                    function reposition() {
                        var modal = $(this),
                            dialog = modal.find('.modal-dialog');
                        modal.css('display', 'block');

                        // Dividing by two centers the modal exactly, but dividing by three
                        // or four works better for larger screens.
                        dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 2));
                    }

                    // Reposition when a modal is shown
                    $('.modal').on('show.bs.modal', reposition);
                    // Reposition when the window is resized
                    $(window).on('resize', function () {
                        $('.modal:visible').each(reposition);
                    });

                    //Show success action modal
                    $('#success-action-modal').modal('show');

                    //

                    $(".js-example-basic-multiple").select2();

                    $('.datepicker').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });

                    $(function () {
                        $('img').on('click', function () {
                            $('.enlargeImageModalSource').attr('src', $(this).attr('src'));
                            $('#enlargeImageModal').modal('show');
                        });
                    });

                    //Initialize iCheck/iRadio Elements
                    $('input').iCheck({
                        checkboxClass: 'icheckbox_square-blue',
                        radioClass: 'iradio_square-blue',
                        increaseArea: '10%' // optional
                    });

                    $(document).ready(function () {

                        $('#date_from').datepicker({
                            format: 'dd/mm/yyyy',
                            autoclose: true,
                            todayHighlight: true
                        });

                    });

                    $('#exp_date').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });

                });


                $('#rdo_single, #rdo_bulke').on('ifChecked', function () {
                    var allType = hideFields();
                    if (allType == 1) $('#box-subtitle').html('Site Address');
                    else if (allType == 2) $('#box-subtitle').html('Temo Site Address');
                });


                function hideFields() {
                    var allType = $("input[name='upload_type']:checked").val();
                    if (allType == 1) {
                        $('.zip-field').hide();
                        $('.user-field').show();
                    }
                    else if (allType == 2) {
                        $('.user-field').hide();
                        $('.zip-field').show();
                    }
                    return allType;
                }

                function changetextbox() {
                    var levID = document.getElementById("key_status").value;
                    if (levID == 1) {
                        $('.sex-field').hide();
                        // $('.Sick-field').show();
                    }
                }


                //Post perk form to server using ajax (add)
                $('#add_document').on('click', function () {
                    var strUrl = '/vehicle_management/add_new_document';
                    var formName = 'add-document-form';
                    var modalID = 'add-document-modal';
                    var submitBtnID = 'add_document';
                    var redirectUrl = '/vehicle_management/document/{{ $maintenance->id }}';
                    var successMsgTitle = 'New Documents Details Added!';
                    var successMsg = 'The Documents Details has been updated successfully.';
                    modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                });

                


            </script>
@endsection
