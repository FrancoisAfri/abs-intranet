<div id="add-licence-allocation" class="modal modal-default fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-licence_allocation-form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add licence</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
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
											onchange="divDDOnChange(this, null, 'add-licence-allocation')">
									</select>
								</div>
							</div>
						</div>
                    @endforeach
                    <input type="hidden" name="licence_id" id="licence_id" value="{{ $LicenceDetails->id }}">
                    <div class="form-group {{ $errors->has('hr_person_id') ? ' has-error' : '' }}">
                        <label for="hr_person_id" class="col-sm-2 control-label">Users</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user-circle"></i>
                                </div>
                                <select class="form-control select2" multiple="multiple" style="width: 100%;"
                                        id="hr_person_id" name="hr_person_id[]">
                                    <option value="">*** Select an Users ***</option>
                                    @foreach($users as $employee)
                                        <option value="{{ $employee->id }} ">{{$employee->first_name . ' ' . $employee->surname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add-licence_allocation" class="btn btn-warning"><i
                                class="fa fa-cloud-upload"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>




