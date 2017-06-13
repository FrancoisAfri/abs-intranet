<div id="edit-tasks-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" name="end-task-form" enctype="multipart/form-data">
			<input type="hidden" name="task_id" id="task_id" value="">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">End Task</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group">
                        <label for="notes" class="col-sm-2 control-label">Order #</label>
                        <div class="col-sm-10">
						<input type="number" class="form-control" style="width:70px;" id="order_no" name="order_no" value="">
                        </div>
                    </div>
					<div class="form-group">
                        <label for="description" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10">
						<textarea rows="4" cols="50" class="form-control" id="description" name="description"></textarea>
                        </div>
                    </div>
					<div class="form-group">
                        <label for="notes" class="col-sm-2 control-label">Person Responsible</label>
                        <div class="col-sm-10">
						<select class="form-control select2" style="width:150px;" id="employee_id" name="employee_id">
						<option selected="selected" value="0">*** Select Person Responsible ***</option>
						@foreach($employees as $employee)
							<option value="{{ $employee->id }}">{{ $employee->first_name.' '.$employee->surname}}</option>
						@endforeach
						</select> </div>
                    </div>
					<div class="form-group">
                        <label for="notes" class="col-sm-2 control-label">Administrator</label>
                        <div class="col-sm-10">
						<select class="form-control select2" style="width:150px;" id="administrator_id" name="administrator_id">
						<option selected="selected" value="0">*** Select Person Administrator ***</option>
						@foreach($employees as $employee)
							<option value="{{ $employee->id }}">{{ $employee->first_name.' '.$employee->surname}}</option>
						@endforeach
						</select></div>
                    </div>
					<div class="form-group">
                        <label for="notes" class="col-sm-2 control-label">Required upload</label>
                        <div class="col-sm-10">
						<select id="upload_required" name="upload_required" class="form-control">
                                    <option value="1">No</option>
                                    <option value="2">Yes</option>
								</select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Close</button>
                    <button type="button" id="update-task" class="btn btn-primary"><i class="fa fa-upload"></i> Update</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>