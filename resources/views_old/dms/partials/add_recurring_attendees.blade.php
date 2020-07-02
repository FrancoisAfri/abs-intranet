<div id="add-recurring-attendee-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-recurring-attendee-form">
			<input type="hidden" name="meeting_id" id="meeting_id" value="">               
			   {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ $modal_title }}</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-exp-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group">
                        <label for="employee_id[]" class="col-sm-3 control-label">Attendee</label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-briefcase"></i>
                                </div>
								<select class="form-control select2" multiple="multiple" data-placeholder="Select Employee" style="width: 100%;" id="employee_id" name="employee_id[]">
								<option  value="0">*** Select Attendee ***</option>
								@foreach($employees as $employee)
									<option value="{{ $employee->id }}">{{ $employee->first_name.' '.$employee->surname}}</option>
								@endforeach
								</select>
                            </div>
							
							
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="save-recurring-attendee" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>