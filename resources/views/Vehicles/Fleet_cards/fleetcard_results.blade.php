@extends('layouts.main_layout')
@section('page_dependencies')
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">

    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
	
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">
@endsection
@section('content')
<div class="row">
<div class="col-md-12 col-md-offset-0">
<div class="box box-primary">
<div class="box-header with-border">
	<i class="fa fa-truck pull-right"></i>
	<h3 class="box-title"> Fleet Cards</h3>
</div>
<div class="box-body">
	<div class="box">
		<!-- /.box-header -->
		<div class="box-body">
			<div style="overflow-X:auto;">
				<table id="example2" class="table table-bordered table-hover">
					<thead>
					<tr>
						<th style="width: 5px; text-align: center;"></th>
						<th>Fleet Card Type</th>
						<th>Vehicle Fleet Number</th>
						<th>Holder</th>
						<th>Card Number</th>
						<th>CVS Number</th>
						<th>Issued By</th>
						<th>Issued Date</th>
						<th>Expiry Date</th>
						<th>Status</th>
					</tr>
					</thead>
					<tbody>
					@if (count($fleetcards) > 0)
						@foreach ($fleetcards as $fleetcard)
							<tr id="categories-list">
								<td nowrap>
									<button vehice="button" id="edit_compan"
											class="btn btn-warning  btn-xs" data-toggle="modal"
											data-target="#edit-vehiclefleet-modal"
											data-id="{{ $fleetcard->id }}"
											data-fleet_number="{{ $fleetcard->fleet_number }}"
											data-cvs_number="{{ $fleetcard->cvs_number }}"
											data-holder_id="{{ $fleetcard->holder_id }}"
											data-company_id="{{ $fleetcard->company_id }}"
											data-card_number="{{$fleetcard->card_number}}"
											data-card_type_id="{{ $fleetcard->card_type_id }}"
											data-issued_date="{{ date("d/m/Y", $fleetcard->issued_date)}}"
											data-expiry_date="{{date("d/m/Y",  $fleetcard->expiry_date)}}"
											data-status="{{ $fleetcard->status }}"><i class="fa fa-pencil-square-o"></i> Edit
									</button>
								</td>
								<td>{{ !empty($fleetcard->type_name ) ? $fleetcard->type_name : '' }}</td>
								<td>{{ !empty($fleetcard->fleetnumber ) ? $fleetcard->fleetnumber : '' }}</td>
								<td>{{ !empty($fleetcard->first_name . '' . $fleetcard->surname ) ? $fleetcard->first_name . '' . $fleetcard->surname : ''}}</td>
								<td>{{ !empty($fleetcard->card_number) ? $fleetcard->card_number : ''}}</td>
								<td>{{ !empty($fleetcard->cvs_number) ? $fleetcard->cvs_number : ''}}</td>
								<td>{{ !empty($fleetcard->Vehicle_Owner) ? $fleetcard->Vehicle_Owner : ''}}</td>
								<td>{{ !empty($fleetcard->issued_date ) ? date("d/m/Y", $fleetcard->issued_date) : ''}}</td>
								<td>{{ !empty($fleetcard->expiry_date ) ? date("d/m/Y",  $fleetcard->expiry_date) : ''}}</td>
								<td>{{ !empty($fleetcard->status) ? $status[$fleetcard->status] : ''}}</td>
							</tr>
						@endforeach
					@endif
					</tbody>
					<tfoot>
					<tr>
						<th style="width: 5px; text-align: center;"></th>
						<th>Fleet Card Type</th>
						<th>Vehicle Fleet Number</th>
						<th>Holder</th>
						<th>Card Number</th>
						<th>CVS Number</th>
						<th>Issued By</th>
						<th>Issued Date</th>
						<th>Expiry Date</th>
						<th>Active</th>
					</tr>
					</tfoot>
				</table>
				<div class="box-footer" style="text-align: center;">
					<button type="button" id="cancel" class="btn btn-default pull-left"><i
								class="fa fa-arrow-left"></i> Back
				</div>
			</div>
			@include ('Vehicles.Fleet_cards.edit_vehiclefleetcard_modal')
		</div>
	</div>
@endsection

