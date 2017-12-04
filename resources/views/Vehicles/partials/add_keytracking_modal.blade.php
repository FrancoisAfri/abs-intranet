<div id="add_keytrackinge-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-new-key-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add key Details vvvvv</h4>
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

                    <div class="form-group ">
                        <label for="key_status" class="col-sm-3 control-label">Key Status</label>
                        <div class="col-sm-8">

                            <select name="key_status" id="key_status" onChange="changetextbox();" class="form-control">
                                <option value="0">*** Select a Key Status ***</option>
                                <option value="1"> In Use</option>
                                <option value="2"> Reallocated</option>
                                <option value="3"> Lost</option>
                                <option value="4"> In Safe</option>
                            </select>

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
                    <div class="form-group lost-field">
                        <label for="path" class="col-sm-3 control-label">Date Lost </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="date_lost" name="date_lost"
                                   value="{{ old('date_lost') }}" placeholder="Select  issue date ...">
                        </div>
                    </div>

                    <div class="form-group lost-field{{ $errors->has('description') ? ' has-error' : '' }}">
                        <label for="days" class="col-sm-3 control-label">Reason for loss</label>
                        <div class="col-sm-8">


                            <textarea class="form-control" id="reason" name="reason"
                                      placeholder="Enter a Brief Description of the leave Application..."
                                      rows="4">{{ old('reason') }}</textarea>

                        </div>
                    </div>


                    <div class="form-group safe-field">
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
                                <option value="0">*** Select a Person ***</option>
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
                                   value="{{ old('employee') }}" placeholder="Select  User ..." readonly="">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add-key" class="btn btn-warning"><i class="fa fa-cloud-upload"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
           