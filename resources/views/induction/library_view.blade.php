@extends('layouts.main_layout')

@section('page_dependencies')
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/select2/select2.min.css">
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Tasks Library</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 10px"></th>
						<th>{{$dept->name}}</th>
                        <th>Order No</th>
                        <th>Description</th>
                        <th>Required upload</th>
                        <th></th>
                        <th style="width: 40px"></th>
                    </tr> 
                    @if (!empty($libraries))
                    @foreach($libraries as $library)
                    <tr id="modules-list">
                        <td nowrap>
                            <button type="button" id="edit_leave" class="btn btn-primary  btn-xs" data-toggle="modal" 
							data-target="#edit-library_tasks-modal" data-id="{{ $library->id }}" 
							data-order_no="{{ $library->order_no }}" data-description="{{ $library->description }}" 
							data-upload_required="{{ $library->upload_required }}" data-dept_id="{{ $library->dept_id }}"
							> <i class="fa fa-pencil-square-o">
                                </i> Edit</button>
                        </td>
                        <td>{{ $library->deptname }} </td>
                        <td>{{ $library->order_no }} </td>
                        <td>{{ $library->description }} </td>
                        <td>{{ (!empty($library->upload_required) && $library->upload_required == 2) ? "Yes" : "No" }} </td>
                        <td>
                            <!--   leave here  -->
                            <button type="button" id="view_ribbons" class="btn {{ (!empty($library->active) && $library->active == 1) ? " btn-danger " : "btn-success " }}
							  btn-xs" onclick="postData({{$library->id}}, 'actdeac');"><i class="fa {{ (!empty($library->active) && $library->active == 1) ?
							  " fa-times " : "fa-check " }}"></i> {{(!empty($library->active) && $library->active == 1) ? "De-Activate" : "Activate"}}</button>
                        </td>

                        <td><button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#delete-contact-warning-modal"><i class="fa fa-trash"></i> Delete Task</button></td>
                    </tr>
                     @endforeach
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
                <button type="button" id="add-new-leave" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-new-task-modal">Add Task</button>
            </div>
        </div>
    </div>
    <!-- Include add new prime rate modal -->
    @include('induction.partials.add_new_task') 
    @include('induction.partials.edit_task')
    <!-- Include delete warning Modal form-->
	 @if (count($libraries) > 0)
		 @include('contacts.partials.task_warning_action', ['modal_title' => 'Delete Task', 'modal_content' => 'Are you sure you want to delete this Task? This action cannot be undone.'])
    @endif
</div>

  @endsection
<!-- Ajax form submit -->

@section('page_script')
<script src="/custom_components/js/modal_ajax_submit.js"></script>
<!-- Select2 -->
<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
<script>
    function postData(id, data) {
       if (data == 'actdeac') location.href = "/induction/library_tasks_activate/" + id;
    }
    function deleteRecord() {
            
        }
    $(function () {
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
            var deptID = btnEdit.data('dept_id');
            var modal = $(this);
            modal.find('#order_no').val(orderNo);
            modal.find('#upload_required').val(uploadRequired);
            modal.find('#description').val(Description);
            modal.find('#dept_id').val(deptID);
        });
        
        $('#add_library_task').on('click', function () {
            var strUrl = '/induction/add_library_task';
            var objData = {
                order_no: $('#add-new-task-modal').find('#order_no').val()
                , description: $('#add-new-task-modal').find('#description').val()
                , upload_required: $('#add-new-task-modal').find('#upload_required').val()
                , dept_id: $('#add-new-task-modal').find('#dept_id').val()
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
                , dept_id: $('#edit-library_tasks-modal').find('#dept_id').val()
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