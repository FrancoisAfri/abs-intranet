@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <!-- Include Date Range Picker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
    <!-- Time picker -->
    <!--  -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css"
          rel="stylesheet">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Fuel Record(s) </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                <strong class="lead">Vehicle Details</strong><br>
                                @if(!empty($vehiclemaker))
                                    | &nbsp; &nbsp; <strong>Vehicle Make:</strong> <em>{{ $vehiclemaker->name }}</em>
                                    &nbsp;
                                    &nbsp;
                                @endif
                                @if(!empty($vehiclemodeler))
                                    -| &nbsp; &nbsp; <strong>Vehicle Model:</strong>
                                    <em>{{ $vehiclemodeler->name }}</em>
                                    &nbsp; &nbsp;
                                @endif
                                @if(!empty($vehicleTypes))
                                    -| &nbsp; &nbsp; <strong>Vehicle Type:</strong> <em>{{ $vehicleTypes->name }}</em>
                                    &nbsp;
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
                    <div align="center">
                        <!--  -->
                        <a href="{{ '/vehicle_management/viewdetails/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-bars"></i> General Details
                        </a>
                        <a href="{{ '/vehicle_management/bookin_log/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-book"></i> Booking Log
                        </a>

                        <a href="{{ '/vehicle_management/fuel_log/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-tint"></i> Fuel Log
                        </a>
                        {{--<a href="{{ '/vehicle_management/oil_log/' . $maintenance->id }}" class="btn btn-app">--}}
                        {{--<i class="fa fa-file-o"></i> Oil Log--}}
                        {{--</a>--}}
                        <a href="{{ '/vehicle_management/incidents/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-medkit"></i> Incidents
                        </a>
                        <a href="{{ '/vehicle_management/fines/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-list-alt"></i> Fines
                        </a>
                        <a href="{{ '/vehicle_management/service_details/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-area-chart"></i> Service Details
                        </a>
                        <a href="{{ '/vehicle_management/insurance/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-car"></i>Insurance
                        </a>
                        <a href="{{ '/vehicle_management/warranties/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-snowflake-o"></i>Warranties
                        </a>
                        <a href="{{ '/vehicle_management/general_cost/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-money"></i> General Cost
                        </a>
						<a href="{{ '/vehicle_management/fleet-communications/' . $maintenance->id }}"
                                   class="btn btn-app"><i class="fa fa-money"></i> Communications</a>
                    </div>
                    <table class="table table-bordered">
						<tr>
							<th>
								<button type="button" class="btn btn-default pull-left" id="previous_button" value=""><i
                                    class="fa fa-caret-square-o-left"></i> Previous Month
								</button>
								<input type="hidden" name="calendar_month" id="calendar_month" value="{{$month}}">
								<input type="hidden" name="calendar_year" id="calendar_year" value="{{$year}}}">
							</th>
							<th colspan="11" style="text-align: center"><font size="5">Fuel Transactions For: {{$monthText}}</font></th>
							<th>
								<button type="button" class="btn btn-default pull-right" id="next_button" value=""><i
											class="fa fa-caret-square-o-right"></i> Next Month
								</button>
							</th>
						</tr>
                        <tr>
                            <th>Date Taken</th>
                            <th>Transaction Type</th>
                            <th>Filled By</th>
                            <th>Tanks and Other</th>
                            <th>Tank Name</th>
                            <th>Service Station</th>
                            <th>Fuel in Litres</th>
                            <th>Cost per Litres</th>
                            <th>Cost (R)</th>
                            @if (isset($metreType) && $metreType === 1)
                                <th>Km Reading</th>
                            @else
                                <th>Hour Reading</th>
                            @endif
                            @if (isset($metreType) && $metreType === 1)
                            <th>Km Per Litre</th>
                            @else
                            <th>Hour Per Litre</th>
                            @endif
                            <th style="width: 5px; text-align: center;">Status</th>
                            <th style="width: 5px; text-align: center;"></th>
                        </tr>
                        @if (count($vehiclefuellog) > 0)
                            @foreach ($vehiclefuellog as $details)
                                <tr id="categories-list">

                                    <td>{{ !empty($details->date) ? date(' d M Y', $details->date) : '' }}</td>
                                    <td>{{ !empty($details->transaction_type) ?  $transType[$details->transaction_type] : ''}}</td>
                                    <td>{{ !empty($details->firstname . ' ' . $details->surname) ? $details->firstname . ' ' . $details->surname : '' }}</td>
                                    <td>{{ !empty($details->tank_and_other) ?  $status[$details->tank_and_other] : ''}}</td>
                                    <td>{{ !empty($details->tankName) ?  $details->tankName : ''}}</td>
                                    <td>{{ !empty($details->station) ?  $details->station : ''}}</td>
                                    <td style="text-align: center">{{ !empty($details->litres_new) ? number_format($details->litres_new, 2) : ''}}</td>
                                    <td style="text-align: center">{{ !empty($details->cost_per_litre) ?  'R '.number_format($details->cost_per_litre, 2) : ''}} </td>
                                    <td style="text-align: center">{{ !empty($details->total_cost) ? 'R '.number_format($details->total_cost, 2) : ''}} </td>
                                    @if (isset($metreType) && $metreType === 1)
										<td style="text-align: center">{{ !empty($details->Odometer_reading) ? number_format($details->Odometer_reading, 2) . ' kms' : ''}}</td>
                                    @else
										<td style="text-align: center">{{ !empty($details->Hoursreading) ? number_format($details->Hoursreading, 2) . ' hrs' : ''}}</td>
                                    @endif
                                    @if (isset($metreType) && $metreType === 1)
                                        <td style="text-align: center">{{!empty($details->actual_km_reading) && !empty($details->litres_new) ? number_format($details->actual_km_reading / $details->litres_new, 2) .' km/l' : 0}}</td>
                                    @else
										<td style="text-align: center">{{!empty($details->actual_hr_reading) && !empty($details->litres_new) ? number_format($details->actual_hr_reading / $details->litres_new, 2) .' hr/l' : 0 }}</td>
                                    @endif
                                    <td>{{ !empty($details->status) ?  $bookingStatus[$details->status] : ''}}</td>
                                    <!--  <td style="text-align:center;" colspan="2"> -->
                                    <td>
                                        <button type="button" class="btn btn-danger btn-xs" data-toggle="modal"
                                                data-target="#delete-fuellog-warning-modal"><i class="fa fa-trash"></i>
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            <input type="hidden" name="vehicle_id" size="10" value="$iVehicleID">
                            <class="caption">
                            <td colspan="6" style="text-align:right">Total</td>
                            <td style="text-align: center">{{number_format($totalLitres, 2) }}</td>
                            <td style="text-align: center">&nbsp;</td>
                            <td style="text-align: center" nowrap>{{'R '.number_format($totalCosts, 2)}}</td>
                            <td style="text-align: center"><span style="float:right"></span></br><span
                                        style="float:right"></span></td>
                            <td style="text-align: center"></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td><!--$iTotalKilo km's,$iTotalHrs hrs-->

                        @else
                            <tr id="categories-list">
                                <td colspan="15">
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        No Record for this vehicle, please start by adding a new Record for this
                                        vehicle..
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </table>
                    <div class="box-footer">
                        <button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                        <button type="button" id="cat_module" class="btn btn-warning pull-right" data-toggle="modal"
                                data-target="#add-fuel-modal">Add New Fuel Record
                        </button>
                    </div>
                </div>
            </div>       
			@include('Vehicles.partials.add_fuelrecord_modal')
			<!--  @include('Vehicles.partials.add_vehicleFuelRecords_modal') -->
			<!--  @include('Vehicles.FuelTanks.partials.edit_vehicleFuelRecords_modal') -->
            @if (count($vehiclefuellog) > 0)
                @include('Vehicles.warnings.fuellog_warning_action', ['modal_title' => 'Delete Task', 'modal_content' => 'Are you sure you want to delete this Vehicle Fuel Log? This action cannot be undone.'])
            @endif
        </div>
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
	<!-- time picker -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
	<script>
		function postData(id, data) {
			if (data == 'actdeac') location.href = "/vehicle_management/policy_act/" + id;

		}
		$('#previous_button').click(function () {
			location.href = '/vehicle_management/fuel_log/{{$ID}}/{{$month. '_' . 'p' . '_' . $year }}';
		});
		$('#next_button').click(function () {
			location.href = '/vehicle_management/fuel_log/{{$ID}}/{{$month. '_' . 'n' . '_' . $year }}';
		});
		var moduleId;
		//Initialize Select2 Elements
		$(".select2").select2();
		$('.zip-field').hide();
		$('.transaction-field').hide();
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
		//Initialize iCheck/iRadio Elements
		$('input').iCheck({
			checkboxClass: 'icheckbox_square-blue',
			radioClass: 'iradio_square-blue',
			increaseArea: '10%' // optional
		});
		$(document).ready(function () {
			$('#litres_new').change(function () {
				var litres_new = $('#litres_new').val();
				var total_cost = $('#total_cost').val();
				var litre_cost = $('#cost_per_litre').val();

				if (litre_cost > 0 && litres_new > 0) {
					var total_cost = (litres_new * litre_cost).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
					document.getElementById('total_cost').value = total_cost;
				}
				else if (litres_new > 0 && total_cost > 0) {
					var litre_cost = (total_cost / litres_new).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
					document.getElementById('cost_per_litre').value = litre_cost;
				}
			});
			$('#cost_per_litre').change(function () {
				var litres_new = $('#litres_new').val();
				var total_cost = $('#total_cost').val();
				var litre_cost = $('#cost_per_litre').val();
				if (litre_cost > 0 && litres_new > 0) {
					var total_cost = (litres_new * litre_cost).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
					document.getElementById('total_cost').value = total_cost;
				}
				else if (litre_cost > 0 && total_cost > 0) {
					var litres_new = (total_cost / litre_cost).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
					document.getElementById('litres_new').value = litres_new;
				}
			});
			$('#total_cost').change(function () {
				var litres_new = $('#litres_new').val();
				var total_cost = $('#total_cost').val();
				var litre_cost = $('#cost_per_litre').val();
				if (litre_cost > 0 && total_cost) {
					var litres_new = (total_cost / litre_cost).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
					document.getElementById('litres_new').value = litres_new;
				}
				else if (litres_new > 0 && total_cost) {
					var litre_cost = (total_cost / litres_new).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
					document.getElementById('cost_per_litre').value = litre_cost;
				}
			});
		});
		$(document).ready(function () {
			$('#date').datepicker({
				format: 'dd/mm/yyyy',
				autoclose: true,
				todayHighlight: true
			});
			$('#dateofincident').datepicker({
				format: 'dd/mm/yyyy',
				autoclose: true,
				todayHighlight: true
			});

		}); 
		$('#rdo_transaction, #rdo_Other').on('ifChecked', function () {
			var allType = hideFields();

		});
		function hideFields() {
			var allType = $("input[name='transaction']:checked").val();
			if (allType == 1) {
				$('.transaction-field').hide();
				$('.Tanks-field').show();
			}
			else if (allType == 2) {
				$('.transaction-field').show();
				$('.Tanks-field').hide();
			}
			return allType;
		}
		//Post perk form to server using ajax (add)
		$('#add_vehiclefuellog').on('click', function () {
			var strUrl = '/vehicle_management/addvehiclefuellog';
			var formName = 'add-fuel-form';
			var modalID = 'add-fuel-modal';
			var submitBtnID = 'add_vehiclefuellog';
			var redirectUrl = '/vehicle_management/fuel_log/{{ $maintenance->id }}';
			var successMsgTitle = 'New Record Added!';
			var successMsg = 'The Record  has been updated successfully.';
			modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
		});
		var incidentID;
		$('#edit-incidents-modal').on('show.bs.modal', function (e) {
			var btnEdit = $(e.relatedTarget);
			incidentID = btnEdit.data('id');
			var date_of_incident = btnEdit.data('dateofincident');
			var incident_type = btnEdit.data('incident_type');
			var severity = btnEdit.data('severity');
			var reported_by = btnEdit.data('reported_by');
			var odometer_reading = btnEdit.data('odometer_reading');
			var status = btnEdit.data('status');
			var description = btnEdit.data('description');
			var claim_number = btnEdit.data('claim_number');
			var Cost = btnEdit.data('cost');
			var documents = btnEdit.data('documents');
			var documents1 = btnEdit.data('documents1');
			var valueID = btnEdit.data('valueID');
			var name = btnEdit.data('name');
			var modal = $(this);
			modal.find('#date_of_incident').val(date_of_incident);
			modal.find('#name').val(name);
			modal.find('#incident_type').val(incident_type);
			modal.find('#severity').val(severity);
			modal.find('#reported_by').val(reported_by);
			modal.find('#odometer_reading').val(odometer_reading);
			modal.find('#status').val(status);
			modal.find('#description').val(description);
			modal.find('#claim_number').val(claim_number);
			modal.find('#Cost').val(Cost);
			modal.find('#documents').val(documents);
			modal.find('#documents1').val(documents1);
			modal.find('#valueID').val(valueID);
		});
		$('#edit_vehicleincidents').on('click', function () {
			var strUrl = '/vehicle_management/edit_vehicleincidents/' + incidentID;
			var formName = 'edit-incidents-form';
			var modalID = 'edit-incidents-modal';
			var submitBtnID = 'edit_fines';
			var redirectUrl = '/vehicle_management/incidents/{{ $maintenance->id }}';
			var successMsgTitle = 'New Record Added!';
			var successMsg = 'The Record  has been updated successfully.';
			var Method = 'PATCH'
			modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
		});
	</script>
@endsection
