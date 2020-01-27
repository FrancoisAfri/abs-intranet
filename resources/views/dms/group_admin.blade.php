@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Group Administration</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
				<div style="overflow-X:auto;">
				<table class="table table-bordered">
					<tr><th style="width: 10px"></th><th>Name</th><th>Users</th><th style="width: 40px"></th></tr>
                    @if (!empty($groupAdmins))
						@foreach($groupAdmins as $groupAdmin)
							<tr id="recurring-list">
								<td><button type="button" id="edit_group" class="btn btn-primary  btn-xs" 
									data-toggle="modal" 
									data-target="#edit-group-modal" 
									data-id="{{ $groupAdmin->id }}"
									data-group_name="{{ $groupAdmin->group_name }}""><i class="fa fa-pencil-square-o"></i> Edit</button></td>
								<td>{{!empty($groupAdmin->group_name) ? $groupAdmin->group_name : ''}}</td>
								<td><a href="{{ '/dms/group/users/' . $groupAdmin->id . '/view' }}" class="product-title">{{$groupAdmin->groupUsers->count()}}</a></td>
								<td nowrap>
									<button type="button" id="view_users" class="btn {{ (!empty($groupAdmin->status) && $groupAdmin->status == 1) ? "btn-danger" : "btn-success" }} btn-xs" onclick="postData({{$groupAdmin->id}}, 'actdeac');"><i class="fa {{ (!empty($groupAdmin->status) && $groupAdmin->status == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($groupAdmin->status) && $groupAdmin->status == 1) ? "De-Activate" : "Activate"}}</button>
								</td>
							</tr>
						@endforeach
                    @else
						<tr id="group-list">
						<td colspan="4">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            No group to display, please start by adding a new group.
                        </div>
						</td>
						</tr>
                    @endif
				</table>
                </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="button" id="add-group" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-new-group-modal">Add Group</button>
                </div>
            </div>
        </div>

        <!-- Include add new modal -->
        @include('dms.partials.add_group')
        @include('dms.partials.edit_group')
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
		location.href = "/dms/group/"  + id + "/actdect";
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
	var groupID;
	
	// Call add attendee Modal
	$('#add-attendee-modal').on('show.bs.modal', function (e) {
		var btnEnd = $(e.relatedTarget);
		minuteID = btnEnd.data('meeting_id');
		var modal = $(this);
		modal.find('#meeting_id').val(minuteID);
	});
	// Add attendee Submit
	$('#save-group').on('click', function() {
		var strUrl = '/dms/add_group';
		var formName = 'add-group-form';
		var modalID = 'add-new-group-modal';
		var submitBtnID = 'save-group';
		var redirectUrl = '/dms/group_admin';
		var successMsgTitle = 'Group Saved!';
		var successMsg = 'Group Has Been Successfully Saved!';
		modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
	});
	// Call Edit meeting modal/*data-meeting_id=""
	$('#edit-group-modal').on('show.bs.modal', function (e) {
		var btnEdit = $(e.relatedTarget);
		groupID = btnEdit.data('id');
		var groupName = btnEdit.data('group_name');
		var modal = $(this);
		modal.find('#group_name').val(groupName);
	});
	//Update meeting
	$('#update-group').on('click', function () {
		var strUrl = '/dms/group/update/' + groupID;
		var formName = 'edit-group-form';
		var modalID = 'edit-group-modal';
		var submitBtnID = 'update-group';
		var successMsgTitle = 'Changes Saved!';
		var redirectUrl = '/dms/group_admin';
		var successMsg = 'Group details has been updated successfully.';
		var method = 'PATCH';
		modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
	});
});
</script>
@endsection