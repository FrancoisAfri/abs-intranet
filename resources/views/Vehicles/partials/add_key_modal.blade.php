<div id="add-key-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-key-form">
                {{ csrf_field() }}


                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"> Add key Details</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>


                    <div class="form-group Single-field">
                        <label for="path" class="col-sm-3 control-label">Key Number </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="key_number" name="key_number" value=""
                                   placeholder="Enter Key Number" required>
                        </div>
                    </div>

                    <input type="hidden" id="valueID" name="valueID"
                           value="{{ !empty($maintenance->id) ? $maintenance->id : ''}}">

                    <div class="form-group">
                        <label for="key_type" class="col-sm-3 control-label">Key Type</label>
                        <div class="col-sm-8">

                            <select name="key_type" id="key_type" class="form-control">
                                <option value="0">*** Select a Key Type ***</option>
                                <option value="1"> Main Key</option>
                                <option value="2"> Spare Key</option>
                                <option value="3"> Remote</option>
                            </select>

                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="key_status" class="col-sm-3 control-label">Key Status</label>
                        <div class="col-sm-8">

                            <select name="key_status" id="key_status" class="form-control">
                                <option value="0">*** Select a Key Status ***</option>
                                <option value="1"> In Use</option>
                                <option value="2"> Reallocated</option>
                                <option value="3"> Lost</option>
                                <option value="4"> In Safe</option>
                            </select>

                        </div>
                    </div>

                    <div class="form-group lost-field{{ $errors->has('reason_loss') ? ' has-error' : '' }}">
                        <label for="days" class="col-sm-3 control-label">Reason For Loss</label>
                        <div class="col-sm-8">


                            <textarea class="form-control" id="reason_loss" name="reason_loss"
                                      placeholder="Enter Reason For Loss..."
                                      rows="4">{{ old('reason_loss') }}</textarea>

                        </div>
                    </div>

                    <div class="form-group lost-field">
                        <label for="path" class="col-sm-3 control-label">Date Lost </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="date_lost" name="date_lost"
                                   value="{{ old('date_lost') }}" placeholder="Select date lost ...">
                        </div>
                    </div>

                    <div class="form-group notes-field{{ $errors->has('description') ? ' has-error' : '' }}">
                        <label for="days" class="col-sm-3 control-label">Description</label>
                        <div class="col-sm-8">


                            <textarea class="form-control" id="description" name="description"
                                      placeholder="Enter a Brief Description of the leave Application..."
                                      rows="4">{{ old('description') }}</textarea>

                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('key') ? ' has-error' : '' }}">
                        <label for="Leave_type" class="col-sm-3 control-label"> Issued to </label>

                        <div class="col-sm-8">
                            <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_user"
                                                                                          name="key" value="1" checked>
                                Employee </label>
                            <label class="radio-inline"><input type="radio" id="rdo_safe" name="key" value="2"> Safe
                            </label>
                        </div>

                    </div>

                    <div class="form-group user-field">
                        <label for="issued_to" class="col-sm-3 control-label">Employee </label>
                        <div class="col-sm-8">
                            <select class="form-control select2" style="width: 100%;" id="issued_to" name="issued_to">
                                <option value="0">*** Select a Employee ***</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}"> {{ !empty($employee->first_name . ' ' . $employee->surname) ? $employee->first_name . ' ' . $employee->surname : ''}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group safe-field">
                        <label for="safe_name" class="col-sm-3 control-label">Safe Name</label>
                        <div class="col-sm-8">
                            <select class="form-control select2" style="width: 100%;" id="safe_name" name="safe_name">
                                <option value="0">*** Select a Safe ***</option>
                                @foreach($safe as $safe)
                                    <option value="{{ $employee->id }}">{{ !empty($safe->name) ? $safe->name : ''}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group safe-field">
                        <label for="safe_controller" class="col-sm-3 control-label">Safe Controller</label>
                        <div class="col-sm-8">
                            <select class="form-control select2" style="width: 100%;" id="safe_controller"
                                    name="safe_controller">
                                <option value="0">*** Select a Person ***</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}"> {{ !empty($employee->first_name . ' ' . $employee->surname) ? $employee->first_name . ' ' . $employee->surname : ''}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="path" class="col-sm-3 control-label">Date Issued </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="date_issued" name="date_issued"
                                   value="{{ old('date_issued') }}" placeholder="Select  issue date ...">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="issued_by" class="col-sm-3 control-label"> Issued By</label>
                        <div class="col-sm-8">
                            <select class="form-control select2" style="width: 100%;" id="issued_by" name="issued_by">
                                <option value="">*** Select a Person ***</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}"> {{ !empty($employee->first_name . ' ' . $employee->surname) ? $employee->first_name . ' ' . $employee->surname : ''}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="path" class="col-sm-3 control-label">Captured By </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="employee" name="employee"
                                   value="{{ !empty($name)  ? $name : ''}}" placeholder="Select  User ..." readonly="">
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add-key-card" class="btn btn-warning"><i class="fa fa-cloud-upload"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<script type="text/javascript">

</script>
           