<div id="add-task-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-task-form" enctype="multipart\form-data">
               <input type="hidden" name="meeting_id" id="meeting_id" value=""> 
			   {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ $modal_title }}</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group">
                        <label for="description" class="col-sm-3 control-label">Description</label>

                        <div class="col-sm-9">
                            <div class="input-group">
							<textarea rows="4" cols="50" class="form-control" id="description" name="description"  placeholder="Enter the Description...">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="due_date" class="col-sm-3 control-label">Due Date</label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control datepicker" id="due_date" name="due_date" value="{{ old('due_date') }}" placeholder="Click to Select a Due Date...">
                            </div>
                        </div>
                    </div>

                        <div class="form-group " style="display: block;">
                          <label for="time" class="col-sm-3 control-label">Due Time</label>  

                            <div class="col-sm-9">
                                <div class="input-group">
                                    
                             <input type="text" class="form-control" id="due_time" name="due_time" value="{{ old('due_time') }}" placeholder="Select Start time...">
                            </div>
                          </div>
                        </div>

                     <div class="form-group {{ $errors->has('employee_id') ? ' has-error' : '' }}">
                            <label for="employee_id" class="col-sm-3 control-label">Responsible Person</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <select class="form-control select2" multiple="multiple" style="width:170px;" id="employee_id" name="employee_id[]">
                                        
                                    <option value="0">*** Select a attendee ***</option>
                                    @foreach($attendees as $attendee)
                                    <option value="{{ $attendee->hr_id }}">{{ $attendee->first_name.' '.$attendee->surname}}</option>
                                @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="save-task" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
</div>
    <!-- /.modal-dialog -->