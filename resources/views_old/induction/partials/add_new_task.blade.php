<div id="add-new-task-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add_new_leavetype-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add New Task</h4>
                </div>
                <div class="modal-body">
                    <div id="task-invalid-input-alert"></div>
                    <div id="task-success-alert"></div>
					<div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Choose {{$dept->name}}</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <select class="form-control select2" style="width:150px;" id="dept_id" name="dept_id">
						<option selected="selected" value="0">*** Select {{$dept->name}} ***</option>
						@foreach($deparments as $deparment)
							<option value="{{ $deparment->id }}">{{ $deparment->name}}</option>
						@endforeach
						</select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Order No</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="number" class="form-control" id="order_no" name="order_no" value="" placeholder="Enter Order No">
                            </div>
                        </div>
                    </div> 
					<div class="form-group">
                        <label for="path" class="col-sm-3 control-label">Description</label>
                        <div class="col-sm-9">
                            <div class="input-group">
							<textarea rows="4" cols="50" class="form-control" id="description" name="description" placeholder="Enter Description"></textarea>
							</div>
                        </div>
                    </div>
					<div class="form-group">
                        <label for="path" class="col-sm-3 control-label">Upload Required</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <select id="upload_required" name="upload_required" class="form-control">
                                    <option value="1">No</option>
                                    <option value="2">Yes</option>
                                    
								</select>
                            </div>
                        </div>
                    </div>
					
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add_library_task" class="btn btn-primary">Add Task</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>