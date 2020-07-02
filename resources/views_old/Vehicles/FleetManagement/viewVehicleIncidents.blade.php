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
                    <h3 class="box-title"> Vehicle Incidents Details </h3>
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
                        <!--  -->
                    </div>
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 10px; text-align: center;"></th>
                            <th>Date Reported</th>
                            <th>Reported By</th>
                              @if (isset($maintenance) && $maintenance->maintenance === 1)
                            <th>Odometer Reading</th>
                            @else
                            <th>Hours Reading</th>
                            @endif
                            <th>Incident Type</th>
                            <th> Severity</th>
                            <th>Cost (R)</th>
                            <th style="width: 5px; text-align: center;">Documents</th>
                            <th style="width: 10px; text-align: center;"></th>
                           
                        </tr>
                        @if (count($vehicleincidents) > 0)
                            @foreach ($vehicleincidents as $details)
                                <tr id="categories-list">
                                    <td nowrap>
                                        <button details="button" id="edit_compan" class="btn btn-warning  btn-xs"
                                                data-toggle="modal" data-target="#edit-incidents-modal"
                                                data-id="{{ $details->id }}"
                                                data-date_of_incident="{{  $details->date_of_incident  }}"
                                                data-incident_type="{{  $details->incident_type  }}"
                                                data-severity="{{  $details->severity  }}"
                                                data-reported_by="{{  $details->reported_by  }}"
                                                data-odometer_reading="{{  $details->odometer_reading  }}"
                                                data-status="{{  $details->status  }}"
                                                data-description="{{  $details->description  }}"
                                                data-claim_number="{{  $details->claim_number  }}"
                                                data-cost="{{ $details->Cost }}"><i class="fa fa-pencil-square-o"></i>
                                            Edit
                                        </button>
                                    </td>
                                    <td>{{ !empty($details->date_of_incident) ? date(' d M Y', $details->date_of_incident) : '' }}</td>
                                    <td>{{ !empty($details->firstname . ' ' . $details->surname) ? $details->firstname . ' ' . $details->surname : '' }}</td>
                                     @if (isset($maintenance) && $maintenance->maintenance === 1)
                                    <td>{{ !empty($details->odometer_reading) ? $details->odometer_reading : '' }}</td>
                                    @else
                                      <td>{{ !empty($details->hours_reading) ? $details->hours_reading : '' }}</td>
                                    @endif
                                    <td>{{ (!empty($details->IncidintType)) ?  $details->IncidintType : ''}}</td>
                                    <td>{{ (!empty($details->severity)) ?  $status[$details->severity] : ''}}</td>
                                    <td>{{ !empty($details->Cost) ? 'R' .number_format($details->Cost, 2) : '' }}</td>
                                    <td nowrap>
                                        <div class="form-group{{ $errors->has('document') ? ' has-error' : '' }}">
                                            <label for="document" class="control-label"></label>
                                            @if(!empty($details->incidentDoc))
												@foreach ($details->incidentDoc as $doc)
                                                <a class="btn btn-default btn-flat btn-block pull-right btn-xs"
                                                   href="{{ Storage::disk('local')->url("Vehicle/vehicleIncidents/$doc->filename") }}"
                                                   target="_blank"><i class="fa fa-file-pdf-o"></i> {{$doc->display_name}}</a>
												@endforeach
											@else
                                                <a class="btn btn-default pull-centre btn-xs"><i class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
                                            @endif
                                        </div>	 
                                    </td>
                                   
                                    @if (isset($details) && $details->vehicle_fixed != 1)
                                   <td>
                                        <button type="button" id="view_ribbons" class="btn {{ (!empty($details->vehicle_fixed) || $details->vehicle_fixed == 1 || $details->vehicle_fixed == 2 && $details->vehicle_fixed == 3) ? " btn-success " : "btn-danger" }}
                                            btn-xs" onclick="postData({{$details->id}}, 'actdeac');"><i class="fa {{ (!empty($details->vehicle_fixed) && $details->vehicle_fixed == 1) ?
                                            " fa-times " : "fa-check " }}"></i> {{ $details->vehicle_fixed == 0 ? "Fix Car" : ""}}</button>
                                  </td>
                                       @else
                                           <td>Fixed</td>
                                  @endif
                                </tr>
                            @endforeach
                        @else
                            <tr id="categories-list">
                                <td colspan="10">
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
                                data-target="#add-incidents-modal">Add New Vehicle Incident
                        </button>
                    </div>
                </div>
            </div>
            <!-- Include add new prime rate modal -->
            @include('Vehicles.partials.add_vehicleIncidents_modal')
            @include('Vehicles.partials.edit_vehicleIncidents_modal')
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
		   // if (data == 'actdeac') location.href = "/vehicle_management/policy_act/" + id;
			if (data == 'actdeac') location.href = "/vehicle_management/fixvehicle/" + id;

		}
	$(function () {
		$('#back_button').click(function () {
			location.href = '/vehicle_management/viewdetails/{{ $maintenance->id }}';
		});
		
		var moduleId;
		//Initialize Select2 Elements
		$(".select2").select2();
		$('.zip-field').hide();
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
		$(".js-example-basic-multiple").select2();
		$(document).ready(function () {

			$('#date_of_incident').datepicker({
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
		//Post perk form to server using ajax (add)
		$('#add_vehicleincidents').on('click', function () {
			var strUrl = '/vehicle_management/addvehicleincidents';
			var formName = 'add-incidents-form';
			var modalID = 'add-incidents-modal';
			var submitBtnID = 'add_vehicleincidents';
			var redirectUrl = '/vehicle_management/incidents/{{ $maintenance->id }}';
			var successMsgTitle = 'New Record Added!';
			var successMsg = 'The Record  has been updated successfully.';
			modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
		});
		var incidentID;
		$('#edit-incidents-modal').on('show.bs.modal', function (e) {
			var btnEdit = $(e.relatedTarget);
			if (parseInt(btnEdit.data('id')) > 0) {
				incidentID = btnEdit.data('id');
			}
			//console.log('gets here: ' + incidentID);
			var date_of_incident = btnEdit.data('date_of_incident');
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
			var submitBtnID = 'edit_vehicleincidents';
			var redirectUrl = '/vehicle_management/incidents/{{ $maintenance->id }}';
			var successMsgTitle = 'Vehicle incident Record updated';
			var successMsg = 'The Record  has been updated successfully.';
			var Method = 'PATCH'
			modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
		});
	});
	function clone(id, file_index, child_id) {
		var clone = document.getElementById(id).cloneNode(true);
		clone.setAttribute("id", file_index);
		clone.setAttribute("name", file_index);
		clone.style.display = "table-row";
		clone.querySelector('#' + child_id).setAttribute("name", child_id + '[' + file_index + ']');
		clone.querySelector('#' + child_id).disabled = false;
		clone.querySelector('#' + child_id).setAttribute("id", child_id + '[' + file_index + ']');
		return clone;
	}
	function addFile() {
		var table = document.getElementById("tab_tab");
		var file_index = document.getElementById("file_index");
		file_index.value = ++file_index.value;
		var file_clone = clone("file_row", file_index.value, "document");
		var name_clone = clone("name_row", file_index.value, "name");
		var final_row = document.getElementById("final_row").cloneNode(false);
		table.appendChild(file_clone);
		table.appendChild(name_clone);
		table.appendChild(final_row);
		var total_files = document.getElementById("total_files");
		total_files.value = ++total_files.value;
		//change the following using jquery if necessary
		var remove = document.getElementsByName("remove");
		for (var i = 0; i < remove.length; i++)
			remove[i].style.display = "inline";
	}
	
	function removeFile(row_name)
	{
		var row=row_name.parentNode.parentNode.id;
		var rows=document.getElementsByName(row);
		while(rows.length>0)
			rows[0].parentNode.removeChild(rows[0]);
		var total_files = document.getElementById("total_files");
		total_files.value=--total_files.value;
		var remove=document.getElementsByName("remove");
		if(total_files.value == 1)
			remove[1].style.display='none';
	}
	</script>
@endsection
