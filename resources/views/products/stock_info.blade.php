@extends('layouts.main_layout')

@section('page_dependencies')
        <!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<!-- iCheck -->
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/green.css"> 
	<!--  -->
	 <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-12">
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">Stock Details Products({{  $products->name}})</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i
									class="fa fa-minus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i
									class="fa fa-remove"></i></button>
					</div>
				</div>
				<!-- /.box-header -->
				<div class="box-body" style="max-height: 190px; overflow-y: scroll;">
					<table id="example2" class="table table-bordered table-hover">
						<thead>
							<tr>
								<th></th>
								<th>Image</th>
								<th>Location</th>
								<th>Description</th>
								<th>Allow Vat</th>
								<th>Mass Net</th>
								<th>minimum Level</th>
								<th>Maximum Level</th>
								<th>Bar Code</th>
								<th>Unit</th>
								<th>Commodity Code</th>
							</tr>
						</thead>
						<tbody>
							@if (count($products->infosProduct) > 0)
								@foreach ($products->infosProduct as $product)
									<tr>
										<td>
											<button type="button" id="edit_info" class="btn btn-primary  btn-xs"
                                                data-toggle="modal" data-target="#edit-stock-info-modal"
                                                data-id="{{ $product->id }}" data-picture="{{ $product->picture }}"
                                                data-description="{{ $product->description }}"
												data-location="{{ $product->location }}"
												data-allow_vat="{{ $product->allow_vat }}"
												data-mass_net="{{ $product->mass_net }}"
												data-minimum_level="{{ $product->minimum_level }}"
												data-maximum_level="{{ $product->maximum_level }}"
												data-bar_code="{{ $product->bar_code }}"
												data-unit="{{ $product->unit }}"
												data-commodity_code="{{ $product->commodity_code }}">
												<i class="fa fa-pencil-square-o"></i> Edit
											</button>
										</td>
										<td>
											<div class="product-img">
												<img alt="Vehicle Image" class="img-responsive" src="{{ (!empty($product->image)) ? Storage::disk('local')->url("Stock/images/$product->picture") : 'http://placehold.it/60x50' }}">
											</div>
											<div class="modal fade" id="enlargeImageModal" tabindex="-1"
													 role="dialog" align="center"
													 aria-labelledby="enlargeImageModal" aria-hidden="true">
												<div class="modal-dialog modal-sm" >
													<div class="modal-body" align="center">
														<img src="" class="enlargeImageModalSource"  style="width:300;"
															height="300" >
													</div>
												</div> 
											</div>
										</td>
										<td>{{ (!empty($product->location)) ? $product->location : ''}} </td>
										<td>{{ (!empty($product->description)) ? $product->description : ''}} </td>
										<td>{{ (!empty($product->allow_vat)) ? $product->allow_vat: ''}} </td>
										<td>{{ (!empty($product->mass_net)) ? $product->mass_net:'' }} </td>
										<td>{{ (!empty($product->minimum_level)) ? $product->minimum_level : ''}} </td>
										<td>{{ (!empty($product->maximum_level)) ? $product->maximum_level : ''}} </td>
										<td>{{ (!empty($product->bar_code)) ? $product->bar_code : ''}} </td>
										<td>{{ (!empty($product->unit)) ? $product->unit : ''}} </td>
										<td>{{ (!empty($product->commodity_code)) ? $product->commodity_code : ''}} </td>
									</tr>
								@endforeach
							@endif
						</tbody>
						<tfoot>
							<tr>
								<th></th>
								<th>Image</th>
								<th>Location</th>
								<th>Description</th>
								<th>Allow Vat</th>
								<th>Mass Net</th>
								<th>Minimum Level</th>
								<th>Maximum Level</th>
								<th>Bar Code</th>
								<th>Unit</th>
								<th>Commodity Code</th>
							</tr>
						</tfoot>
					</table>
                </div>
				<div class="box-footer">
                    <button type="button" id="add-price_titles" class="btn btn-primary pull-right" data-toggle="modal"
                            data-target="#add-stock-info-modal">Add Details
                    </button>
				</div>
			</div>
		</div>
    </div>
	<div class="row">
		<div class="col-sm-12">
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">Preferred Suppliers</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i
									class="fa fa-minus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i
									class="fa fa-remove"></i></button>
					</div>
				</div>
				<!-- /.box-header -->
				<div class="box-body" style="max-height: 190px; overflow-y: scroll;">
					<table id="example2" class="table table-bordered table-hover">
						<thead>
							<tr>
								<th></th>
								<th>Order No</th>
								<th>Supplier</th>
								<th>Description</th>
								<th>Inventory Code</th>
								<th>Date Last Processed</th>
							</tr>
						</thead>
						<tbody>
							@if (count($productPreferreds) > 0)
								@foreach ($productPreferreds as $productPreferred)
									<tr>
										<td>
											<button type="button" id="edit_info" class="btn btn-primary  btn-xs"
                                                data-toggle="modal" data-target="#edit-stock-info-modal"
                                                data-id="{{ $productPreferred->id }}" 
												data-order_no="{{ $productPreferred->order_no }}"
												data-supplier_id="{{ $productPreferred->supplier_id }}"
												data-description="{{ $productPreferred->description }}"
												data-inventory_code="{{ $productPreferred->inventory_code }}"
												<i class="fa fa-pencil-square-o"></i> Edit
											</button>
										</td>
										<td>{{ (!empty($productPreferred->order_no)) ? $productPreferred->order_no : ''}} </td>
										<td>{{ (!empty($productPreferred->com_name)) ? $productPreferred->com_name : ''}} </td>
										<td>{{ (!empty($productPreferred->description)) ? $productPreferred->description : ''}} </td>
										<td>{{ (!empty($productPreferred->inventory_code)) ? $productPreferred->inventory_code: ''}} </td>
										<td>{{ (!empty($productPreferred->date_last_processed)) ? date(' d M Y', $productActivity->date_last_processed) : ''}} </td>
									</tr>
								@endforeach
							@endif
						</tbody>
						<tfoot>
							<tr>
								<th></th>
								<th>Order No</th>
								<th>Supplier</th>
								<th>Description</th>
								<th>Inventory Code</th>
								<th>Date Last Processed</th>
							</tr>
						</tfoot>
					</table>
                </div>
				<div class="box-footer">
                    <button type="button" id="add-price_titles" class="btn btn-primary pull-right" data-toggle="modal"
                            data-target="#add-preferred-supplier-modal">Add Supplier
                    </button>
				</div>
			</div>
		</div>
    </div>
    <div class="row">
		<div class="col-sm-12">
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">Activities</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i
									class="fa fa-minus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i
									class="fa fa-remove"></i></button>
					</div>
				</div>
				<!-- /.box-header -->
				<div class="box-body" style="max-height: 190px; overflow-y: scroll;">
					<table id="example2" class="table table-bordered table-hover">
						<thead>
						<tr>
							<th>Product name</th>
							<th>Date</th>
							<th>Action Performed</th>
							<th>Performed By</th>
							<th>Allocated to</th>
							<th style="text-align: center;">Balance Before</th>
							<th style="text-align: center;">Balance After</th>
							<th style="text-align: center;">Available Balance</th>
						</tr>
						</thead>
						<tbody>
							@if (count($productActivities) > 0)
								@foreach ($productActivities as $productActivity)
									<tr>
										<td>{{ (!empty($productActivity->product_name)) ? $productActivity->product_name : ''}} </td>
										<td>{{ (!empty($productActivity->action_date)) ? date(' d M Y', $productActivity->action_date) : ''}} </td>
										<td>{{ (!empty($productActivity->action)) ? $productActivity->action : ''}} </td>
										<td>{{ (!empty($productActivity->name)&& !empty($productActivity->surname)) ? $productActivity->name." ".$productActivity->surname: ''}}</td>
										<td>{{ (!empty($productActivity->allocated_firstname) && !empty($productActivity->allocated_surname)) ? $productActivity->allocated_firstname." ".$productActivity->allocated_surname: $productActivity->fleet_number." ".$productActivity->vehicle_registration }} </td>
										<td style="text-align: center;">{{ (!empty($productActivity->balance_before)) ? $productActivity->balance_before : 0}} </td>
										<td style="text-align: center;">{{ (!empty($productActivity->balance_after)) ? $productActivity->balance_after : 0}} </td>
										<td style="text-align: center;">{{ (!empty($productActivity->avalaible_stock)) ? $productActivity->avalaible_stock : 0}} </td>
									</tr>
								@endforeach
							@endif
						</tbody>
						<tfoot>
							<tr>
								<th>Product name</th>
								<th>Date</th>
								<th>Action Performed</th>
								<th>Performed By</th>
								<th>Allocated to</th>
								<th style="text-align: center;">Balance Before</th>
								<th style="text-align: center;">Balance After</th>
								<th style="text-align: center;">Available Balance</th>
							</tr>
						</tfoot>
					</table>
                </div>
				<div class="box-footer">
                    <button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                </div>
			</div>
		</div>
    </div>
	        <!-- Include add expenditure and add income modals -->
            @include('products.partials.add_new_stock_info_modal')
			@include('products.partials.edit_stock_info_modal')
            @include('products.partials.add_prefered_suppliers_modal')
