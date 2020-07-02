<div id="add-tank-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-module-form">
                {{ csrf_field() }}


                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Fuel Tank</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="box-body" id="view_users">
                        @foreach($division_levels as $division_level)
                            <div class="form-group{{ $errors->has('division_level_' . $division_level->level) ? ' has-error' : '' }}">
                                <label for="{{ 'division_level_' . $division_level->level }}"
                                       class="col-sm-2 control-label">{{ $division_level->name }}</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-black-tie"></i>
                                        </div>
                                        <select id="{{ 'division_level_' . $division_level->level }}"
                                                name="{{ 'division_level_' . $division_level->level }}"
                                                class="form-control"
                                                onchange="divDDOnChange(this, null, 'view_users')">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="form-group">
                            <label for="tank_name" class="col-sm-2 control-label">Tank Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="tank_name" name="tank_name" value=""
                                       placeholder="Enter Tank name" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tank_location" class="col-sm-2 control-label">Tank Location</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="tank_location" name="tank_location" value=""
                                       placeholder="Enter tank location" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tank_description" class="col-sm-2 control-label">Tank Description</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="tank_description" name="tank_description"
                                       value=""
                                       placeholder="Enter tank description" required>
                            </div>
                        </div>
                        {{--  <div class="form-group">
                            <label for="tank_capacity" class="col-sm-2 control-label">Tank Capacity</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" min="0" step="0.001" id="tank_capacity"
                                       name="tank_capacity" value="" onchange="convertMoney(this.value, 4);"
                                       placeholder="Enter tank capacity" required>
                            </div>
                        </div>  --}}
                        <div class="form-group">
                            <label for="tank_capacity" class="col-sm-2 control-label">Tank Capacity</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="tank_capacity" name="tank_capacity"
                                       value="{{ old('tank_capacity') }}" onchange="convertMoney(this.value, 1);"
                                       placeholder="Enter the tank capacity...">
                            </div>
                        </div>
                        <div class="form-group safe-field">
                            <label for="tank_manager" class="col-sm-2 control-label">Tank Manager </label>
                            <div class="col-sm-8">
                                <select class="form-control select2" style="width: 100%;" id="tank_manager"
                                        name="tank_manager">
                                    <option value="0">*** Select a Tank Manager ***</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}"> {{ !empty($employee->first_name . ' ' . $employee->surname) ? $employee->first_name . ' ' . $employee->surname : ''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" id="add-fueltank" class="btn btn-warning"><i
                                    class="fa fa-cloud-upload"></i> Save
                        </button>
                    </div>
            </form>
        </div>
    </div>
</div>
</div>



           