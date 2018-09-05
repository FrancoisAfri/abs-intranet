<div id="add-vehicledetails-modal" class="modal modal-default fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" name="add-new-vehicledetails-form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Vehicle General Details </h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="box-body" id="vehicle_details">
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
                                            onchange="divDDOnChange(this, null, 'add-vehicledetails-modal')"
                                            style="width: 100%;">
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Person Responsible </label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <select class="form-control select2" style="width: 100%;"
                                        id="responsible_for_maintenance" name="responsible_for_maintenance">
                                    <option value="0">*** Select User ***</option>
                                    @foreach($hrDetails as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->surname }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('vehiclemake_id') ? ' has-error' : '' }}">
						<label for="{{ 'vehiclemake_id' }}" class="col-sm-2 control-label">Vehicle Make </label>

						<div class="col-sm-8">
							<select id="vehiclemake_id" name="vehiclemake_id" class="form-control select2" style="width: 100%;" onchange="vehiclemakeDDOnChange(this)">
								<option value="">*** Please Select a Vehicle Make ***</option>
								<option value="0"></option>
								@foreach($vehiclemake as $make)
									<option value="{{ $make->id }}" >{{ $make->name }}</option>
								@endforeach
							</select>
						</div>
                    </div>
					<div class="form-group{{ $errors->has('vehiclemodel_id') ? ' has-error' : '' }}">
						<label for="{{ 'vehiclemodel_id' }}" class="col-sm-2 control-label">Vehicle Model</label>

						<div class="col-sm-8">
							<select id="vehiclemodel_id" name="vehiclemodel_id" class="form-control select2" style="width: 100%;">
								<option value="">*** Please Select a Vehicle Make First ***</option>
							</select>
						</div>
					</div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Vehicle Type</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-truck"></i>
                                </div>
                                <select class="form-control select2" style="width: 100%;"
                                        id="vehicle_type" name="vehicle_type">
                                    <option value="0">*** Select a Vehicle Type ***</option>
                                    @foreach($Vehicle_types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
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
                                <input type="text" class="form-control" id="year" name="year" value="{{ old('year') }}"
                                       placeholder="Select  Year ...">

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Vehicle Registration</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="vehicle_registration"
                                   name="vehicle_registration" value="" placeholder="Enter vehicle registration">
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Chassis Number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="chassis_number" name="chassis_number" value=""
                                   placeholder="Enter vehicle chassis number">
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Engine Number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="engine_number" name="engine_number" value=""
                                   placeholder="Enter vehicle engine number">
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Vehicle Color</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="vehicle_color" name="vehicle_color" value=""
                                   placeholder="Enter vehicle color ">
                        </div>

                    </div>
                    <div class="form-group{{ $errors->has('metre_reading_type') ? ' has-error' : '' }}">
                        <label for="Leave_type" class="col-sm-2 control-label"> Metre Reading Type </label>
                        <div class="col-sm-9">
                            <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_package"
                                                                                          name="promotion_type"
                                                                                          value="1" checked> Kilometres
                            </label>
                            <label class="radio-inline"><input type="radio" id="rdo_product" name="promotion_type"
                                                               value="2"> Hours </label>

                        </div>
                    </div>
                    <div class="form-group odometer-field">
                        <label for="path" class="col-sm-2 control-label">Odometer Reading</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="odometer_reading" name="odometer_reading"
                                   value="" placeholder="Enter Odometer Reading "> Km
                        </div>

                    </div>
                    <div class="form-group hours-field">
                        <label for="path" class="col-sm-2 control-label">Hours Reading</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="hours_reading" name="hours_reading" value="0"
                                   placeholder="Enter vehicle color ">
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="fuel_type" class="col-sm-2 control-label">Fuel Type</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-filter"></i>
                                </div>

                                <select name="fuel_type" class="form-control">
                                    <option value="0">*** Select Fuel Type ***</option>
                                    <option value="1"> Unleaded</option>
                                    <option value="2"> Lead replacement</option>
                                    <option value="3"> Diesel</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Size of Fuel Tank</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="size_of_fuel_tank" name="size_of_fuel_tank"
                                   value="" placeholder="Enter Size of Fuel Tank ">
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Fleet Number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="fleet_number" name="fleet_number" value=""
                                   placeholder="Enter fleet number ">
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Vehicle Cell Number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="cell_number" name="cell_number"
                                   value="{{ old('cell_number') }}" data-inputmask='"mask": "(999) 999-9999"'
                                   placeholder="Cell Number" data-mask>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Tracking Cell Number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="tracking_umber" name="tracking_umber" 
                                value="" data-inputmask='"mask": "(999) 999-9999999999"'  placeholder="Enter Tracking Cell Number" data-mask>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="vehicle_owner" class="col-sm-2 control-label">Vehicle Owner</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user-circle-o"></i>
                                </div>
                                <select class="form-control select2" style="width: 100%;" id="vehicle_owner"
                                        name="vehicle_owner">
                                    <option value="0">*** Select Vehicle Owner ***</option>
									@foreach($DivisionLevelFive as $owner)
                                        <option value="{{ $owner->id }}">
                                            {{ (!empty( $owner->name)) ?  $owner->name : ''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('title_type') ? ' has-error' : '' }}">
                        <label for="title_type" class="col-sm-2 control-label"> Title Holder </label>
                        <div class="col-sm-9">
                            <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_fin"
                                name="title_type" value="1" checked> Financial Institution                                                         
                            </label>
                            <label class="radio-inline"><input type="radio" id="rdo_comp" name="title_type" value="2">
                                Company </label>
                        </div>
                    </div>
                    <div class="form-group fin-field">
						<label for="financial_institution" class="col-sm-2 control-label">Financial
						Institution</label>
						<div class="col-sm-8">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-university"></i>
								</div>
								<select class="form-control select2" style="width: 100%;" id="financial_institution"
									name="financial_institution">
									<option value="0">*** Select Financial Institution ***</option>
									@foreach($ContactCompany as $ContactCompan)
									<option value="{{ $ContactCompan->id }}">{{ (!empty( $ContactCompan->name)) ?  $ContactCompan->name : ''}}</option>

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
                                    <option value="0">*** Select Company ***</option>
                                    @foreach($DivisionLevelFive as $owner)
                                        <option value="{{ $owner->id }}">
                                            {{ (!empty( $owner->name)) ?  $owner->name : ''}}</option>
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
                                <textarea class="form-control" id="extras" name="extras" placeholder="Enter Extras..."
                                          rows="3">{{ old('Extras') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="image" class="col-sm-2 control-label">Image</label>

                        <div class="col-sm-8">
                            @if(!empty($avatar))
                                <div style="margin-bottom: 10px;">
                                    <img src="{{ $avatar }}" class="img-responsive img-thumbnail" width="200"
                                         height="200">
                                </div>
                            @endif
                            <input type="file" id="image" name="image" class="file file-loading"
                                   data-allowed-file-extensions='["jpg", "jpeg", "png"]' data-show-upload="false">
                        </div>
                    </div>
                    <div class="form-group supDoc-field{{ $errors->has('registration_papers') ? ' has-error' : '' }}">
                        <label for="registration_papers" class="col-sm-2 control-label">Registration Papers</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-file-pdf-o"></i>
                                </div>
                                <input type="file" id="registration_papers" name="registration_papers"
                                       class="file file-loading" data-allowed-file-extensions='["pdf", "docx", "doc"]'
                                       data-show-upload="false">
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
                                <select name="property_type" class="form-control">
                                    <option value="0">*** Select Property ***</option>
                                    <option value="1"> Internal</option>
                                    <option value="2"> External</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add_vehicledetails" class="btn btn-warning"><i
                                class="fa fa-cloud-upload"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>