@endsection

@section('page_script')
<!-- Select2 -->
<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>

<!-- bootstrap datepicker -->
<script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>

<!-- Ajax form submit -->
<script src="/custom_components/js/modal_ajax_submit.js"></script>
<!-- iCheck -->
<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>

<script type="text/javascript">

function hideFields() {
	var radioCheck = $("input[name='attendance']:checked").val();
	if (radioCheck == 1) {
		$('.no_field').hide();
	}
	else if (radioCheck == 2) {
		$('.no_field').show();
	}
}
function hideFieldsEdit() {
	var radioCheck = $("input[name='attendance_edit']:checked").val();
	if (radioCheck == 1) {
		$('.no_field').hide();
	}
	else if (radioCheck == 2) {
		$('.no_field').show();
	}
}
function postData(id, data)
{
	if (data == 'print_minutes')
		location.href = "/meeting/prnt_meeting/" + id;
	else if (data == 'email_minutes')
		location.href = "/meeting/email_meeting/" + id;
}
$('#back_button').click(function () {
            location.href = '/Product/Product/{{$products->category_id}}';
        });
$(function () {

	 $(".select2").select2();

	 $('#due_time').datetimepicker({
             format: 'HH:mm:ss'
        });
        $('#time_to').datetimepicker({
             format: 'HH:mm:ss'
        });
	 
	 //Initialize iCheck/iRadio Elements
	$('input').iCheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green',
		increaseArea: '20%' // optional
	});
	$('#attendance_yes, #attendance_no').on('ifChecked', function(){
           hideFields();
    });
	$('#attendance_yes_edit, #attendance_no_edit').on('ifChecked', function(){
           hideFieldsEdit();
    });
	$('.datepicker').datepicker({
		format: 'dd/mm/yyyy',
		autoclose: true,
		todayHighlight: true
		});
	//Vertically center modals on page
	function reposition() {
		var modal = $(this),
				dialog = modal.find('.modal-dialog');
		modal.css('display', 'block');
		// Dividing by two centers the modal exactly, but dividing by three
		// or four works better for larger screens.
		dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 2));
	}
    //Hide/show fields
    hideFields();
	// Reposition when a modal is shown
	$('.modal').on('show.bs.modal', reposition);
	// Reposition when the window is resized
	$(window).on('resize', function () {
		$('.modal:visible').each(reposition);
	});
	//Show success action modal
	$('#success-action-modal').modal('show');
	//Post end task form to server using ajax (add)
	var minuteID;
	var attendeeID;
	
	// Call add attendee Modal
	$('#add-attendee-modal').on('show.bs.modal', function (e) {
		var btnEnd = $(e.relatedTarget);
		minuteID = btnEnd.data('meeting_id');
		var modal = $(this);
		modal.find('#meeting_id').val(minuteID);
	});
	// Add attendee Submit
	$('#save-attendee').on('click', function() {
		var strUrl = '/meeting/add_attendees/' + minuteID;
		var formName = 'add-attendee-form';
		var modalID = 'add-attendee-modal';
		var submitBtnID = 'save-attendee';
		var redirectUrl = '/meeting_minutes/view_meeting/' + {{$products->id}} + '/view';
		var successMsgTitle = 'Attendee Saved!';
		var successMsg = 'Attendee Has Been Successfully Saved!';
		modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
	});
	// Call add minute Modal
	$('#add-minutes-modal').on('show.bs.modal', function (e) {
		var btnEnd = $(e.relatedTarget);
		minuteID = btnEnd.data('meeting_id');
		var modal = $(this);
		modal.find('#meeting_id').val(minuteID);
	});
	// Add minute Submit
	$('#save-minute').on('click', function() {
		var strUrl = '/meeting/add_minutes/' + minuteID;
		var formName = 'add-minutes-form';
		var modalID = 'add-minutes-modal';
		var submitBtnID = 'save-attendee';
		var redirectUrl = '/meeting_minutes/view_meeting/' + {{$products->id}} + '/view';
		var successMsgTitle = 'Minute Saved!';
		var successMsg = 'Minute Has Been Successfully Saved!';
		modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
	});
	// Call add task Modal
	$('#add-task-modal').on('show.bs.modal', function (e) {
		var btnEnd = $(e.relatedTarget);
		minuteID = btnEnd.data('meeting_id');
		var modal = $(this);
		modal.find('#meeting_id').val(minuteID);
	});
	// Add minute Submit
	$('#save-task').on('click', function() {
		var strUrl = '/meeting/add_task/'+ {{$products->id}};
		var formName = 'add-task-form';
		var modalID = 'add-task-modal';
		var submitBtnID = 'save-task';
		var redirectUrl = '/meeting_minutes/view_meeting/' + {{$products->id}} + '/view';
		var successMsgTitle = 'Task Saved!';
		var successMsg = 'Task Has Been Successfully Saved!';
		modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
	});
	// Call Edit meeting modal/*data-meeting_id=""
	$('#edit-meeting-modal').on('show.bs.modal', function (e) {
		var btnEdit = $(e.relatedTarget);
		minuteID = btnEdit.data('meeting_id');
		var meetingName = btnEdit.data('meeting_name');
		var meetingLocation = btnEdit.data('meeting_location');
		var meetingAgenda = btnEdit.data('meeting_agenda');
		var modal = $(this);
		modal.find('#meeting_location').val(meetingLocation);
		modal.find('#meeting_name').val(meetingName);
		modal.find('#meeting_agenda').val(meetingAgenda);
		modal.find('#meeting_id').val(minuteID);
	});
	//Update meeting
	$('#update-meeting').on('click', function () {
		
		var strUrl = '/meeting/update/' + minuteID;
		var formName = 'edit-meeting-form';
		var modalID = 'edit-meeting-modal';
		var submitBtnID = 'update-meeting';
		var successMsgTitle = 'Changes Saved!';
		var redirectUrl = '/meeting_minutes/view_meeting/' + {{$products->id}} + '/view';
		var successMsg = 'Meeting details has been updated successfully.';
		var method = 'PATCH';
		modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
	});
	// Call Complete Induction modal
	$('#comp-induction-modal').on('show.bs.modal', function (e) {
		var btnEdit = $(e.relatedTarget);
		inductionID = btnEdit.data('induction_id');
		var modal = $(this);
		modal.find('#induction_id').val(inductionID);
	});
	// Complete Induction
	$('#complete-induction').on('click', function () {
		var strUrl = '/induction/complete';
		var formName = 'comp-induction-form';
		var modalID = 'comp-induction-modal';
		var submitBtnID = 'complete-induction';
		var redirectUrl = '/induction/' + {{$products->id}} + '/view';
		var successMsgTitle = 'Induction Completed!';
		var successMsg = 'Induction has been Successfully Completed!';
		modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
	});
