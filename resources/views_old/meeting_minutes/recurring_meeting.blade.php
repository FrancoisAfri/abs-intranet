@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Recurring Meeintgs</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
				<div style="overflow-X:auto;">
				<table class="table table-bordered">
					 <tr><th style="width: 10px"></th><th>Title</th><th>Location</th><th>Agenda</th><th style="text-align: center">Attendees</th><th style="width: 40px"></th></tr>
                    @if (!empty($recurringMeetings))
						@foreach($recurringMeetings as $recurringMeeting)
						 <tr id="recurring-list">
						  <td><button type="button" id="edit_recurring_meeting" class="btn btn-primary  btn-xs" 
						  data-toggle="modal" 
						  data-target="#edit-recurring-meeting-modal" 
						  data-id="{{ $recurringMeeting->id }}"
						  data-meeting_title="{{ $recurringMeeting->meeting_title }}"
						  data-meeting_location="{{ $recurringMeeting->meeting_location }}"
						  data-meeting_agenda="{{ $recurringMeeting->meeting_agenda }}"><i class="fa fa-pencil-square-o"></i> Edit</button></td>
						  <td>{{!empty($recurringMeeting->meeting_title) ? $recurringMeeting->meeting_title : ''}}</td>
						  <td>{{!empty($recurringMeeting->meeting_location) ? $recurringMeeting->meeting_location : ''}}</td>
						  <td>{{!empty($recurringMeeting->meeting_agenda) ? $recurringMeeting->meeting_agenda : ''}}</td>
						  <td style="text-align: center"><a href="{{ '/meeting_minutes/recurring/' . $recurringMeeting->id . '/view' }}" class="product-title">{{$recurringMeeting->recurringAttendees->count()}}</a></td>
						  <td nowrap>
                              <button type="button" id="view_meeting" class="btn {{ (!empty($recurringMeeting->status) && $recurringMeeting->status == 1) ? "btn-danger" : "btn-success" }} btn-xs" onclick="postData({{$recurringMeeting->id}}, 'actdeac');"><i class="fa {{ (!empty($recurringMeeting->status) && $recurringMeeting->status == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($recurringMeeting->status) && $recurringMeeting->status == 1) ? "De-Activate" : "Activate"}}</button>
                          </td>
						</tr>
						@endforeach
                    @else
						<tr id="kpis-list">
						<td colspan="5">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            No meeting to display, please start by adding a new meeting.
                        </div>
						</td>
						</tr>
                    @endif
				</table>
                </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="button" id="add-recurring-meeting" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-new-recurring-modal">Add Meeting</button>
                </div>
            </div>
        </div>

        <!-- Include add new modal -->
        @include('meeting_minutes.partials.add_recurring_meeting')
        @include('meeting_minutes.partials.edit_recurring_meeting')
    </div>
@endsection

@section('page_script')

<!-- Ajax dropdown options load -->
<script src="/custom_components/js/load_dropdown_options.js"></script>
<!-- Select2 -->
<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
<!-- Ajax form submit -->
<script src="/custom_components/js/modal_ajax_submit.js"></script>
<script>
function postData(id, data)
{
	if (data == 'actdeac')
		location.href = "/meeting_minutes/recurring/"  + id + "/actdect";
}
$(function () {
	
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
	var meetingID;
	
	// Call add attendee Modal
	$('#add-attendee-modal').on('show.bs.modal', function (e) {
		var btnEnd = $(e.relatedTarget);
		minuteID = btnEnd.data('meeting_id');
		var modal = $(this);
		modal.find('#meeting_id').val(minuteID);
	});
	// Add attendee Submit
	$('#save-recurring').on('click', function() {
		var strUrl = '/meeting_minutes/add_recurring_meeting';
		var formName = 'add-recurring-form';
		var modalID = 'add-new-recurring-modal';
		var submitBtnID = 'save-recurring';
		var redirectUrl = '/meeting_minutes/recurring';
		var successMsgTitle = 'Recurring Meeting Saved!';
		var successMsg = 'Recurring Meeting Has Been Successfully Saved!';
		modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
	});
	// Call Edit meeting modal/*data-meeting_id=""
	$('#edit-recurring-meeting-modal').on('show.bs.modal', function (e) {
		var btnEdit = $(e.relatedTarget);
		meetingID = btnEdit.data('id');
		var meetingTitle = btnEdit.data('meeting_title');
		var meetingLocation = btnEdit.data('meeting_location');
		var meetingAgenda = btnEdit.data('meeting_agenda');
		var modal = $(this);
		modal.find('#meeting_location').val(meetingLocation);
		modal.find('#meeting_title').val(meetingTitle);
		modal.find('#meeting_agenda').val(meetingAgenda);
		modal.find('#meeting_id').val(meetingID);
	});
	//Update meeting
	$('#update-recurring-meeting').on('click', function () {
		var strUrl = '/meeting_minutes/recurring/update/' + meetingID;
		var formName = 'edit-recurring-meeting-form';
		var modalID = 'edit-recurring-meeting-modal';
		var submitBtnID = 'update-recurring-meeting';
		var successMsgTitle = 'Changes Saved!';
		var redirectUrl = '/meeting_minutes/recurring';
		var successMsg = 'Meeting details has been updated successfully.';
		var method = 'PATCH';
		modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
	});
});
</script>
@endsection