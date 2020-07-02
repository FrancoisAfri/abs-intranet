<div id="edit-tank-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-tank-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Fleet Type</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

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
                                            class="form-control select2"
                                            onchange="divDDOnChange(this, null)"
                                            style="width: 100%;">
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
                    <div class="form-group">
                        <label for="tank_capacity" class="col-sm-2 control-label">Tank Capacity</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="tank_capacity" name="tank_capacity"
                                   value="{{ old('tank_capacity') }}" onchange="convert(this.value, 1);"
                                   placeholder="Enter the tank capacity...">
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label for="current_fuel_litres" class="col-sm-2 control-label">Add Litres</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="current_fuel_litres"
                                   name="current_fuel_litres" value="" onchange="convert(this.value, 2);"
                                   placeholder="Enter Fuel Litres" required>
                        </div>
                    </div> -->
                    <div class="form-group safe-field">
                        <label for="tank_manager" class="col-sm-2 control-label">Tank Manager </label>
                        <div class="col-sm-8">
                            <select class="form-control select2" style="width: 100%;" id="tank_manager"
                                    name="tank_manager">
                                <option value="0">*** Select a Tank Manager ***</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ !empty($tanks->tank_manager) && ($tanks->tank_manager == $employee->id) ? ' selected' : '' }}>{{ $employee->first_name . ' ' . $employee->surname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="edit_tank" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    function convert(value, type) {
        if (value.length > 1) {
            var str = value.toString().split('.');
            if (str[0].length >= 4) {
                str[0] = str[0].replace(/(\d)(?=(\d{3})+$)/g, '$1,');
            }
            if (str[1] && str[1].length >= 5) {
                str[1] = str[1].replace(/(\d{3})/g, '$1 ');
            }
            value = str + '. 00';
        }
        else value = value + '. 00';
        if (type == 1) $('#tank_capacity').val(value);
        else if (type == 2) $('#current_fuel_litres').val(value);

        //console.log(value);
    }

</script>


        
           