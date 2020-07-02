@extends('layouts.main_layout')

@section('page_dependencies')
        <!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<!-- Select2 -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/select2/select2.min.css">

@endsection

@section('content')
    <div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">Users: {{$group->group_name}}</h3>
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
						<tr><th>Name</th><th></th></tr>
						@if(!empty($group->groupUsers))
							@foreach($group->groupUsers as $user)
								<tr>
									<td>{{ $user->users->first_name  .' '. $user->users->surname}}</td>
									<td nowrap>
										<button type="button" id="actdeac" class="btn {{ (!empty($user->status) && $user->status == 1) ? "btn-danger" : "btn-success" }} btn-xs pull-right" onclick="postData({{$user->id}}, 'actdeac');"><i class="fa {{ (!empty($user->status) && $user->status == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($user->status) && $user->status == 1) ? "De-Activate" : "Activate"}}</button>
									</td>
								</tr>
							@endforeach
						@else
							<tr>
								<td colspan="3">
									<div class="alert alert-danger alert-dismissable">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No user to display, please start by adding one. </div>
								</td>
							</tr> 
						@endif
						</tbody>
					</table>
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
					<button type="button" id="add-users" class="btn btn-success pull-right" data-toggle="modal"
							data-target="#add-group-users-modal" data-group_id="{{ $group->id }}">Add User(s)
					</button>
				</div>
			</div>
		</div>
        <!-- Include add expenditure and add income modals -->
            @include('dms.partials.add_group_users', ['modal_title' => 'Add Users To This Group'])
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
	if (data == 'actdeac')
		location.href = "/dms/group/users/actdeac/" + id;
}
$(function () {
	
	//Initialize Select2 Elements
    $(".select2").select2();
	document.getElementById("back_button").onclick = function () {
			location.href = "/dms/group_admin";	};
			
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
	$('#add-group-users-modal').on('show.bs.modal', function (e) {
		var btnEnd = $(e.relatedTarget);
		groupID = btnEnd.data('group_id');
		var modal = $(this);
		modal.find('#group_id').val(groupID);
	});
	// Add attendee Submit
	$('#save-group-users').on('click', function() {
		var strUrl = '/dms/add_group_users';
		var formName = 'add-group-users-form';
		var modalID = 'add-group-users-modal';
		var submitBtnID = 'save-group-users';
		var redirectUrl = '/dms/group/users/' + {{$group->id}} + '/view';
		var successMsgTitle = 'User(s) Saved!';
		var successMsg = 'User(s) Has Been Successfully Saved!';
		modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
	});
});
</script>
@endsection