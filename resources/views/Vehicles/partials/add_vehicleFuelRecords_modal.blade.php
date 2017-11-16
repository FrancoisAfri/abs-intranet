<div id="add-fuel-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-fuel-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"> Add new Fuel Record</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                     <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Driver </label>
                        <div class="col-sm-8">
                            <select class="form-control select2" style="width: 100%;"
                                    id="driver" name="driver">
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
                            <input type="text" class="form-control" id="document_number" name="document_number" value=" "
                                   placeholder="Enter Document Number" required>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="date" class="col-sm-2 control-label">Date </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="date" name="date"
                                   value="{{ old('date') }}" placeholder="Select  date   ...">
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('tank_type') ? ' has-error' : '' }}">
                        <label for="tank_type" class="col-sm-2 control-label"> Tanks and Other </label>          
                            <div class="col-sm-8">
                                <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_product"
                                        name="tank_type" value="1" checked>                              Tank                              
                             </label>
                                 <label class="radio-inline"><input type="radio" id="rdo_product" name="tank_type" value="2">
                                        Other 
                                </label>
                            </div>
                    </div>


                   
                     <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Tanks </label>
                        <div class="col-sm-8">
                            <select class="form-control select2" style="width: 100%;"
                                    id="tank_name" name="tank_name">
                                <option value="0">*** Select tank  ***</option>
                                <!-- @foreach($employees as $driver)
                                    <option value="{{ $driver->id }}">{{ $driver->first_name . ' ' . $driver->surname }}</option>
                                @endforeach -->
                            </select>
                        </div>
                    </div>

                     <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Litres </label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="litres" name="litres" value="0"
                                   placeholder="Enter Litres" required>
                        </div>
                    </div>


                      <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Hours Reading </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="hours_reading" name="hours_reading" value=""
                                   placeholder="Enter Hours Reading" required>
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
                            <input type="number" class="form-control" id="captured_by" name="captured_by" value="{{ $loggedInEmplID }}"
                                   placeholder="{{ $name }}" required readonly="">
                        </div>
                    </div>

                 
                   <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Person Responsible </label>
                        <div class="col-sm-8">
                            <select class="form-control select2" style="width: 100%;"
                                    id="rensonsible_person" name="rensonsible_person">
                                <option value="0">*** Select User  ***</option>
                                @foreach($employees as $user)
                                    <option value="{{ $user->id }}">{{ $user->first_name . ' ' . $user->surname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <input type="hidden" id="valueID" name="valueID"
                           value="{{ !empty($maintenance->id) ? $maintenance->id : ''}}">

                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" id="add_vehiclefuellog" class="btn btn-warning"><i class="fa fa-cloud-upload"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

           