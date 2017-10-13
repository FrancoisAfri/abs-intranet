<div id="add-maintenance-modal" class="modal modal-default fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add_new_site-form" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <i class="fa fa-truck pull-left"></i>
                     <h4 class="modal-title">Add Vehicle General Details </h4>
                </div>
                <div class="modal-body" >
                    <div id="leave-invalid-input-alert"></div>
                    <div id="success-alert"></div>

                      
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Person Responsible for Maintenance:</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <select class="form-control select2" style="width: 100%;" id="responsible_for_maintenance" name="responsible_for_maintenance">
                                    <option value="">*** Select User  ***</option>
                                    @foreach($Vehicle_types as $Vehicle)
                                        <option value="{{ $Vehicle->id }}">{{ $Vehicle->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                      <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Vehicle Make</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-modx"></i>
                                </div>
                                <select class="form-control select2" style="width: 100%;" id="vehicle_make" name="vehicle_make">
                                    <option value="">*** Select a Vehicle Make ***</option>
                                    @foreach($vehiclemake as $Vehicle)
                                        <option value="{{ $Vehicle->id }}">{{ $Vehicle->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Vehicle Model</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-bullseye"></i>
                                </div>
                                <select class="form-control select2" style="width: 100%;" id="vehicle_model" name="vehicle_model">
                                    <option value="">*** Select a Vehicle Model ***</option>
                                    @foreach($vehiclemodel as $Vehicle)
                                        <option value="{{ $Vehicle->id }}">{{ $Vehicle->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                     <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Vehicle Type</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-truck"></i>
                                </div>
                                <select class="form-control select2" style="width: 100%;" id="vehicle_type" name="vehicle_type">
                                    <option value="">*** Select a Vehicle Type ***</option>
                                    @foreach($Vehicle_types as $Vehicle)
                                        <option value="{{ $Vehicle->id }}">{{ $Vehicle->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                        
                             
				 <div class="form-group">
                        <label for="year" class="col-sm-2 control-label">Year</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                               <input type="text" class="form-control" id="year" name="year" value="{{ old('year') }}" placeholder="Select  Year ...">

                            </div>
                        </div>
                    </div>

                   <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Vehicle Registration</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control"  id="vehicle_registration" name="vehicle_registration" value="" placeholder="Enter vehicle registration" >
                        </div>

                    </div>

                     <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Chassis Number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control"  id="chassis_number" name="chassis_number" value="" placeholder="Enter vehicle chassis number" >
                        </div>

                    </div>

                     <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Engine Number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control"  id="engine_number" name="engine_number" value="" placeholder="Enter vehicle engine number" >
                        </div>

                    </div>


                     <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Vehicle Color</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control"  id="vehicle_color" name="vehicle_color" value="" placeholder="Enter vehicle color " >
                        </div>

                    </div>

                    <div class="form-group{{ $errors->has('metre_reading_type') ? ' has-error' : '' }}">
                                <label for="Leave_type" class="col-sm-2 control-label"> Metre Reading Type </label>
                                <div class="col-sm-9">
                                    <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_package" name="metre_reading_type" value="1" checked> Kilometres   </label>
                                    <label class="radio-inline"><input type="radio" id="rdo_product" name="metre_reading_type" value="2">  Hours  </label>

                                </div>
                    </div> 

                    <div class="form-group odometer-field">
                        <label for="path" class="col-sm-2 control-label">Odometer Reading</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control"  id="odometer_reading" name="odometer_reading" value="" placeholder="Enter Odometer Reading " > Km
                        </div>

                    </div>

                    <div class="form-group hours-field">
                        <label for="path" class="col-sm-2 control-label">Hours Reading</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control"  id="hours_reading" name="hours_reading" value="0" placeholder="Enter vehicle color " >
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="fuel_type" class="col-sm-2 control-label">Fuel Type</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-filter"></i>
                                </div>
                                <select class="form-control select2" style="width: 100%;" id="fuel_type" name="fuel_type">
                                    <option value="">*** Select Fuel Type  ***</option>
                                    @foreach($vehicle as $Vehicle)
                                        <option value="{{ $Vehicle->id }}">{{ $Vehicle->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    

                     <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Size of Fuel Tank</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control"  id="size_of_fuel_tank" name="size_of_fuel_tank" value="" placeholder="Enter Size of Fuel Tank " >
                        </div>

                    </div>

                     <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Fleet Number</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control"  id="fleet_number" name="fleet_number" value="" placeholder="Enter vehicle color " >
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Vehicle Cell Number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="cell_number" name="cell_number" value="{{ old('cell_number') }}" data-inputmask='"mask": "(999) 999-9999"' placeholder="Cell Number" data-mask>
                        </div>

                    </div>

                     <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Tracking Cell Number</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control"  id="tracking_umber" name="tracking_umber" value="" placeholder="Enter Tracking Cell Number" >
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="vehicle_owner" class="col-sm-2 control-label">Vehicle Owner</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user-circle-o"></i>
                                </div>
                                <select class="form-control select2" style="width: 100%;" id="vehicle_owner" name="vehicle_owner">
                                    <option value="">*** Select Vehicle Owner  ***</option>
                                    @foreach($vehicle as $Vehicle)
                                        <option value="{{ $Vehicle->id }}">{{ $Vehicle->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                     <div class="form-group{{ $errors->has('title_type') ? ' has-error' : '' }}">
                                <label for="Leave_type" class="col-sm-2 control-label"> Title Holder </label>
                                <div class="col-sm-9">
                                    <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_fin" name="title_type" value="1" checked> Financial Institution   </label>
                                    <label class="radio-inline"><input type="radio" id="rdo_comp" name="title_type" value="2">  Company  </label>

                                </div>
                    </div>

                    <div class="form-group fin-field">
                        <label for="financial_institution" class="col-sm-2 control-label">Financial Institution</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-university"></i>
                                </div>
                                <select class="form-control select2" style="width: 100%;" id="financial_institution" name="financial_institution">
                                    <option value="">*** Select Financial Institution  ***</option>
                                    @foreach($vehicle as $Vehicle)
                                        <option value="{{ $Vehicle->id }}">{{ $Vehicle->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group comp-field">
                        <label for="company" class="col-sm-2 control-label">Company</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-building"></i>
                                </div>
                                <select class="form-control select2" style="width: 100%;" id="company" name="company">
                                    <option value="">*** Select Company  ***</option>
                                    @foreach($vehicle as $Vehicle)
                                        <option value="{{ $Vehicle->id }}">{{ $Vehicle->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                     <div class="form-group notes-field{{ $errors->has('extras') ? ' has-error' : '' }}">
                        <label for="extras" class="col-sm-2 control-label">Extras</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-sticky-note"></i>
                                </div>
                                <textarea class="form-control" id="extras" name="extras" placeholder="Enter Extras..." rows="3">{{ old('Extras') }}</textarea>
                            </div>
                        </div>
                    </div>


                     <div class="form-group">
                            <label for="image" class="col-sm-2 control-label">Image</label>

                            <div class="col-sm-8">
                                @if(!empty($avatar))
                                    <div style="margin-bottom: 10px;">
                                        <img src="{{ $avatar }}" class="img-responsive img-thumbnail" width="200" height="200">
                                    </div>
                                @endif
                                <input type="file" id="image" name="image" class="file file-loading" data-allowed-file-extensions='["jpg", "jpeg", "png"]' data-show-upload="false">
                            </div>
                        </div>

                    <div class="form-group supDoc-field{{ $errors->has('registration_papers') ? ' has-error' : '' }}">
                        <label for="registration_papers" class="col-sm-2 control-label">Registration Papers</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-upload"></i>
                                </div>
                                <input type="file" id="registration_papers" name="registration_papers" class="file file-loading" data-allowed-file-extensions='["pdf", "docx", "doc"]' data-show-upload="false">
                            </div>
                        </div>
                    </div>


                     <div class="form-group">
                        <label for="property_type" class="col-sm-2 control-label">Property Type</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-building-o"></i>
                                </div>
                                <select class="form-control select2" style="width: 100%;" id="property_type" name="property_type">
                                    <option value="">*** Select Property ***</option>
                                    @foreach($vehicle as $Vehicle)
                                        <option value="{{ $Vehicle->id }}">{{ $Vehicle->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add_maintenance" class="btn btn-warning">Add Vehicle Details</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
  