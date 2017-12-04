<div id="add-oil-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-oil-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"> Add new vehicle Oil Log</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                     <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Driver </label>
                        <div class="col-sm-8">
                            <select class="form-control select2" style="width: 100%;"
                                    id="reported_by" name="reported_by">
                                <option value="0">*** Select Driver  ***</option>
                                @foreach($employees as $driver)
                                    <option value="{{ $driver->id }}">{{ $driver->first_name . ' ' . $driver->surname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                     <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Document Number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="odometer_reading" name="odometer_reading" value="0"
                                   placeholder="Enter Contact Person" required>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="date" class="col-sm-2 control-label">Date </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="date_of_incident" name="date_of_incident"
                                   value="{{ old('date') }}" placeholder="Select  date of incident   ...">
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('property_type') ? ' has-error' : '' }}">
                        <label for="property_type" class="col-sm-2 control-label"> Tanks and Other </label>          
                            <div class="col-sm-8">
                                <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_product"
                                        name="property_type" value="1" checked>                              Tank                              
                                </label>
                                    <label class="radio-inline"><input type="radio" id="rdo_product" name="property_type" value="2">
                                        Other 
                                </label>
                            </div>
                    </div>


                    <div class="form-group">
                        <label for="Status" class="col-sm-2 control-label">Tanks  </label>
                        <div class="col-sm-8">
                            <select id="severity" name="severity" class="form-control">
                                <option value="0">*** Select a Severity ***</option>
                                <option value="1"> Tanks1</option>
                                <option value="2"> Tanks2 </option>
                                <option value="3"> Tanks3 </option>
                            </select>
                        </div>
                    </div>

                     <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Litres </label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="odometer_reading" name="odometer_reading" value="0"
                                   placeholder="Enter Contact Person" required>
                        </div>
                    </div>


                      <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Hours Reading </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="claim_number" name="claim_number" value=""
                                   placeholder="Enter Name" required>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="description" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-8">

                            <textarea class="form-control" id="description" name="description"
                                      placeholder="Enter description..." rows="3">{{ old('description') }}</textarea>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Captured By</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="Cost" name="Cost" value="0"
                                   placeholder="Enter Name" required>
                        </div>
                    </div>
                 
                   <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Person Responsible </label>
                        <div class="col-sm-8">
                            <select class="form-control select2" style="width: 100%;"
                                    id="reported_by" name="reported_by">
                                <option value="0">*** Select Driver  ***</option>
                                @foreach($employees as $driver)
                                    <option value="{{ $driver->id }}">{{ $driver->first_name . ' ' . $driver->surname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <input type="hidden" id="valueID" name="valueID"
                           value="{{ !empty($maintenance->id) ? $maintenance->id : ''}}">

                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" id="add_vehicleincidents" class="btn btn-warning"><i class="fa fa-cloud-upload"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

           