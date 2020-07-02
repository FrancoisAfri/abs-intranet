<div id="add-minutes-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-minutes-form">
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
                        <label for="employee_id" class="col-sm-3 control-label">Attendee (internal)</label>
                        <div class="col-sm-9">
                            <select class="form-control select2" style="width: 100%;" id="employee_id" name="employee_id">
                                <option selected="selected" value="0">*** Select An Internal Attendee ***</option>
                                @foreach($attendees as $attendee)
                                    <option value="{{ $attendee->hr_id }}">{{ $attendee->first_name.' '.$attendee->surname}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
					<div class="form-group">
                        <label for="client_id" class="col-sm-3 control-label">Attendee (external)</label>
                        <div class="col-sm-9">
                            <select class="form-control select2" style="width: 100%;" id="client_id" name="client_id">
    							<option selected="selected" value="0">*** Select An External Attendee ***</option>
    							@if (!empty($externalAttendees))
								@foreach($externalAttendees as $externalAttendee)
    								<option value="{{ $externalAttendee->id }}">{{ $externalAttendee->full_name }}</option>
    							@endforeach
								@endif
							</select>
                        </div>
					</div>
                    <div class="form-group">
                        <label for="minutes" class="col-sm-3 control-label">Minutes</label>

                        <div class="col-sm-9">
                            <div class="input-group">
							<textarea rows="10" cols="100" class="form-control" id="minutes" name="minutes"  placeholder="Enter the Minutes...">{{ old('minutes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="save-minute" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
</div>
    <!-- /.modal-dialog -->     