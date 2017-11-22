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
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title"> Vehicle Notes </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
				<form class="form-horizontal" method="POST" action="/vehicle_management/vehiclesearch">
					{{ csrf_field() }}
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
                                                <input type="text" id ="required_from" class="form-control pull-left" name="required_from" value="{{ $vehicleTypes->name }} " readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="path" class="col-sm-2 control-label">Vehicle Model</label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-bullseye"></i>
                                                </div>
                                                <input type="text" id ="required_from" class="form-control pull-left" name="required_from" value="{{ $vehiclemodeler->name }} " readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="path" class="col-sm-2 control-label">Vehicle Reg. No</label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-id-card-o"></i>
                                                </div>
                                                <input type="text" id ="required_from" class="form-control pull-left" name="required_from" value="{{  $maintenance->vehicle_registration }} " readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row emp-field" style="display: block;">
                                        <div class="col-xs-6">
                                            <div class="form-group Sick-field {{ $errors->has('date_from') ? ' has-error' : '' }}">
                                                <label for="date_from" class="col-sm-4 control-label">Required From</label>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                        <input type="text" id ="required_from" class="form-control pull-left" name="required_from" value="{{ $startdate }} " readonly>
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
                                                        <input type="text" id ="required_time" class="form-control pull-left" name="required_time" value=" {{  $requiredTime }}" readonly>
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
                                                        <input type="text" id ="return_at" class="form-control pull-left" name="return_at" value=" {{ $returnAt }} " readonly>
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
                                                        <input type="text" id ="return_time" class="form-control pull-left" name="return_time" value=" {{$returnTime}} " readonly >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                      <div class="form-group">
                                          <label for="Status" class="col-sm-2 control-label">Usage Type </label>
                                          <div class="col-sm-10">
                                              <div class="input-group">
                                                  <div class="input-group-addon">
                                                      <i class="fa fa-ravelry"></i>
                                                  </div>
                                              <select id="status" name="status" class="form-control">
                                                  <option value="0">*** Select a Booking Type  ***</option>
                                                  <option value="1"> Usage</option>
                                                  <option value="2"> Service</option>
                                                  <option value="2"> Maintenance</option>
                                                  <option value="2"> Repair</option>
                                              </select>
                                              </div>
                                          </div>
                                      </div>

                                        <div class="form-group">
                                            <label for="path" class="col-sm-2 control-label">Vehicle Driver</label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-user-o"></i>
                                                    </div>
                                                    <select class="form-control " style="width: 100%;"
                                                            id="service_provider" name="service_provider">
                                                        <option value="0">*** Select Driver ***</option>
                                                        @foreach($employees as $user)
                                                            <option value="{{ $user->id }}">{{ $user->first_name . ' ' . $user->surname  }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group notes-field{{ $errors->has('extras') ? ' has-error' : '' }}">
                                            <label for="extras" class="col-sm-2 control-label">Purpose for Request</label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-sticky-note"></i>
                                                    </div>
                                                    <textarea class="form-control" id="extras" name="extras"
                                                              placeholder="Enter Extras..."
                                                              rows="3">{{ old('Extras') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    <div class="form-group">
                                        <label for="path" class="col-sm-2 control-label">Destination </label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-anchor"></i>
                                                </div>
                                                <input type="text" id ="required_from" class="form-control pull-left" name="required_from" value=" " >
                                            </div>
                                        </div>
                                    </div>



                                    <!--   </div> -->
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                        <input type="submit" id="submit" name="submit" class="btn btn-primary pull-right" value="Submit Request">
                    </div>
                </div>
            </div>
            <!-- Include add new prime rate modal -->
        @include('Vehicles.partials.upload_newnote_modal')
        @include('Vehicles.partials.edit_notes_modal')
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


	// Reposition when a modal is shown
	$('.modal').on('show.bs.modal', reposition);
	// Reposition when the window is resized
	$(window).on('resize', function() {
		$('.modal:visible').each(reposition);
	});

		var moduleId;
		//Initialize Select2 Elements
		$(".select2").select2();
		$('.zip-field').hide();

	$(".js-example-basic-multiple").select2();

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

			$('#date_captured').datepicker({
				format: 'dd/mm/yyyy',
				autoclose: true,
				todayHighlight: true
			});


			$('#exp_date').datepicker({
				format: 'dd/mm/yyyy',
				autoclose: true,
				todayHighlight: true
			});


			$('#expdate').datepicker({
				format: 'dd/mm/yyyy',
				autoclose: true,
				todayHighlight: true
			});
			$('#datecaptured').datepicker({
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
$('#add_notes').on('click', function () {
	var strUrl = '/vehicle_management/add_new_note';
	var formName = 'add-note-form';
	var modalID = 'add-note-modal';
	var submitBtnID = 'add_notes';
	var redirectUrl = '/vehicle_management/notes/{{ $maintenance->id }}';
	var successMsgTitle = 'New Note  Added!';
	var successMsg = 'The Note  has been updated successfully.';
	modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
});

		//Post perk form to server using ajax (add)
		$('#add_notes').on('click', function () {
			var strUrl = '/vehicle_management/add_new_note';
			var formName = 'add-note-form';
			var modalID = 'add-note-modal';
			var submitBtnID = 'add_notes';
			var redirectUrl = '/vehicle_management/notes/{{ $maintenance->id }}';
			var successMsgTitle = 'New Note  Added!';
			var successMsg = 'The Note  has been updated successfully.';
			modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
		});

		// });

		var noteID;
		$('#edit-note-modal').on('show.bs.modal', function (e) {
			//console.log('kjhsjs');
			var btnEdit = $(e.relatedTarget);
			 if (parseInt(btnEdit.data('id')) > 0) {
               noteID = btnEdit.data('id');     
             }
			
			var captured_by = btnEdit.data('captured_by');
			var date_captured = btnEdit.data('date_captured');
			var notes = btnEdit.data('notes');
			var documents = btnEdit.data('documents');
			var modal = $(this);
			modal.find('#captured_by').val(captured_by);
			modal.find('#date_captured').val(date_captured);
			modal.find('#notes').val(notes);
			modal.find('#documents').val(documents);

		});


		//Post perk form to server using ajax (edit)
		$('#edit_note').on('click', function () {
			var strUrl = '/vehicle_management/edit_note/' + noteID;
			var formName = 'edit-note-form';
			var modalID = 'edit-note-modal';
			var submitBtnID = 'edit_note';
			var redirectUrl = '/vehicle_management/notes/{{$maintenance->id}}';
			var successMsgTitle = 'Changes Saved!';
			var successMsg = 'The  details have been updated successfully!';
			var Method = 'PATCH';
			modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
		});


	</script>
@endsection
