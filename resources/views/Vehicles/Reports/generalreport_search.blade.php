@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-8 col-md-offset-2">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user pull-right"></i>
                    <h3 class="box-title">Vehicle Reports</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" id="report_form" method="POST"
                      action="/vehicle_management/vehicle_reports/details">
                    <!-- audits -->
                    {{ csrf_field() }}

                    <div class="box-body">

                        <div class="form-group{{ $errors->has('application_type') ? ' has-error' : '' }}">
                            <label for="Leave_type" class="col-sm-2 control-label"> Report Type</label>

                            <div class="col-sm-10">
                                <label class="radio-inline"><input type="radio" id="rdo_all" name="report_type"
                                                                   value="1" checked> ALL </label>
                                <label class="radio-inline"><input type="radio" id="rdo_active" name="report_type"
                                                                   value="2"> Active </label>
                                <label class="radio-inline"><input type="radio" id="rdo_inactive" name="report_type"
                                                                   value="3"> Inactive </label>
                                <label class="radio-inline"><input type="radio" id="rdo_req_approval" name="report_type"
                                                                   value="4"> Require Approval </label>
                                <label class="radio-inline"><input type="radio" id="rdo_rejected" name="report_type"
                                                                   value="5"> Rejected </label>

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="gender" class="col-sm-2 control-label">Report Type</label>

                            <div class="col-sm-8">
                                <select name="report_id" id="report_id" class="form-control">
                                    <option value="">*** Select Report Type ***</option>
                                    <option value="1">Booking Log</option>
                                    <option value="2">Fuel Log</option>
                                    <option value="3">Fines</option>
                                    <option value="4">Service</option>
                                    <option value="5">Incidents</option>
                                    <option value="6">Vehicle Details</option>
                                    <option value="7">Vehicle Contract</option>
                                    <option value="8">Expired Documents</option>
                                    <option value="9">External Diesel Log</option>
                                    <option value="10">Internal Diesel Log</option>
                                    <option value="11">Diesel Log</option>
                                    {{--<option value="12" >Incidents</option>--}}
                                    <option value="13">Alerts Report</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="path" class="col-sm-2 control-label">Vehicle Type</label>
                            <div class="col-sm-8">
                                <select class="form-control select2" style="width: 100%;"
                                        id="vehicle_type" name="vehicle_type">
                                    <option value="">*** Select a Vehicle Type ***</option>
                                    @foreach($Vehicle_types as $Vehicle)
                                        <option value="{{ $Vehicle->id }}">{{ $Vehicle->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('licence_type') ? ' has-error' : '' }}">
                            <label for="licence_type" class="col-sm-2 control-label">Licence Type</label>
                            <div class="col-sm-8">

                                <select class="form-control select2" style="width: 100%;" id="licence_type"
                                        name="licence_type">
                                    <option value="">*** Select an Licence Type ***</option>
                                    @foreach($licence as $licencetype)
                                        <option value="{{ $licencetype->id }}">{{ $licencetype->name}}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        {{--<div class="form-group {{ $errors->has('vehicle_id') ? ' has-error' : '' }}">--}}
                            {{--<label for="vehicle_id" class="col-sm-2 control-label">Vehicle </label>--}}
                            {{--<div class="col-sm-8">--}}

                                {{--<select class="form-control select2" style="width: 100%;" id="vehicle_id"--}}
                                        {{--name="vehicle_id">--}}
                                    {{--<option value="">*** Select an Vehicle ***</option>--}}
                                    {{--@foreach($vehicledetail as $vehicle)--}}
                                        {{--<option value="{{ $vehicle->id }}">{{ $vehicle->vehicle_make . ' ' . $vehicle->vehicle_model}}</option>--}}
                                    {{--@endforeach--}}
                                {{--</select>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        <div class="form-group {{ $errors->has('vehicle_id') ? ' has-error' : '' }}">
                            <label for="vehicle_id" class="col-sm-2 control-label">Vehicle</label>
                            <div class="col-sm-8">
                                <select class="form-control select2" multiple="multiple" style="width: 100%;"
                                        id="vehicle_id" name="vehicle_id[]">
                                    {{--<option value="">*** Select an Employee ***</option>--}}
                                    @foreach($vehicledetail as $vehicle)
                                        <option value="{{ $vehicle->id }}">{{ $vehicle->vehicle_make . ' ' . $vehicle->vehicle_model}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('driver_id') ? ' has-error' : '' }}">
                            <label for="driver_id" class="col-sm-2 control-label">Driver</label>
                            <div class="col-sm-8">

                                <select class="form-control select2" style="width: 100%;" id="driver_id"
                                        name="driver_id">
                                    <option value="">*** Select an Driver ***</option>
                                    @foreach($hrDetails as $driver)
                                        <option value="{{ $driver->id }}">{{ $driver->first_name . ' ' . $driver->surname }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="form-group day-field {{ $errors->has('leave_types_id') ? ' has-error' : '' }}">
                            <label for="days" class="col-sm-2 control-label">Action Date</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <!--                                    <input type="text" class="form-control pull-right" id="reservation">-->
                                    <input type="text" class="form-control daterangepicker" id="action_date"
                                           name="action_date" value="" placeholder="Select Action Date...">

                                </div>
                            </div>
                        </div>


                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-search-plus"></i>
                            Search
                        </button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- End new User Form-->
    </div>
@endsection

@section('page_script')
    <!-- Select 2-->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <!-- Bootstrap date picker -->
    <script src="/bower_components/AdminLTE/plugins/daterangepicker/moment.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Start Bootstrap File input -->
    <!-- canvas-to-blob.min.js is only needed if you wish to resize images before upload. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/canvas-to-blob.min.js"
            type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
    <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
    <!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
    <script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>

    <!-- End Bootstrap File input -->

    <script type="text/javascript">
        //Cancel button click event
        /*document.getElementById("cancel").onclick = function () {
            location.href = "/contacts";
        };*/
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
            //Date Range picker
            $('.daterangepicker').daterangepicker({
                format: 'DD/MM/YYYY',
                endDate: '-1d',
                autoclose: true
            });

            //Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });

        });
        //Phone mask
        $("[data-mask]").inputmask();
    </script>
@endsection