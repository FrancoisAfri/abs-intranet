@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- Include Date Range Picker -->
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
        <!-- New User Form -->
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                    <!--  <h3 class="box-title">Search for a Vehicle</h3> -->
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <!--    <form class="form-horizontal"  id="search_form" method="POST"> -->
                <form class="form-horizontal" method="POST" action="/vehicle_management/vehicle/Search">
                    {{ csrf_field() }}

                    <div class="box-body">

                        <!--  -->
                        <div class="col-md-8 col-md-offset-2">
                            <div>
                                <div class="box-header with-border">
                                    <h3 class="box-title">Search for a Vehicle</h3>
                                </div>
                                <div class="box-body">

                                    <div class="form-group">
                                        <label for="company_id" class="col-sm-3 control-label">Company</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <select class="form-control select2" id="company_id" name="company_id">
                                                    <option selected="selected" value="0">*** Select a Company ***
                                                    </option>

                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="department_id" class="col-sm-3 control-label">Department</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <select class="form-control select2" id="department_id"
                                                        name="department_id">
                                                    <option selected="selected" value="0">*** Select a Department ***
                                                    </option>

                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('property_type') ? ' has-error' : '' }}">
                                        <label for="property_type" class="col-sm-3 control-label"> Property
                                            Type </label>

                                        <div class="col-sm-9">
                                            <label class="radio-inline" style="padding-left: 0px;"><input type="radio"
                                                                                                          id="rdo_package"
                                                                                                          name="property_type"
                                                                                                          value="1"
                                                                                                          checked> All
                                            </label>
                                            <label class="radio-inline"><input type="radio" id="rdo_product"
                                                                               name="property_type" value="2"> Internal
                                            </label>
                                            <label class="radio-inline"><input type="radio" id="rdo_products"
                                                                               name="property_type" value="3"> External
                                            </label>

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="vehicle_id" class="col-sm-3 control-label">Vehicle Type</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <select class="form-control select2" id="vehicle_id" name="vehicle_id">
                                                    <option selected="selected" value="0">*** Select a Vehicle Type
                                                        ***
                                                    </option>

                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="fleet_number" class="col-sm-3 control-label">Fleet Number </label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <input type="text" class="form-control" id="fleet_number"
                                                       name="fleet_number" placeholder="Enter Fleet Number...">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="registration_number" class="col-sm-3 control-label">Registration
                                            Number </label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <input type="text" class="form-control" id="registration_number"
                                                       name="registration_number"
                                                       placeholder="Enter Registration Number...">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('promotion_type') ? ' has-error' : '' }}">
                                        <label for="property_type" class="col-xs-3 control-label"> Property
                                            Type </label>

                                        <div class="col-sm-9">
                                            <label class="radio-inline" style="padding-left: 0px;"><input type="radio"
                                                                                                          id="rdo_package"
                                                                                                          name="promotion_type"
                                                                                                          value="1"
                                                                                                          checked>
                                                Inactive </label>
                                            <label class="radio-inline"><input type="radio" id="rdo_product"
                                                                               name="promotion_type" value="2"> Active
                                            </label>
                                            <label class="radio-inline"><input type="radio" id="rdo_products"
                                                                               name="promotion_type" value="3"> Require
                                                Approval </label>
                                            <label class="radio-inline"><input type="radio" id="rdo_products"
                                                                               name="promotion_type" value="4"> Rejected
                                            </label>
                                            <label class="radio-inline"><input type="radio" id="rdo_products"
                                                                               name="promotion_type" value="5"> All
                                            </label>

                                        </div>
                                    </div>


                       

                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary pull-left"><i
                                                    class="fa fa-search"></i> Search
                                        </button>
                                        <!--  <button type="button" class="btn btn-primary pull-right" id="add_vehicle" ><i class="fa fa-plus-square-o"></i> Add Vehicle</button> -->

                                        <button type="button" id="cat_module" class="btn btn-primary pull-right"
                                                data-toggle="modal"
                                                data-target="#add-vehicledetails-modal"><i
                                                    class="fa fa-plus-square-o"></i> Add Vehicle
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
        </div>
        @include('Vehicles.partials.add_vehicledetails_modal')
    </div>
@endsection

@section('page_script')
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- bootstrap datepicker -->
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>

    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js"
            type="text/javascript"></script>
    <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->

    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>

    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script type="text/javascript">
        $(function () {
            $(".select2").select2();
            $('.hours-field').hide();
            $('.comp-field').hide();
            var moduleId;
            //Tooltip
            $('[data-toggle="tooltip"]').tooltip();

            //Vertically center modals on page

            //Phone mask
            $("[data-mask]").inputmask();

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
        });

        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true
        });

        //Initialize iCheck/iRadio Elements
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '10%' // optional
        });

        $(document).ready(function () {

            $('#year').datepicker({
                minViewMode: 'years',
                autoclose: true,
                format: 'yyyy'
            });

        });

        $('#rdo_package, #rdo_product').on('ifChecked', function () {
            var allType = hideFields();
            if (allType == 1) $('#box-subtitle').html('Site Address');
            else if (allType == 2) $('#box-subtitle').html('Temo Site Address');
        });

        //

        $('#rdo_fin, #rdo_comp').on('ifChecked', function () {
            var allType = hidenFields();
            if (allType == 1) $('#box-subtitle').html('Site Address');
            else if (allType == 2) $('#box-subtitle').html('Temo Site Address');
        });


        function hideFields() {
            var allType = $("input[name='promotion_type']:checked").val();
            if (allType == 1) {
                $('.hours-field').hide();
                $('.odometer-field').show();
            }
            else if (allType == 2) {
                $('.odometer-field').hide();
                $('.hours-field').show();
            }
            return allType;
        }

        //
        function hidenFields() {
            var allType = $("input[name='title_type']:checked").val();
            if (allType == 1) {
                $('.comp-field').hide();
                $('.fin-field').show();
            }
            else if (allType == 2) {
                $('.fin-field').hide();
                $('.comp-field').show();
            }
            return allType;
        }

        $('#add_vehicledetails').on('click', function () {
            var strUrl = '/vehicle_management/add_vehicleDetails';
            var formName = 'add-new-vehicledetails-form';
            var modalID = 'add-vehicledetails-modal';
            //var modal = $('#'+modalID);
            var submitBtnID = 'add_vehicledetails';
            var redirectUrl = '/vehicle_management/add_vehicle';
            var successMsgTitle = 'Fleet Type Added!';
            var successMsg = 'The Fleet Type has been updated successfully.';
            modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });


    </script>
@endsection
