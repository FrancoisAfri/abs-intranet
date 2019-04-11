<div id="add-private-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-private-form">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Private Usage for {{ !empty($tank->tank_name) ? $tank->tank_name : ''}}</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="box-body" id="view_users">
						<div class="form-group search-field" id="vehicle-field">
                            <label for="vehicle_id" class="col-sm-2 control-label">Vehicle</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <select class="form-control select2" style="width: 100%;"
                                            id="vehicle_id" name="vehicle_id">
                                        <option value="0">*** Select a Vehicle ***</option>
                                        @foreach($vehicleDetails as $vehicle)
                                            <option value="{{ $vehicle->id }}">{{ $vehicle->fleet_number."|".$vehicle->vehicle_registration }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="document_no" class="col-sm-2 control-label">Document Number </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="document_no" name="document_no" value=""
                                       placeholder="Enter Document Number" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="document_date" class="col-sm-2 control-label">Document Date </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="documents_date" name="documents_date"
                                       value=""
                                       placeholder="Enter document date " required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="usage_date" class="col-sm-2 control-label">Usage Date </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="usage_date" name="usage_date" value=""
                                       placeholder="Enter Usage up date ">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="litres_new" class="col-sm-2 control-label">Litres</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="litres_new" name="litres_new" value=""
                                       placeholder="Enter Litres ">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="description" name="description" value=""
                                       placeholder="Enter description ">
                            </div>
                        </div>
                        <div class="form-group safe-field">
                            <label for="received_by" class="col-sm-2 control-label">Received By</label>
                            <div class="col-sm-8">
                                <select class="form-control select2" style="width: 100%;" id="received_by"
                                        name="received_by">
                                    <option value="0">*** Select an Employee ***</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}"> {{ !empty($employee->first_name . ' ' . $employee->surname) ? $employee->first_name . ' ' . $employee->surname : ''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="captured_by" class="col-sm-2 control-label">Captured By</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="captured_by" name="descaptured_bycription"
                                       value="{{$name}}"
                                       placeholder="" readonly="">
                            </div>
                        </div>
                        <div class="form-group safe-field">
                            <label for="person_responsible" class="col-sm-2 control-label">Person Responsible</label>
                            <div class="col-sm-8">
                                <select class="form-control select2" style="width: 100%;" id="person_responsible"
                                        name="person_responsible">
                                    <option value="0">*** Select an Employee ***</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}"> {{ !empty($employee->first_name . ' ' . $employee->surname) ? $employee->first_name . ' ' . $employee->surname : ''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <input type="hidden" id="tank_id" name="tank_id" value="{{$ID}}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" id="add_privateUse" class="btn btn-warning"><i
                                    class="fa fa-cloud-upload"></i> Save
                        </button>
                    </div>
            </form>
        </div>
    </div>
</div>
</div>