@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/select2/select2.min.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Create Induction</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>
			<form method="POST" action="/induction/client_add">
            {{ csrf_field() }}
            <!-- /.box-header -->
            <div class="box-body">
			<div style="overflow-X:auto;">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 10px"></th>
                        <th>Order No</th>
                        <th>Description</th>
                        <th>Person Responsible</th>
                        <th>Start Date</th>
                        <th>Due Date</th>
						<th>Escalation Person</th>
                        <th>Required upload</th>
                    </tr> 
                    @if (!empty($libraries))
                    @foreach($libraries as $library)
                    <tr>
                        <td><input type="checkbox" class="checkbox" id="selected_{{ $library->id }}" name="selected_{{ $library->id }}" value="1"></td>
                        <td><input type="number" class="form-control" style="width:70px;" id="order_no_{{ $library->id }}" name="order_no_{{ $library->id }}" value="{{$library->order_no }}"></td>
                        <td style="width:150px;">{{ $library->description }} <input type="hidden" id="description_{{ $library->id }}" name="description_{{ $library->id }}" value="{{ $library->description }}"></td>
                        <td><select class="form-control select2" style="width:150px;" id="employee_id_{{ $library->id }}" name="employee_id_{{ $library->id }}" required>
						<option selected="selected" value="0">*** Select Person Responsible ***</option>
						@foreach($users as $user)
							<option value="{{ $user->id }}">{{ $user->first_name.' '.$user->surname}}</option>
						@endforeach
						</select> </td>
                        <td> <input type="text" class="form-control datepicker" id="start_date_{{ $library->id }}" name="start_date_{{ $library->id }}" value="" placeholder="Start Date..." required></td>
                        <td> <input type="text" class="form-control datepicker" id="due_date_{{ $library->id }}" name="due_date_{{ $library->id }}" value="" placeholder="Due Date..." required></td>
                        <td><select class="form-control select2" style="width:150px;" id="escalation_id_{{ $library->id }}" name="escalation_id_{{ $library->id }}" required>
							<option selected="selected" value="0">*** Select escalation Person ***</option>
							@foreach($users as $user)
								<option value="{{ $user->id }}">{{ $user->first_name.' '.$user->surname}}</option>
							@endforeach
						</select></td>
                        <td style="width:20px;">{{ (!empty($library->upload_required) && $library->upload_required == 2) ? "Yes" : "No" }} <input type="hidden" id="upload_required_{{ $library->id }}" name="upload_required_{{ $library->id }}" value="{{ $library->upload_required }}"></td>
                    </tr>
                     @endforeach
					<tr>
                        <td colspan="2">Induction Title</td>
						<td colspan="2"><input type="text" class="form-control" style="width:250px;" id="title" name="title" value=""  placeholder="Enter induction Title..." required></td>
						<td>Client</td>
						<td colspan="3"><select class="form-control select2" style="width:400px;" id="company_id" name="company_id" required>
						<option selected="selected" value="0">*** Select a Client ***</option>
						@foreach($companies as $company)
							<option value="{{ $company->id }}">{{ $company->name}}</option>
						@endforeach
						</select> </td>
                    </tr>
					@else
						<tr id="modules-list">
							<td colspan="5">
								<div class="alert alert-danger alert-dismissable">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No task to display, please start by adding a new task to the library. </div>
							</td>
						</tr> 
					@endif
            </table>
            </div>
            <!-- /.box-body -->
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-adn"></i>  Submit</button>
            </div>
        </div>
		</form>
        </div>
    </div>
</div>

@endsection
<!-- Ajax form submit -->

@section('page_script')
<script src="/custom_components/js/modal_ajax_submit.js"></script>
<!-- Select2 -->
<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
 <!-- bootstrap datepicker -->
<script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>
    function postData(id, data) {
       if (data == 'actdeac') location.href = "/induction/library_tasks_activate/" + id;
    }
    $(function () {
		  //Date picker
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });
		            //Initialize Select2 Elements
            $(".select2").select2();

        //Tooltip
        $('[data-toggle="tooltip"]').tooltip();
        //Vertically center modals on page
        function reposition() {
            var modal = $(this)
                , dialog = modal.find('.modal-dialog');
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
        //pass module data to the leave type -edit module modal
        var tasksId;
        $('#edit-library_tasks-modal').on('show.bs.modal', function (e) {
            //console.log('kjhsjs');
            var btnEdit = $(e.relatedTarget);
            tasksId = btnEdit.data('id');
            var orderNo = btnEdit.data('order_no');
            var uploadRequired = btnEdit.data('upload_required');
            var Description = btnEdit.data('description');
            var modal = $(this);
            modal.find('#order_no').val(orderNo);
            modal.find('#upload_required').val(uploadRequired);
            modal.find('#description').val(Description);
        });
        
        $('#add_library_task').on('click', function () {
            var strUrl = '/induction/add_library_task';
            var objData = {
                order_no: $('#add-new-task-modal').find('#order_no').val()
                , description: $('#add-new-task-modal').find('#description').val()
                , upload_required: $('#add-new-task-modal').find('#upload_required').val()
                , _token: $('#add-new-task-modal').find('input[name=_token]').val()
            };
            var modalID = 'add-new-task-modal';
            var submitBtnID = 'add_library_task';
            var redirectUrl = '/induction/tasks_library';
            var successMsgTitle = 'Changes Saved!';
            var successMsg = 'Task successfully Added to Library.';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });
        $('#update-library_tasks').on('click', function () {
            var strUrl = '/induction/tasks_library_edit/' + tasksId;
            var objData = {
                order_no: $('#edit-library_tasks-modal').find('#order_no').val()
                , description: $('#edit-library_tasks-modal').find('#description').val()
                , upload_required: $('#edit-library_tasks-modal').find('#upload_required').val()
                , _token: $('#edit-library_tasks-modal').find('input[name=_token]').val()
            };
            var modalID = 'edit-library_tasks-modal';
            var submitBtnID = 'update-library_tasks';
            var redirectUrl = '/induction/tasks_library';
            var successMsgTitle = 'Changes Saved!';
            var successMsg = 'Task type has been changed successfully.';
            var method = 'PATCH';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, method);
        });
    });
</script>
 @endsection