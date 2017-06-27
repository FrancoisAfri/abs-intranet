@extends('layouts.main_layout')

@section('page_dependencies')
        <!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<!-- Select2 -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/select2/select2.min.css">

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
							<div class="box-body">
								<div class="box-body" style="max-height: 190px; overflow-y: scroll;">
									<div class="form-group">
										<label for="Meeting Name" class="col-sm-2 control-label">Title</label>
										<div class="col-sm-10">
											<div>
												<input type="text" class="form-control" id="meeting_name" name="meeting_name" value="{{ $meeting->meeting_name }}" readonly>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="Meeting Date" class="col-sm-2 control-label">Date</label>
										<div class="col-sm-10">
											<div>
											<input type="text" class="form-control datepicker" name="meeting_date" placeholder="  dd/mm/yyyy" value="{{ date('d F Y', $meeting->meeting_date) }}" readonly>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="Meeting Location" class="col-sm-2 control-label">Location</label>
										<div class="col-sm-10">
											<div>
												<input type="text" class="form-control" id="meeting_location" name="meeting_location" value="{{$meeting->meeting_location}}" readonly>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="Meeting Agenda" class="col-sm-2 control-label">Agenda</label>
										<div class="col-sm-10">
											<div>
											<textarea rows="4" cols="50" class="form-control" id="meeting_agenda" name="meeting_agenda" readonly>{{$meeting->meeting_agenda}}</textarea>
											</div>
										</div>
									</div>
								</div>
							</div>
						<!-- /.box-body -->
							<div class="box-footer">
								<button type="button" id="add-expenditure" class="btn btn-success pull-right"
										data-toggle="modal" data-target="#edit-meeting-modal"
										data-meeting_id="{{ $meeting->id }}" 
										data-meeting_name="{{ $meeting->meeting_name }}" 
										data-meeting_location="{{ $meeting->meeting_location }}" 
										data-meeting_agenda="{{ $meeting->meeting_agenda }}">Edit Details
								</button>
							</div>
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
													<td>{{ $minute->minutesPerson->first_name  .' '. $minute->minutesPerson->surname }}</td>
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
								<th>Name</th>
								<th>Type</th>
								<th>Attendance</th>
								<th>Apology</th>
							</tr>
							@if(!empty($meeting->attendees))
								@foreach($meeting->attendees as $attendee)
									<tr>
										<td>{{ $attendee->attendeesInfo->first_name  .' '. $attendee->attendeesInfo->surname}}</td>
										<td>{{ !empty($attendee->client_id) ? 'Client' : 'Employee' }}</td>
										<td>{{ ($attendee->attendance == 1) ? 'Yes' : 'No'  }}</td>
										<td>{{ $attendee->apology}}</td>
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
    </div>
@endsection

@section('page_script')
			<!-- Select2 -->
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>

	<!-- bootstrap datepicker -->
	<script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>

	<!-- Ajax form submit -->
	<script src="/custom_components/js/modal_ajax_submit.js"></script>

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
function postData(id, data)
{
	if (data == 'print_minutes')
		location.href = "/meeting/prnt_meeting/" + id;
	else if (data == 'email_minutes')
		location.href = "/meeting/email_meeting/" + id;
}
$(function () {
	
	 //show/hide fields on radio button toggles
	/*$('#attendance_yes, #attendance_no').on('ifChecked', function(){
		var companyType = hideFields();
	});*/
	
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
});
</script>
@endsection