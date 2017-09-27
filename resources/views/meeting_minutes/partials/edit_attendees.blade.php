<div id="edit-attendees-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-attendees-form">
			<input type="hidden" name="attendee_id" id="attendee_id" value="">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ $modal_title }}</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group internal-attendee">
                        <label for="supplier_id" class="col-sm-3 control-label">Attendee</label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-briefcase"></i>
                                </div>
                                <select class="form-control select2" style="width: 100%;" id="employee_id" name="employee_id">
                                <option selected="selected" value="0">*** Select Attendee ***</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->first_name.' '.$employee->surname}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group external-attendee">
                        <label for="client_id" class="col-sm-3 control-label">Attendee</label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-briefcase"></i>
                                </div>
								<select class="form-control select2" style="width: 100%;" id="client_id" name="client_id">
								<option value="0">*** Select Attendee ***</option>
								@if (!empty($externalAttendees))
								@foreach($externalAttendees as $externalAttendee)
									<option value="{{ $externalAttendee->id }}">{{ $externalAttendee->full_name }}</option>
								@endforeach
								@endif
								</select>
                            </div>
                        </div>
                    </div>
					<div class="form-group">
                        <label for="attendance" class="col-sm-3 control-label">Attendee</label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-briefcase"></i>
                                </div>
								<div class="col-sm-10">
                                <label class="radio-inline"><input type="radio" id="attendance_yes_edit" name="attendance_edit" value="1"> Yes</label>
                                <label class="radio-inline"><input type="radio" id="attendance_no_edit" name="attendance_edit" value="2"> No</label>
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
                    <button type="button" id="update-attendees" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
</div>          