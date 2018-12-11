<div id="add-new-step-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-new-step-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add New Step</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
					<div class="form-group">
                        <label for="step_number" class="col-sm-2 control-label"> Step number</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="step_number" name="step_number"
                                   value="{{$newstep}}"
                                   placeholder="" readonly="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="step_name" class="col-sm-2 control-label">Step Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="step_name" name="step_name" value=""
                                   placeholder="Enter step name">
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
					@foreach($division_levels as $division_level)
						<div class="form-group manual-field{{ $errors->has('division_level_' . $division_level->level) ? ' has-error' : '' }}">
							<label for="{{ 'division_level_' . $division_level->level }}"
								   class="col-sm-2 control-label">{{ $division_level->name }}</label>

							<div class="col-sm-10">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-black-tie"></i>
									</div>
									<select id="{{ 'division_level_' . $division_level->level }}"
											name="{{ 'division_level_' . $division_level->level }}"
											class="form-control"
											onchange="divDDOnChange(this, null, 'add-new-step-modal')">
									</select>
								</div>
							</div>
						</div>
                    @endforeach
					<div class="form-group{{ $errors->has('hr_person_id') ? ' has-error' : '' }}">
						<label for="hr_person_id" class="col-sm-2 control-label">Employee</label>

						<div class="col-sm-10">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-user"></i>
								</div>
								<select id="hr_person_id" name="hr_person_id" class="form-control select2" style="width: 100%;">
									<option value="">*** Select an Employee ***</option>
									@foreach($employees as $employee)
										<option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
									@endforeach
								</select>
							</div>
						</div>
                    </div>				
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add_step" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>