// Update task
	$('#update-task').on('click', function () {
	var strUrl = '/tasks/update/' + taskID;
	var objData = {
		order_no: $('#edit-tasks-modal').find('#order_no').val()
		, description: $('#edit-tasks-modal').find('#description').val()
		, upload_required: $('#edit-tasks-modal').find('#upload_required').val()
		, employee_id: $('#edit-tasks-modal').find('#employee_id').val()
		, administrator_id: $('#edit-tasks-modal').find('#administrator_id').val()
		, _token: $('#edit-tasks-modal').find('input[name=_token]').val()
	};
	var modalID = 'edit-tasks-modal';
	var submitBtnID = 'update-task';
	var redirectUrl = '/induction/' + {{$products->id}} + '/view';
	var successMsgTitle = 'Changes Saved!';
	var successMsg = 'Task details has been updated successfully.';
	var method = 'PATCH';
	modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, method);
	});
	// Call Edit meeting modal/*data-meeting_id="}"
	$('#edit-attendees-modal').on('show.bs.modal', function (e) {
		var btnEdit = $(e.relatedTarget);
		attendeeID = btnEdit.data('attendee_id');
		var employeeID = btnEdit.data('employee_id') || 0;
		var clientID = btnEdit.data('client_id') || 0;
		var Attendance = btnEdit.data('attendance');
		var Apology = btnEdit.data('apology');
		var modal = $(this);
		//console.log('gets here. clientID = ' + clientID + '. empID = ' + employeeID);
		modal.find('#apology').val(Apology);
		modal.find('#attendee_id').val(attendeeID);
		modal.find('select#employee_id').val(employeeID).trigger('change');
		modal.find('select#client_id').val(clientID).trigger('change');
		if (employeeID > 0) {
			$('.internal-attendee').show();
			$('.external-attendee').hide();
		}
		else if (clientID > 0) {
			$('.internal-attendee').hide();
			$('.external-attendee').show();
		}

		if (Attendance == 2)
		{
			$("#attendance_no_edit").iCheck('check');
			$('.no_field').show();
		}
		else
		{
			$("#attendance_yes_edit").iCheck('check');
			$('.no_field').hide();
		}
	});
	$('#update-attendees').on('click', function () {
		var strUrl = '/meeting/update_attendee/' + attendeeID;
		var formName = 'edit-attendees-form';
		var modalID = 'edit-attendees-modal';
		var submitBtnID = 'update-attendees';
		var successMsgTitle = 'Changes Saved!';
		var redirectUrl = '/meeting_minutes/view_meeting/' + {{$products->id}} + '/view';
		var successMsg = 'Meeting details has been updated successfully.';
		var method = 'PATCH';
		modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
	});
});
</script>
@endsection