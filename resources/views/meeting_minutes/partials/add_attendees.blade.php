<div id="add-attendee-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-attendee-form" enctype="multipart\form-data">
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
                    <!-- <div class="form-group">
                        <label for="supplier_id" class="col-sm-3 control-label">Attendee</label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-briefcase"></i>
                                </div>
								<select class="form-control select2"  multiple="multiple" style="width:170px;" id="employee_id" name="employee_id[]">
								<option selected="selected" value="0">*** Select Attendee ***</option>
								@foreach($employees as $employee)
									<option value="{{ $employee->id }}">{{ $employee->first_name.' '.$employee->surname}}</option>
								@endforeach
								</select>
                            </div>
                        </div>
                    </div> -->
                    <div class="form-group {{ $errors->has('employee_id') ? ' has-error' : '' }}">
                        <label for="employee_id" class="col-sm-3 control-label">Attendee (internal)</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-briefcase"></i>
                                </div>
                                <select class="form-control select2" multiple="multiple" style="width: 100%;" id="employee_id" name="employee_id[]" data-placeholder="Select (an) Employee(s)">
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->surname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    @if($externalAttendees)
                        <div class="form-group {{ $errors->has('client_id') ? ' has-error' : '' }}">
                            <label for="client_id" class="col-sm-3 control-label">Attendee (external)</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-briefcase"></i>
                                    </div>
                                    <select class="form-control select2" multiple="multiple"style="width: 100%;" id="client_id" name="client_id[]" data-placeholder="Select External Attendee">
                                        @foreach($externalAttendees as $externalAttendee)
                                            <option value="{{ $externalAttendee->id }}">{{ $externalAttendee->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif
					<div class="form-group">
                        <label for="attendance" class="col-sm-3 control-label">Attendee</label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-briefcase"></i>
                                </div>
								<div class="col-sm-10">
                                <label class="radio-inline"><input type="radio" id="attendance_yes" name="attendance" value="1" checked> Yes</label>
                                <label class="radio-inline"><input type="radio" id="attendance_no" name="attendance" value="2"> No</label>
                            </div>
                            </div>
                        </div>
                    </div>
					<div class="form-group no_field">
                        <label for="apology" class="col-sm-3 control-label">Apology</label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-briefcase"></i>
                                </div>
								<textarea rows="8" cols="100" class="form-control" id="apology" name="apology" placeholder="Enter Apology..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="save-attendee" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>