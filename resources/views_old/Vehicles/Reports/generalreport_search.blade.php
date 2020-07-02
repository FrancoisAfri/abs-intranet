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
                    <h3 class="box-title">Fleet Management Reports</h3>
                </div>
                <!-- /.box-header -->
                <!-- form startaction="/vehicle_management/vehicle_reports/details" -->
                <form class="form-horizontal" id="report_form" method="POST">
                    <!-- audits -->
                    {{ csrf_field() }}
                    <div class="box-body" id="vehicle_details">
                        <div class="form-group">
                            <label for="gender" class="col-sm-2 control-label">Report Type</label>
                            <div class="col-sm-8">
                                <select name="report_id" id="report_id" class="form-control"
                                        onchange="changetype(this.value);" required>
                                    <option value="">*** Select Report Type ***</option>
                                    <option value="1" selected>Booking Log</option>
                                    <option value="2">Fuel Log</option>
                                    <option value="3">Fines</option>
                                    <option value="4">Service</option>
                                    <option value="5">Incidents</option>
                                    <option value="6">Vehicle Details</option>
                                    <option value="7">Fleet Card Report</option>
                                    {{--<option value="7">Vehicle Contract</option>--}}
                                    <option value="8">Expired Documents</option>
                                    <option value="9">External Diesel Log</option>
                                   {{-- <option value="10">Internal Diesel Log</option>
                                    <option value="11">Diesel Log</option>--}}
									<option value="12">Fire Extinguishers </option>
                                    {{--<option value="13">Alerts Report</option>--}}
                                </select>
                            </div>
                        </div>
                        <div id="vehicle_type_div" class="form-group">
                            <label for="vehicle_type" class="col-sm-2 control-label">Vehicle Type</label>
                            <div class="col-sm-8">
                                <select class="form-control select2" style="width: 100%;"
                                        id="vehicle_type" name="vehicle_type">
                                    <option value="">*** Select a Vehicle Type ***</option>
                                    @foreach($Vehicle_types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group detail-field {{ $errors->has('licence_type') ? ' has-error' : '' }}">
                            <label for="vehicle_make" class="col-sm-2 control-label">Manufacturer</label>
                            <div class="col-sm-8">
                                <select class="form-control select2" style="width: 100%;"
                                        id="vehicle_make" name="vehicle_make">
                                    <option value="">*** Select a Manufacturer  ***</option>
                                    @foreach($vehiclemakes as $vehiclemake)
                                        {{--<option value="{{ $vehiclemake->id }}">{{ $vehiclemake->name }}</option>--}}
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @foreach($division_levels as $division_level)
                            <div class="form-group  detail-field{{ $errors->has('division_level_' . $division_level->level) ? ' has-error' : '' }}">
                                <label for="{{ 'division_level_' . $division_level->level }}"
                                       class="col-sm-2 control-label">{{ $division_level->name }}</label>

                                <div class="col-sm-8">
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
                        <div class="form-group {{ $errors->has('vehicle_id') ? ' has-error' : '' }}">
                            <label for="vehicle_id" class="col-sm-2 control-label">Vehicle</label>
                            <div class="col-sm-8">

                                <select class="form-control select2" multiple="multiple" style="width: 100%;"
                                        id="vehicle_id"
                                        name="vehicle_id[]">
                                    <option value="">*** Select an Vehicle ***</option>
                                    @foreach($vehicledetail as $vehicle)
                                        <option value="{{ $vehicle->id }}">{{ $vehicle->fleet_number}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group dest-field {{ $errors->has('leave_types_id') ? ' has-error' : '' }}">
                            <label for="days" class="col-sm-2 control-label"> Destination</label>
                            <div class="col-sm-8">

                                <input type="text" class="form-control" id="destination"
                                       name="destination" value="" placeholder="Enter destination...">
                            </div>
                        </div>
                        <div class="form-group dest-field {{ $errors->has('purpose') ? ' has-error' : '' }}">
                            <label for="days" class="col-sm-2 control-label"> Purpose</label>
                            <div class="col-sm-8">

                                <input type="text" class="form-control" id="purpose"
                                       name="purpose" value="" placeholder="Enter Purpose...">
                            </div>
                        </div>
                        <div id="driver_div" class="form-group {{ $errors->has('driver_id') ? ' has-error' : '' }}">
                            <label for="driver_id" class="col-sm-2 control-label">Driver</label>
                            <div class="col-sm-8">
                                <select class="form-control select2" style="width: 100%;" id="driver_id"
                                        name="driver_id">
                                    <option value="">*** Select a Driver ***</option>
                                    @foreach($hrDetails as $driver)
                                        <option value="{{ $driver->id }}">{{ $driver->first_name . ' ' . $driver->surname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group day-field {{ $errors->has('action_date') ? ' has-error' : '' }}">
                            <label for="action_date" class="col-sm-2 control-label">Action Date</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="text" class="form-control daterangepicker" id="action_date"
                                           name="action_date" value="" placeholder="Select Action Date...">
                                </div>
                            </div>
                        </div>
						<div class="form-group card-field">
							<label for="path" class="col-sm-2 control-label">Card Type</label>
							<div class="col-sm-10">
								<select class="form-control select2" style="width: 100%;"
										id="card_type_id" name="card_type_id">
									<option value="">*** Select a Card Type ***</option>
									@foreach($fleetcardtype as $card)
										<option value="{{ $card->id }}">{{ $card->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group card-field">
							<label for="path" class="col-sm-2 control-label">Issued By</label>
							<div class="col-sm-10">
								<select class="form-control select2" style="width: 100%;"
										id="company_id" name="company_id">
									<option value="">*** Select a Company ***</option>
									@foreach($contactcompanies as $Company)
										<option value="{{ $Company->id }}">{{ $Company->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group fire-field">
							<label for="supplier_id" class="col-sm-2 control-label">Supplier</label>
							<div class="col-sm-10">
								<select class="form-control select2" style="width: 100%;"
										id="supplier_id" name="supplier_id">
									<option value="">*** Select a Company ***</option>
									@foreach($contactcompanies as $Company)
										<option value="{{ $Company->id }}">{{ $Company->name }}</option>
									@endforeach
								</select>
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

        $('.licence-field').hide();
        $('.detail-field').hide();
        $('.card-field').hide();
        $('.fire-field ').hide();

        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
            //Date Range picker
            $('.daterangepicker').daterangepicker({
				locale:{ format:'DD/MM/YYYY' },
                endDate: '-1d',
                autoclose: true
            });

            //Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });

            $('#report_form').attr('action', '/vehicle_management/booking_report');
        });
        //Phone mask
        $("[data-mask]").inputmask();

        function changetype(type) {
            if (type == 1) $('#report_form').attr('action', '/vehicle_management/booking_report');
            else if (type == 2) $('#report_form').attr('action', '/vehicle_management/fuel_report');
            else if (type == 3) $('#report_form').attr('action', '/vehicle_management/fine_report');
            else if (type == 4) $('#report_form').attr('action', '/vehicle_management/report_services');
            else if (type == 5) $('#report_form').attr('action', '/vehicle_management/report_incidents');
            else if (type == 6) $('#report_form').attr('action', '/vehicle_management/report_vehicle_details');
            else if (type == 7) $('#report_form').attr('action', '/vehicle_management/fleet_card_report');
            else if (type == 8) $('#report_form').attr('action' ,'/vehicle_management/report_expiry_documents');
            else if (type == 9) $('#report_form').attr('action', '/vehicle_management/report_external_diesel');
            else if (type == 10) $('#report_form').attr('action', '/vehicle_management/report_internal_diesel');
            else if (type == 11) $('#report_form').attr('action', '/activity/search12');
            else if (type == 12) $('#report_form').attr('action', '/vehicle_management/fire_extinguishers');

            //changetype

            var reportID = document.getElementById("report_id").value;

            if (reportID == 1) {
                $('.licence-field').hide();
                $('.dest-field').show();
                $('.detail-field').hide();
                $('.card-field').hide();
            } else if (reportID == 2) {
                $('.dest-field').hide();
                $('.detail-field').hide();
                $('.card-field').hide();
            } else if (reportID == 3) {
                $('.dest-field').hide();
                $('.detail-field').hide();
                $('.card-field').hide();
            } else if (reportID == 4) {
                $('.dest-field').hide();
                $('.detail-field').hide();
                $('.card-field').hide();
            } else if (reportID == 5) {
                $('.dest-field').hide();
                $('.detail-field').hide();
                $('.card-field').hide();
            } else if (reportID == 6) {
                $('.dest-field').hide();
                $('.detail-field').show();
                $('.card-field').hide();
                $('.day-field ').hide();
				$('#driver_div').hide();
            }else if (reportID == 7) {
                $('.dest-field').hide();
                $('.card-field').show();
                $('.detail-field').hide();
            }else if (reportID == 8) {
                $('.dest-field').hide();
                $('.detail-field').hide();
				$('#driver_div').hide();
            }else if (reportID == 9) {
                $('.dest-field').hide();
                $('.detail-field').hide();
                $('.card-field').hide();
            }else if (reportID == 10) {
                $('.dest-field').hide();
                $('.detail-field').hide();
                $('.card-field').hide();
            }else if (reportID == 12) {
                $('.dest-field').hide();
                $('.detail-field').hide();
                $('.card-field').hide();
                $('#driver_div').hide();
                $('#vehicle_type_div').hide();
            }

        }

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
        var parentContainer = $('#add-vehicledetails-modal');
        loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo, 0, 0, parentContainer);
        parentDDID = ddID;
        loadAllDivs = -1;
        @endforeach
    </script>
@endsection