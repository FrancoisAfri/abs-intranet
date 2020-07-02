@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/select2/select2.min.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Induction Title: <b>{{$induction->induction_title}}</b></h3></br>
                    <h3 class="box-title">Client Name: <b>{{$induction->ClientName->name}}</b></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
				<div style="overflow-X:auto;">
				<table class="table table-bordered">
					 <tr><th style="width: 10px"></th><th style="width: 10px"></th><th style="width: 10px">Order #</th><th>Description</th><th>Person Responsible</th><th>Status</th><th>Notes</th><th>Document</th></tr>
                    @if (!empty($tasks))
						@foreach($tasks as $task)
						 <tr id="categories-list">
						  <td>@if(!empty($task->status) && $task->status < 4 && $induction->status == 1)
							<button type="button" id="view_kpi" class="btn btn-xs" data-toggle="modal" data-target="#edit-tasks-modal"
							  data-task_id="{{ $task->task_id }}" 
							  data-employee_id="{{ $task->employee_id }}" 
							  data-administrator_id="{{ $task->administrator_id }}"
							  data-order_no="{{ $task->order_no }}"
							  data-description="{{ $task->description }}"
							  data-start_date="{{ $task->start_date }}"
							  data-due_date="{{ $task->due_date }}"
							  data-upload_required="{{ $task->upload_required }}"
							  
							  >Edit</button>
							@endif 
							</td>
						 <td>@if(!empty($task->administrator_id) && $task->administrator_id == $user->person->id && $task->status < 4 && $induction->status == 1)           
							  <button type="button" id="end-task-button" class="btn btn-sm btn-default btn-flat pull-right" data-toggle="modal" data-target="#end-task-modal"
							  data-task_id="{{ $task->task_id }}" data-employee_id="{{ $task->employee_id }}" 
							  data-upload_required="{{ $task->upload_required }}" >End</button>
							 @endif</td>
						  <td>{{ (!empty($task->order_no)) ?  $task->order_no : ''}} </td>
						  <td>{{ (!empty($task->description)) ?  $task->description : ''}} </td>
						  <td>{{ (!empty($task->hr_fist_name)) && (!empty($task->hr_surname)) ?  $task->hr_fist_name." ".$task->hr_surname : ''}} </td>
						  <td>{{ (!empty($task->status)) ?  $taskStatus[$task->status] : ''}} </td>
						  <td>{{ (!empty($task->status)) ?  $task->notes : ''}} </td>
						  @if(!empty($task->emp_doc))
							<td><a class="btn btn-default btn-flat btn-block" href="{{ Storage::disk('local')->url("tasks/$task->emp_doc") }}" target="_blank"><i class="fa fa-file-pdf-o"></i> Click Here</a></td>
                          @else
                            <td><a class="btn btn-default btn-flat btn-block"><i class="fa fa-exclamation-triangle"></i>No Document Was Uploaded</a></td>
                          @endif
						</tr>
						@endforeach
                    @else
						<tr id="categories-list">
						<td colspan="8">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            No task to display, please start by adding a new task.
                        </div>
						</td>
						</tr>
                    @endif
					<tr id="categories-list">
						<td colspan="8">
						@if($induction->status == 1)
                        <div>
                           <button type="button" id="complete-induction-button" class="btn btn-sm btn-default btn-flat pull-right" data-toggle="modal" data-target="#comp-induction-modal"
							  data-induction_id="{{ $induction->id }}">Complete Induction</button>
                        </div>
						@else
						<div class="alert alert-danger alert-dismissable">
						Induction Note : {{$induction->notes}} </br>
						<a class="btn btn-default btn-flat btn-block" href="{{ Storage::disk('local')->url("induction/$induction->completion_document") }}" target="_blank"><i class="fa fa-file-pdf-o"></i> Completion Document</a>
						</div>
						@endif
						</td>
					</tr>
					</table>
					</div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                </div>
            </div>
        </div>
		@include('tasks.partials.edit_task')
		@include('tasks.partials.end_task')
		@include('induction.partials.complete_induction')
    </div>
@endsection
@section('page_script')
            <!-- ChartJS 1.0.1 -->
    <script src="/bower_components/AdminLTE/plugins/chartjs/Chart.min.js"></script>
    <!-- Admin dashboard charts ChartsJS -->
    <script src="/custom_components/js/admindbcharts.js"></script>
    <!-- matchHeight.js
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.0/jquery.matchHeight-min.js"></script>-->
	   <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
	<!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
 <!-- bootstrap datepicker -->
<script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>

    <script>
		$(function () {
            //initialise matchHeight on widgets
            //$('.same-height-widget').matchHeight();

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
			document.getElementById("notes").placeholder = "Enter Task Note or Summary";
			//Post end task form to server using ajax (add)
			var taskID;
			var employeeID;
			var uploadRequired;
			// Call end Task Modal
            $('#end-task-modal').on('show.bs.modal', function (e) {
                var btnEnd = $(e.relatedTarget);
                taskID = btnEnd.data('task_id');
                employeeID = btnEnd.data('employee_id');
                uploadRequired = btnEnd.data('upload_required');
                var modal = $(this);
                modal.find('#task_id').val(taskID);
                modal.find('#employee_id').val(employeeID);
                modal.find('#upload_required').val(uploadRequired);
            });
			// End Task Submit
            $('#end-task').on('click', function() {
                var strUrl = '/task/end';
                var formName = 'end-task-form';
                var modalID = 'end-task-modal';
                var submitBtnID = 'end-task';
                var redirectUrl = '/';
                var successMsgTitle = 'Task Ended!';
                var successMsg = 'Task has been Successfully ended!';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
			// Call Edit task modal
			$('#edit-tasks-modal').on('show.bs.modal', function (e) {
				var btnEdit = $(e.relatedTarget);
				taskID = btnEdit.data('task_id');
				var orderNo = btnEdit.data('order_no');
				var uploadRequired = btnEdit.data('upload_required');
				var Description = btnEdit.data('description');
				var EmployeeID = btnEdit.data('employee_id');
				var AdministratorID = btnEdit.data('administrator_id');
				var modal = $(this);
				modal.find('#order_no').val(orderNo);
				modal.find('#upload_required').val(uploadRequired);
				modal.find('#description').val(Description);
				modal.find('#employee_id').val(EmployeeID);
				modal.find('#administrator_id').val(AdministratorID);
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
                var redirectUrl = '/induction/' + {{$induction->id}} + '/view';
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
            var redirectUrl = '/induction/' + {{$induction->id}} + '/view';
            var successMsgTitle = 'Changes Saved!';
            var successMsg = 'Task details has been updated successfully.';
            var method = 'PATCH';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, method);
        });
        });
    </script>
@endsection