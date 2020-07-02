<div id="edit-step-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-step-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Step Details</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group">
                        <label for="step_number" class="col-sm-2 control-label"> Step number</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="step_number" name="step_number" value=""
                                   placeholder="" readonly="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="step_name" class="col-sm-2 control-label">Step Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="step_name" name="step_name" value=""
                                   placeholder="Enter step name" required>
						</div>
                    </div>
					<div class="form-group">
                        <label for="max_amount" class="col-sm-2 control-label"> Max Amount</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="max_amount" name="max_amount"
                                   value=""
                                   placeholder="Enter Max Amount">
                        </div>
                    </div>
					<div class="form-group{{ $errors->has('approval_types') ? ' has-error' : '' }}">
						<label for="approval_types" class="col-sm-2 control-label">Approval Type</label>
						<div class="col-sm-10">
							<label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_roles" name="approval_types" value="1"> Role</label>
							<label class="radio-inline"><input type="radio" id="rdo_emps" name="approval_types" value="2"> Employees</label>
						</div>
					</div>
					@foreach($division_levels as $division_level)
						<div class="form-group emp-field{{ $errors->has('division_level_' . $division_level->level) ? ' has-error' : '' }}">
							<label for="{{ 'division_level_' . $division_level->level }}" class="col-sm-2 control-label">{{ $division_level->name }}</label>
							<div class="col-sm-10">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-black-tie"></i>
									</div>
									<select id="{{ 'division_level_' . $division_level->level }}" name="{{ 'division_level_' . $division_level->level }}" class="form-control select2" onchange="divDDOnChange(this, null, 'edit-step-modal')" style="width: 100%;">
									</select>
								</div>
							</div>
						</div>
                    @endforeach
					<div class="form-group emp-field{{ $errors->has('hr_person_id') ? ' has-error' : '' }}">
						<label for="hr_person_id" class="col-sm-2 control-label">Employee</label>
						<div class="col-sm-10">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-user"></i>
								</div>
								<select id="hr_person_id" name="hr_person_id" class="form-control" style="width: 100%;">
									<option value="">*** Select an Employee ***</option>
									@foreach($employees as $employee)
										<option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
									@endforeach
								</select>
							</div>
						</div>
                    </div>
					<div class="form-group role-field{{ $errors->has('role_id') ? ' has-error' : '' }}">
						<label for="role_id" class="col-sm-2 control-label">Roles</label>
						<div class="col-sm-10">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-user"></i>
								</div>
								<select id="role_id" name="role_id" class="form-control" style="width: 100%;">
									<option value="">*** Select a Role ***</option>
									@foreach($roles as $role)
										<option value="{{ $role->role_id }}">{{ $role->role_name }}</option>
									@endforeach
								</select>
							</div>
						</div>
                    </div>
					<div class="form-group {{ $errors->has('enforce_upload') ? ' has-error' : '' }}">
						<label for="enforce_upload" class="col-sm-2 control-label">Enforce Docunent(s) Upload</label>
						<div class="col-sm-10">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-user"></i>
								</div>
								<select id="enforce_upload" name="enforce_upload" class="form-control" style="width: 100%;">
									<option value="">*** Select an Option ***</option>
									<option value="1">Single Docunent</option>
									<option value="2">Multiple Docunents</option>
								</select>
							</div>
						</div>
                    </div>
					<div class="form-group role-field{{ $errors->has('division_id') ? ' has-error' : '' }}">
						<label for="division_id" class="col-sm-2 control-label">Company</label>
						<div class="col-sm-10">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-user"></i>
								</div>
								<select id="division_id" name="division_id" class="form-control select2" style="width: 100%;">
									<option value="">*** Select a Company ***</option>
									@foreach($divisionFives as $divisionFive)
										<option value="{{ $divisionFive->id }}">{{ $divisionFive->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="update-step" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
</div>