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
      <!--  -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">     
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
                <form class="form-horizontal" method="POST" action="/vehicle_management/vehiclesearch">
                    {{ csrf_field() }}

                    <div class="box-body">

                        <!--  -->
                        <div class="col-md-8 col-md-offset-2">
                            <div>
                                <div class="box-header with-border" align="center">
                                    <h3 class="box-title">Search for a Vehicle</h3>
                                </div>
                                <div class="box-body" id="vehicle_details">

                                <div class="form-group">
                                        <label for="path" class="col-sm-2 control-label">Vehicle Type</label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-truck"></i>
                                                </div>
                                                <select class="form-control select2" style="width: 100%;"
                                                        id="vehicle_type" name="vehicle_type">
                                                    <option value="">*** Select a Vehicle Type ***</option>
                                                    @foreach($Vehicle_types as $Vehicle)
                                                        <option value="{{ $Vehicle->id }}">{{ $Vehicle->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                 @foreach($division_levels as $division_level)
                                        <div class="form-group manual-field{{ $errors->has('division_level_' . $division_level->level) ? ' has-error' : '' }}">
                                            <label for="{{ 'division_level_' . $division_level->level }}"
                                                   class="col-sm-2 control-label">{{ $division_level->name }}</label>

                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-black-tie"></i>
                                                    </div>
                                                    <select id="{{ 'division_level_' . $division_level->level }}"
                                                            name="{{ 'division_level_' . $division_level->level }}"
                                                            class="form-control"
                                                            onchange="divDDOnChange(this, null, 'vehicle_details')">
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="row emp-field" style="display: block;">
                                        <div class="col-xs-6">
                                            <div class="form-group Sick-field {{ $errors->has('date_from') ? ' has-error' : '' }}">
                                                <label for="date_from" class="col-sm-4 control-label">Required From</label>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                        <input type="text" id ="required_from" class="form-control pull-left" name="required_from" value=" " >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-6">
                                            <div class="form-group neg-field {{ $errors->has('date_to') ? ' has-error' : '' }}">
                                                <label for="date_to" class="col-sm-3 control-label">Required Time</label>
                                                <div class="col-sm-9">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-clock-o"></i>
                                                        </div>
                                                        <input type="text" id ="required_time" class="form-control pull-left" name="required_time" value=" " >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                     <div class="row emp-field" style="display: block;">
                                        <div class="col-xs-6">
                                            <div class="form-group Sick-field {{ $errors->has('date_from') ? ' has-error' : '' }}">
                                                <label for="date_from" class="col-sm-4 control-label">Return At</label>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                        <input type="text" id ="return_at" class="form-control pull-left" name="return_at" value=" " >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-6">
                                            <div class="form-group neg-field {{ $errors->has('date_to') ? ' has-error' : '' }}">
                                                <label for="date_to" class="col-sm-3 control-label">Return At Time</label>
                                                <div class="col-sm-9">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-clock-o"></i>
                                                        </div>
                                                        <input type="text" id ="return_time" class="form-control pull-left" name="return_time" value=" " >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                        <div class="box-footer">
                           <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Search</button>
                        </div>
                                </div>
                            </div>
                        </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
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
     <!-- time picker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
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

        $('.required_from').datepicker({
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

            $('#required_from').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });


            $('#return_at').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });


            $('#required_time').datetimepicker({
                             format: 'HH:mm:ss'
              });

             $('#return_time').datetimepicker({
                             format: 'HH:mm:ss'
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

        //Post perk form to server using ajax (add)
        $('#add_vehicledetails').on('click', function () {
            var strUrl = '/vehicle_management/add_vehicleDetails';
            var formName = 'add-new-vehicledetails-form';
            var modalID = 'add-vehicledetails-modal';
            var submitBtnID = 'add_vehicledetails';
            var redirectUrl = '/vehicle_management/manage_fleet';
            var successMsgTitle = 'New Vehicle Details Added!';
            var successMsg = 'TheVehicle Details has been updated successfully.';
            modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });

         //Load divisions drop down
        var parentDDID = '';
        var loadAllDivs = 1;
        @foreach($division_levels as $division_level)
        //Populate drop down on page load
        var ddID = '{{ 'division_level_' . $division_level->level }}';
        var postTo = '{!! route('divisionsdropdown') !!}';
        var selectedOption = '';
        var divLevel = parseInt('{{ $division_level->level }}');
        var incInactive = -1;
        var loadAll = loadAllDivs;
        loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo);
        parentDDID = ddID;
        loadAllDivs = -1;
        @endforeach
      
    </script>
@endsection
