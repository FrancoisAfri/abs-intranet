<div id="edit-user-modal" class="modal modal-default fade">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-user-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Component  </h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                    @if (isset($view_by_admin) && $view_by_admin === 1)
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
                                                class="form-control" onchange="divDDOnChange(this, 'hr_person_id', 'view_users')">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    <div class="form-group">
                        <label for="first_name" class="col-sm-2 control-label">First Name </label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <input type="text" class="form-control" id="first_name" name="first_name"
                                       value="{{ $user->person->first_name }}" placeholder="First Name"
                                       required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="surname" class="col-sm-2 control-label">Surname</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <input type="text" class="form-control" id="surname" name="surname"
                                       value="{{ $user->person->surname }}" placeholder="Surname" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="employee_number" class="col-sm-2 control-label">Employee Number</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <input type="text" class="form-control" id="employee_number"
                                       name="employee_number" value="{{ $user->person->employee_number }}"
                                       placeholder="Employee Number">
                            </div>
                        </div>
                    </div>
                    {{--add leave porfile--}}
                    <div class="form-group">
                        <label for="leave_profile" class="col-sm-2 control-label">Leave Profile</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-black-tie"></i>
                                </div>
                                <select name="leave_profile" class="form-control">
                                    <option value="">*** Select leave Profile ***</option>
                                    @foreach($leave_profile as $leave_profiles)
                                        <option value="{{ $leave_profiles->id }}" {{ ($user->person->leave_profile == $leave_profiles->id) ?
                                                ' selected' : '' }}>{{ $leave_profiles->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    @if (isset($view_by_admin) && $view_by_admin === 1)
                        <div class="form-group">
                            <label for="position" class="col-sm-2 control-label">Position</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-black-tie"></i>
                                    </div>
                                    <select name="position" class="form-control">
                                        <option value="">*** Select a Position ***</option>
                                        @foreach($positions as $position)
                                            <option value="{{ $position->id }}" {{ ($user->person->position == $position->id) ? ' selected' : '' }}>{{ $position->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (isset($view_by_admin) && $view_by_admin === 1)
                        <div class="form-group">
                            <label for="action" class="col-sm-2 control-label">Reports to</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user-circle"></i>
                                    </div>
                                    <select id="manager_id" name="manager_id" class="form-control select2"  style="width: 100%;">
                                        <option selected="selected" value="" >*** Select a Manager ***</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" {{ ($user->person->manager_id == $employee->id) ? ' selected' : '' }}>{{ $employee->first_name . ' ' . $employee->surname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="action" class="col-sm-2 control-label">Second manager in charge </label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user-circle"></i>
                                    </div>
                                    <select id="second_manager_id" name="second_manager_id" class="form-control select2"  style="width: 100%;">
                                        <option selected="selected" value="" >*** Select a Manager ***</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" {{ ($user->person->second_manager_id == $employee->id) ? ' selected' : '' }}>{{ $employee->first_name . ' ' . $employee->surname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="cell_number" class="col-sm-2 control-label">Cell Number</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-phone"></i>
                                </div>
                                <input type="text" class="form-control" name="cell_number" value="{{ $user->person->cell_number }}" data-inputmask='"mask": "(999) 999-9999"' placeholder="Cell Number" data-mask>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-envelope"></i>
                                </div>
                                <input type="email" class="form-control" id="email" name="email" value="{{ $user->person->email }}" placeholder="Email" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="res_address" class="col-sm-2 control-label">Address</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-home"></i>
                                </div>
                                <textarea name="res_address" class="form-control" placeholder="Address">{{ $user->person->res_address }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="res_suburb" class="col-sm-2 control-label">Suburb</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-home"></i>
                                </div>
                                <input type="text" class="form-control" id="res_suburb" name="res_suburb" value="{{ $user->person->res_suburb }}" placeholder="Suburb">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="res_city" class="col-sm-2 control-label">City</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-home"></i>
                                </div>
                                <input type="text" class="form-control" id="res_city" name="res_city" value="{{ $user->person->res_city }}" placeholder="City">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="res_postal_code" class="col-sm-2 control-label">Postal Code</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-home"></i>
                                </div>
                                <input type="number" class="form-control" id="res_postal_code" name="res_postal_code" value="{{ $user->person->res_postal_code }}" placeholder="Postal Code">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="res_province_id" class="col-sm-2 control-label">Province</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-home"></i>
                                </div>
                                <select name="res_province_id" class="form-control">
                                    <option value="">*** Select Your Province ***</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province->id }}" {{ ($user->person->res_province_id == $province->id) ? ' selected' : '' }}>{{ $province->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="date_of_birth" class="col-sm-2 control-label">Date of Birth</label>
                        <div class="col-sm-10">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control datepicker" name="date_of_birth" placeholder="  dd/mm/yyyy" value="{{ ($user->person->date_of_birth) ? date('d/m/Y',$user->person->date_of_birth) : '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="gender" class="col-sm-2 control-label">Gender</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-venus-mars"></i>
                                </div>
                                <select name="gender" class="form-control">
                                    <option value="">*** Select Your gender ***</option>
                                    <option value="1" {{ ($user->person->gender === 1) ? ' selected' : '' }}>Male</option>
                                    <option value="2" {{ ($user->person->gender === 2) ? ' selected' : '' }}>Female</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="id_number" class="col-sm-2 control-label">ID Number</label>

                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-book"></i>
                                </div>
                                <input type="number" class="form-control" id="id_number" name="id_number" value="{{ $user->person->id_number }}" placeholder="ID Number">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="passport_number" class="col-sm-2 control-label">Passport Number</label>

                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-book"></i>
                                </div>
                                <input type="text" class="form-control" id="passport_number" name="passport_number" value="{{ $user->person->passport_number }}" placeholder="Passport Number">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="marital_status" class="col-sm-2 control-label">Marital Status</label>

                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-venus-mars"></i>
                                </div>
                                <select name="marital_status" class="form-control">
                                    <option value="">*** Select Your Marital Status ***</option>
                                    @foreach($marital_statuses as $marital_status)
                                        <option value="{{ $marital_status->id }}" {{ ($user->person->marital_status == $marital_status->id) ? ' selected' : '' }}>{{ $marital_status->value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ethnicity" class="col-sm-2 control-label">Ethnicity</label>

                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-bar-chart"></i>
                                </div>
                                <select name="ethnicity" class="form-control">
                                    <option value="">*** Select Your Ethnic Group ***</option>
                                    @foreach($ethnicities as $ethnicity)
                                        <option value="{{ $ethnicity->id }}" {{ ($user->person->ethnicity == $ethnicity->id) ? ' selected' : '' }}>{{ $ethnicity->value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>



{{--                    <input type="hidden" value="{{ $asset->id }}" name="asset_id" id="asset_id">--}}

                    <input type="hidden" value="Un Allocated" name="asset_status" id="asset_status">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="edit-component" class="btn btn-warning"><i class="fa fa-cloud-upload"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



