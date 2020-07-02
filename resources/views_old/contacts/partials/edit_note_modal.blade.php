<div id="edit-note-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-note-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Note for:  {{ $company->name}} </h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                     <div class="form-group{{ $errors->has('originator_type_update') ? ' has-error' : '' }}">
                        <label for="originator_type" class="col-sm-2 control-label"> Originator</label>
                        <div class="col-sm-9">
							<select class="form-control select2" style="width: 100%;" id="originator_type_update" name="originator_type_update">
								<option value="">*** Select Response ***</option>
								<option value="1" selected>From us</option>
								<option value="2">Client</option>
							</select>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('company_id') ? ' has-error' : '' }}">
                        <label for="company_id" class="col-sm-2 control-label"> Company</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user-circle"></i>
                                </div>
                                   <input type="text" class="form-control" id="company_update" name="company_update" value="{{ $company->name }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('hr_person_id_update') ? ' has-error' : '' }}">
                        <label for="hr_person_id" class="col-sm-2 control-label">Company Representative</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user-circle"></i>
                                </div>
                                <select class="form-control select2" style="width: 100%;" id="hr_person_id_update" name="hr_person_id_update">
                                    <option value="">*** Select a Contact Representative ***</option>
                                    @foreach($contactPeople as $contact)
                                    <option value="{{ $contact->id }}">{{ $contact->first_name . ' ' . $contact->surname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('employee_id_update') ? ' has-error' : '' }}">
                        <label for="employee_id" class="col-sm-2 control-label">Our Representative</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user-circle"></i>
                                </div>
                                <select class="form-control select2" style="width: 100%;" id="employee_id_update" name="employee_id_update">
                                    <option value="">*** Select an Employee ***</option>
                                    @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->surname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group " style="display: block;">
						<label for="date" class="col-sm-2 control-label">Date </label>  
						<div class="col-sm-4">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control datepicker" id="date_update" name="date_update" value="{{ old('date_update') }}" placeholder="Click to Select a Date...">
                            </div>
                        </div>
                        <label for="time" class="col-sm-1 control-label">Time</label>  
						<div class="col-sm-3">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                               <input type="text" class="form-control" id="time_update" name="time_update" value="{{ old('time_update') }}" placeholder="Select time...">
                            </div>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('communication_method_update') ? ' has-error' : '' }}">
						<label for="communication_method" class="col-sm-2 control-label">Communication Method</label>
						<div class="col-sm-8">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-pinterest-p"></i>
								</div>
								<select class="form-control select2" style="width: 100%;" id="communication_method_update" name="communication_method_update">
									<option value="">*** Select Method ***</option>
									<option value="1">Telephone</option>
									<option value="2">Meeting/Interview</option>
									<option value="3">Email</option>
									<option value="4">Fax</option>
									<option value="5">SMS</option>
								</select>
							</div>
						</div>
                    </div>
                    <div class="form-group{{ $errors->has('rensponse_type_update') ? ' has-error' : '' }}">
                        <label for="rensponse_type" class="col-sm-2 control-label"> Response</label>
                        <div class="col-sm-9">
							<select class="form-control select2" style="width: 100%;" id="rensponse_type_update" name="rensponse_type_update">
								<option value="">*** Select Response ***</option>
								<option value="1" selected>Unanswered / Unattended</option>
								<option value="2">Answered / Attended</option>
							</select>
                        </div>
                    </div>
                    <div class="form-group notes-field{{ $errors->has('notes_update') ? ' has-error' : '' }}">
						<label for="days" class="col-sm-2 control-label">Note</label>
						<div class="col-sm-8">
						   <div class="input-group">
								<div class="input-group-addon">
								   <i class="fa fa-ticket"></i>
								</div>
								<textarea class="form-control" id="notes_update" name="notes_update" placeholder="Enter a Note Description ..." rows="3">{{ old('notes') }}</textarea>
							</div>
                        </div>
                    </div>
					<div class="form-group {{ $errors->has('next_action_update') ? ' has-error' : '' }}">
						<label for="next_action" class="col-sm-2 control-label">Follow-up Task</label>
						<div class="col-sm-8">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-pinterest-p"></i>
								</div>
								<select class="form-control select2" style="width: 100%;" id="next_action_update" name="next_action_update">
									<option value="">*** Select Option ***</option>
									<option value="1">Yes</option>
									<option value="2">No</option>
									
								</select>
							</div>
						</div>
					</div>  
				</div>  
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				<button type="button" id="edit_note" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Update</button>
			</div>
            </form>

            </div>
         </div>
    </div>
        
           