@section('page_script')
	<!-- DataTables -->
		<script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
		<script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
		<!-- Select2 -->
		<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
		<!-- bootstrap datepicker -->
		<script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
		<!-- iCheck -->
		<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
		
		<!-- Ajax form submit -->
		<script src="/custom_components/js/modal_ajax_submit.js"></script>
		<!-- End Bootstrap File input -->
		<!--<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
		<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
		<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
		<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
		<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script> -->
		<script>
			//Cancel button click event
			document.getElementById("cancel").onclick = function () {
				location.href = "/vehicle_management/fleet_cards";
			};
			
			$(function () {
				
				$('#issued_date').datepicker({
					format: 'dd/mm/yyyy',
					autoclose: true,
					todayHighlight: true
				});

				$('#expiry_date').datepicker({
					format: 'dd/mm/yyyy',
					autoclose: true,
					todayHighlight: true
				});

				//Tooltip
				$('[data-toggle="tooltip"]').tooltip();

				//Vertically center modals on page

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

			//Initialize iCheck/iRadio Elements
			$('input').iCheck({
				checkboxClass: 'icheckbox_square-blue',
				radioClass: 'iradio_square-blue',
				increaseArea: '10%' // optional
			});
<<<<<<< HEAD
=======
			
				$(function () {
					var bindDatePicker = function() {
							$(".date").datetimepicker({
							format:'YYYY-MM-DD',
								icons: {
									time: "fa fa-clock-o",
									date: "fa fa-calendar",
									up: "fa fa-arrow-up",
									down: "fa fa-arrow-down"
								}
							}).find('input:first').on("blur",function () {
								// check if the date is correct. We can accept dd-mm-yyyy and yyyy-mm-dd.
								// update the format if it's yyyy-mm-dd
								var date = parseDate($(this).val());

								if (! isValidDate(date)) {
									//create date based on momentjs (we have that)
									date = moment().format('YYYY-MM-DD');
								}

								$(this).val(date);
							});
						}
					
					var isValidDate = function(value, format) {
							format = format || false;
							// lets parse the date to the best of our knowledge
							if (format) {
								value = parseDate(value);
							}

							var timestamp = Date.parse(value);

							return isNaN(timestamp) == false;
					}
					
					var parseDate = function(value) {
							var m = value.match(/^(\d{1,2})(\/|-)?(\d{1,2})(\/|-)?(\d{4})$/);
							if (m)
								value = m[5] + '-' + ("00" + m[3]).slice(-2) + '-' + ("00" + m[1]).slice(-2);

							return value;
					}
					
					bindDatePicker();
					});
>>>>>>> 5067566d6beab8673795cce3217f315d6a5cccad

			var fleetID;
			$('#edit-vehiclefleet-modal').on('show.bs.modal', function (e) {
				var btnEdit = $(e.relatedTarget);
				fleetID = btnEdit.data('id');
				var Fleet_number = btnEdit.data('fleet_number');
				var CardTypeId = btnEdit.data('card_type_id');
				var Company_id = btnEdit.data('company_id');
				var Holder_id = btnEdit.data('holder_id');
				var Card_number = btnEdit.data('card_number');
				var CVs_number = btnEdit.data('cvs_number');
				var IssuedDate = btnEdit.data('issued_date');
				var ExpiryDate = btnEdit.data('expiry_date');
				var Status = btnEdit.data('status');
				var modal = $(this);
				modal.find('#fleet_number').val(Fleet_number);
				modal.find('select#card_type_id').val(CardTypeId).trigger("change");
				modal.find('#company_id').val(Company_id);
				modal.find('#holder_id').val(Holder_id);
				modal.find('#card_number').val(Card_number);
				modal.find('#cvs_number').val(CVs_number);
				modal.find('#issued_date').val(IssuedDate);
				modal.find('#expiry_date').val(ExpiryDate);
				modal.find('#status').val(Status);
			});

			 $('#edit_vehiclefleetcard').on('click', function () {
				var strUrl = '/vehicle_management/edit_vehiclefleetcard/' + fleetID;
				//var formName = 'edit-vehiclefleet-form';
				var modalID = 'edit-vehiclefleet-modal';
				var objData = {
					card_type_id: $('#'+modalID).find('#card_type_id').val(),
					fleet_number: $('#'+modalID).find('#fleet_number').val(),
					company_id: $('#'+modalID).find('#company_id').val(),
					holder_id: $('#'+modalID).find('#holder_id').val(),
					card_number: $('#'+modalID).find('#card_number').val(),
					cvs_number: $('#'+modalID).find('#cvs_number').val(),
					issued_date: $('#'+modalID).find('#issued_date').val(),
					expiry_date: $('#'+modalID).find('#expiry_date').val(),
					status: $('#'+modalID).find('input:checked[name = status]').val(),
					_token: $('#'+modalID).find('input[name=_token]').val()
				};
				var submitBtnID = 'edit_vehiclefleetcard';
				var redirectUrl = '/vehicle_management/fleet_cards';
				var successMsgTitle = 'Record has been updated!';
				var successMsg = 'The Record has been updated successfully.';
				var Method = 'PATCH';
				modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
			});

		</script>
@endsection
