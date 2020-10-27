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
        <!-- New Form -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
			<div class="box-body">
				<div class="row">
					<div class="col-md-6">
					
						<div class="box box-success">
							<div class="box-header with-border">
								<h3 class="box-title">Meeting Details</h3>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i
												class="fa fa-minus"></i></button>
									<button type="button" class="btn btn-box-tool" data-widget="remove"><i
												class="fa fa-remove"></i></button>
								</div>
							</div>
							<!-- /.box-header -->
							<form class="form-horizontal" method="POST"  action="/meeting/update/{{$meeting->id}}">
							<input type="hidden" name="meeting_id" id="meeting_id" value="{{$meeting->id}}">
							{{ csrf_field() }}
							{{ method_field('PATCH') }}
							<div class="box-body">
								<div class="box-body" style="max-height: 190px; overflow-y: scroll;">
									<div class="form-group">
										<label for="Meeting Name" class="col-sm-2 control-label">Title</label>
										<div class="col-sm-10">
											<div>
												<input type="text" class="form-control" id="meeting_name" name="meeting_name" value="{{ $meeting->meeting_name }}">
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="Meeting Date" class="col-sm-2 control-label">Date</label>
										<div class="col-sm-10">
											<div>
											<input type="text" class="form-control datepicker" name="meeting_date" placeholder="  dd/mm/yyyy" value="{{ date('d F Y', $meeting->meeting_date) }}">
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="Meeting Location" class="col-sm-2 control-label">Location</label>
										<div class="col-sm-10">
											<div>
												<input type="text" class="form-control" id="meeting_location" name="meeting_location" value="{{$meeting->meeting_location}}">
											</div>
										</div>
									</div>
									<div class="form-group new-field {{ $errors->has('company_id') ? ' has-error' : '' }}">
										<label for="company_id" class="col-sm-2 control-label">Client</label>
										<div class="col-sm-10">
											<select id="company_id" name="company_id" class="form-control select2" style="width: 100%;">
												<option value="">*** Select a Client Company ***</option>
												@foreach($companies as $company)
													<option value="{{ $company->id }}" {{ ($company->id == $meeting->company_id) ? ' selected' : '' }}>{{ $company->name }}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="form-group">
										<label for="Meeting Agenda" class="col-sm-2 control-label">Agenda</label>
										<div class="col-sm-10">
											<div>
											<textarea rows="4" cols="50" class="form-control" id="meeting_agenda" name="meeting_agenda">{{$meeting->meeting_agenda}}</textarea>
											</div>
										</div>
									</div>
								</div>
							</div>
							
						<!-- /.box-body -->
							<div class="box-footer">
								<button type="submit"  class="btn btn-success pull-right"
										>Update
								</button>
							</div>
						</form>
						</div>
					</div>
					<!-- /.col -->
					<div class="col-md-6">
						<div class="box box-success">
							<div class="box-header with-border">
								<h3 class="box-title">Meeting Minutes</h3>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i
												class="fa fa-minus"></i></button>
									<button type="button" class="btn btn-box-tool" data-widget="remove"><i
												class="fa fa-remove"></i></button>
								</div>
							</div>
							<!-- /.box-header -->
							<div class="box-body">
								<div class="box-body" style="max-height: 190px; overflow-y: scroll;">
									<table class="table table-striped">
										<tbody>
										<tr><th>Person</th><th>Minute</th></tr>
										@if(!empty($meeting->MinutesMeet))
											@foreach($meeting->MinutesMeet as $minute)
												<tr>
													<td>{{ ($minute->employee_id > 0) ? $minute->minutesPerson->first_name  .' '. $minute->minutesPerson->surname : '' }}
														{{ ($minute->client_id > 0) ? $minute->client->full_name : '' }}
													</td>
													<td><textarea rows="2" cols="70" class="form-control" id="" name="" readonly>{{ $minute->minutes}}</textarea></td>
												</tr>
											@endforeach
										@else
												<tr>
													<td colspan="2">
														<div class="alert alert-danger alert-dismissable">
															<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><textarea rows="8" cols="100" class="form-control" id="" name="" readonly>No minutes to display, please start by adding one.</textarea></div>
													</td>
												</tr>
										@endif
										</tbody>
									</table>
								</div>
							</div>
						<!-- /.box-body -->
							<div class="box-footer">
								<a href="{{ '/meeting/prnt_meeting/'.$meeting->id.''}}" class="btn btn-success pull-left" target="_blank">Print Minutes</a>
								<button type="button" class="btn btn-success pull-left" onclick="postData({{$meeting->id}}, 'email_minutes');">Email To Attendees</button>
								<button type="button" id="add-minutes" class="btn btn-success pull-right"
								data-toggle="modal" data-target="#add-minutes-modal" data-meeting_id="{{ $meeting->id }}">Add Minutes
								</button>
							</div>
						</div> 
					<!-- /.form-group -->
					</div>
					<!-- /.col -->
				</div>
			</div>
                    <!-- /.box-footer -->
            <!-- /.box -->
        </div>
                <!-- Confirmation Modal -->
        @if(Session('success_email'))
            @include('contacts.partials.success_action', ['modal_title' => "Emails Sent!", 'modal_content' => session('success_email')])
		        <!-- Confirmation Modal -->
        @elseif(Session('success_error'))
            @include('contacts.partials.warning_action', ['modal_title' => "Meeting Minutes Error!", 'modal_content' => session('success_error')])
		@endif
    </div>
    <div class="row">
		<div class="col-sm-6">
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">Meeting Tasks</h3>

					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i
									class="fa fa-minus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i
									class="fa fa-remove"></i></button>
					</div>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="box-body" style="max-height: 400px; overflow-y: scroll;">
						<table class="table table-striped">
							<tbody>
							<tr>
								<th>Description</th>
								<th>Responsible Person</th>
								<th>Status</th>
								<th style="text-align: right;">Checked</th>
							</tr>
							@if(!empty($meeting->tasksMeeting))
							@foreach($meeting->tasksMeeting as $task)
								<tr>
									<td>{{ $task->description }}</td>
									<td>{{ $task->employeesTasks->first_name  .' '. $task->employeesTasks->surname}}</td>
									<td>{{ $taskStatus[$task->status] }}</td>
									<td>{{ !empty($task->checked) && $task->checked == 1 ?  $task->check_comments : 'No' }}</td>
								</tr>
							@endforeach
							@else
								<tr>
									<td colspan="4">
										<div class="alert alert-danger alert-dismissable">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No attendee to display, please start by adding one. </div>
									</td>
								</tr> 
							@endif
							</tbody>
						</table>
					</div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<button type="button" id="add-task" class="btn btn-success pull-right" data-toggle="modal"
							data-target="#add-task-modal" data-meeting_id="{{ $meeting->id }}">Add Task
					</button>
				</div>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">Meeting Attendees</h3>

					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i
									class="fa fa-minus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i
									class="fa fa-remove"></i></button>
					</div>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="box-body" style="max-height: 400px; overflow-y: scroll;">
						<table class="table table-striped">
							<tbody>
							<tr>
								<th></th>
								<th>Name</th>
								<th>Type</th>
								<th>Attendance</th>
								<th>Apology</th>
							</tr>
							@if(!empty($meeting->attendees))
								@foreach($meeting->attendees as $attendee)
									<tr>
										<td>
											<button type="button" id="edit-attendee" class="btn btn-success pull-right"
											data-toggle="modal" data-target="#edit-attendees-modal"
											data-attendee_id="{{ $attendee->id }}" 
											data-employee_id="{{ $attendee->employee_id }}" 
											data-client_id="{{ $attendee->client_id }}" 
											data-attendance="{{ $attendee->attendance }}" 
											data-apology="{{ $attendee->apology }}">Edit
										</button>
										</td>
										@if($attendee->employee_id)
											<td>{{ $attendee->attendeesInfo->first_name  .' '. $attendee->attendeesInfo->surname}}</td>
										@elseif($attendee->client_id)
											<td>{{ $attendee->client->full_name }}</td>
										@endif
										<td>{{ !empty($attendee->client_id) ? 'Client' : 'Employee' }}</td>
										<td>{{ ($attendee->attendance == 1) ? 'Yes' : 'No'  }}</td>
										<td>{{ $attendee->apology}}</td>
									</tr>
								@endforeach
							@else
								<tr>
									<td colspan="5">
										<div class="alert alert-danger alert-dismissable">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No attendee to display, please start by adding one. </div>
									</td>
								</tr> 
							@endif
							</tbody>
						</table>
					</div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<button type="button" id="add-attendee" class="btn btn-success pull-right" data-toggle="modal"
							data-target="#add-attendee-modal" data-meeting_id="{{ $meeting->id }}">Add Attendee
					</button>
				</div>
			</div>
		</div>
        <!-- Include add expenditure and add income modals -->
            @include('meeting_minutes.partials.add_attendees', ['modal_title' => 'Add Attendees To This Meeting'])
            @include('meeting_minutes.partials.add_task', ['modal_title' => 'Add Task To This Meeting'])
            @include('meeting_minutes.partials.add_minutes', ['modal_title' => 'Add Minutes To This Meeting'])
            @include('meeting_minutes.partials.edit_meeting', ['modal_title' => 'Edit Minutes Details'])
            @include('meeting_minutes.partials.edit_attendees', ['modal_title' => 'Edit Attendee Details'])
    </div>
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
		var redirectUrl = '/meeting_minutes/view_meeting/' + {{$meeting->id}} + '/view';
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
		var redirectUrl = '/meeting_minutes/view_meeting/' + {{$meeting->id}} + '/view';
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
		$('#save-task').prop('disabled', true);
		var strUrl = '/meeting/add_task/'+ {{$meeting->id}};
		var formName = 'add-task-form';
		var modalID = 'add-task-modal';
		var submitBtnID = 'save-task';
		var redirectUrl = '/meeting_minutes/view_meeting/' + {{$meeting->id}} + '/view';
		var successMsgTitle = 'Task Saved!';
		var successMsg = 'Task Has Been Successfully Saved!';
		modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
	});
	// Call Edit meeting modal/*data-meeting_id="{{ $meeting->id }}"
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
		var redirectUrl = '/meeting_minutes/view_meeting/' + {{$meeting->id}} + '/view';
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
		var redirectUrl = '/induction/' + {{$meeting->id}} + '/view';
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
	var redirectUrl = '/induction/' + {{$meeting->id}} + '/view';
	var successMsgTitle = 'Changes Saved!';
	var successMsg = 'Task details has been updated successfully.';
	var method = 'PATCH';
	modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, method);
	});
	// Call Edit meeting modal/*data-meeting_id="{{ $meeting->id }}"
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
		var redirectUrl = '/meeting_minutes/view_meeting/' + {{$meeting->id}} + '/view';
		var successMsg = 'Meeting details has been updated successfully.';
		var method = 'PATCH';
		modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
	});
});
</script>
@endsection