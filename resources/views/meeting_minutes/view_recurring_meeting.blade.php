@extends('layouts.main_layout')

@section('page_dependencies')
        <!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<!-- Select2 -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/select2/select2.min.css">

@endsection

@section('content')
    <div class="row">
		<div style="width:700px; align: center">
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">Meeting Attendees for {{$recurring->meeting_title}}</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i
									class="fa fa-minus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i
									class="fa fa-remove"></i></button>
					</div>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<table class="table table-striped">
						<tbody>
						<tr><th>Name</th><th>Type</th></tr>
						@if(!empty($recurring->recurring_attendees))
							@foreach($recurring->recurring_attendees as $attendee)
								<tr>
									<td>{{ $attendee->attendeesInfo->first_name  .' '. $attendee->attendeesInfo->surname}}</td>
									<td>Employee</td>
								</tr>
							@endforeach
						@else
							<tr>
								<td colspan="2">
									<div class="alert alert-danger alert-dismissable">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No attendee to display, please start by adding one. </div>
								</td>
							</tr> 
						@endif
						</tbody>
					</table>
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<button type="button" id="add-attendee" class="btn btn-success pull-right" data-toggle="modal"
							data-target="#add-recurring-attendee-modal" data-meeting_id="{{ $recurring->id }}">Add Attendee
					</button>
				</div>
			</div>
		</div>
        <!-- Include add expenditure and add income modals -->
            @include('meeting_minutes.partials.add_recurring_attendees', ['modal_title' => 'Add Attendees To This Meeting'])
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

function postData(id, data)
{
	if (data == 'print_minutes')
		location.href = "/meeting/prnt_meeting/" + id;
	else if (data == 'email_minutes')
		location.href = "/meeting/email_meeting/" + id;
}
$(function () {
	
	//Initialize Select2 Elements
    $(".select2").select2();
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
	$('#save-recurring-attendee').on('click', function() {
		var strUrl = '/meeting/add_recurring_attendees';
		var formName = 'add-recurring-attendee-form';
		var modalID = 'add-recurring-attendee-modal';
		var submitBtnID = 'save-recurring-attendee';
		var redirectUrl = '/meeting_minutes/recurring/' + {{$recurring->id}} + '/view';
		var successMsgTitle = 'Attendee Saved!';
		var successMsg = 'Attendee Has Been Successfully Saved!';
		modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
	});
	$('#update-meeting').on('click', function () {
		
		var strUrl = '/meeting/update/' + minuteID;
		var formName = 'edit-meeting-form';
		var modalID = 'edit-meeting-modal';
		var submitBtnID = 'update-meeting';
		var successMsgTitle = 'Changes Saved!';
		var redirectUrl = '/meeting_minutes/view_meeting/' + {{$recurring->id}} + '/view';
		var successMsg = 'Meeting details has been updated successfully.';
		var method = 'PATCH';
		modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
	});
});
</script>
@endsection