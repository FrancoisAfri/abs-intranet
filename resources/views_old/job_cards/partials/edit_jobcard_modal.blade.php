<div id="edit-jobcard-modal" class="modal modal-default fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-jobcard-form">
				<input type="hidden" name="file_index" id="file_index" value="1"/>
				<input type="hidden" name="total_files" id="total_files" value="1"/>
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit jobcard</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                    <div class="form-group">
                        <label for="leave_type" class="col-sm-2 control-label">Vehicle</label>
                        <div class="col-sm-8">
                            <select id="vehicle_id" name="vehicle_id" class="form-control">
                                <option value="0">*** Select a Vehicle ***</option>
                                @foreach($vehicledetails as $details)
                                    <option value="{{ $details->id }}">{{ $details->fleet_number . ' ' .  $details->vehicle_registration . ' ' . $details->vehicle_make . ' ' . $details->vehicle_model }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="date" class="col-sm-2 control-label"> Job card Date </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control datepicker" id="card_date" name="card_date"
                                   value="{{ old('date') }}" placeholder="Select start date  ...">
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="date" class="col-sm-2 control-label"> Schedule Date </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control datepicker" id="schedule_date" name="schedule_date"
                                   value="{{ old('date') }}" placeholder="Select start date  ...">
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="date" class="col-sm-2 control-label"> Service by Agent</label>
                        <div class="col-sm-8">
                            <input type="checkbox" id="external_service" value="1" $sServiceAgent
                                   name="external_service" onclick="showHide();">
                        </div>
                    </div>
                    <div class="form-group agent_field">
                        <label for="date" class="col-sm-2 control-label"> Booking Date </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="booking_date" name="booking_date"
                                   value="{{ old('date') }}" placeholder="Select date  ...">
                        </div>
                    </div>
                    <div class="form-group agent_field">
                        <label for="leave_type" class="col-sm-2 control-label">Supplier</label>
                        <div class="col-sm-8">
                            <select id="supplier_id" name="supplier_id" class="form-control">
                                <option value="0">*** Select a Supplier ***</option>
                                @foreach($ContactCompany as $details)
                                    <option value="{{ $details->id }}">{{ $details->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="leave_type" class="col-sm-2 control-label">Service Type</label>
                        <div class="col-sm-8">
                            <select id="service_type" name="service_type" class="form-control">
                                <option value="0">*** Select a Service type ***</option>
                                @foreach($servicetype as $details)
                                    <option value="{{ $details->id }}">{{ $details->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Estimated Hours</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="estimated_hours" name="estimated_hours"
                                   value=""
                                   placeholder="Enter Estimated Hours" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Machine Hour Metre</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="machine_hour_metre" name="machine_hour_metre"
                                   value=""
                                   placeholder="Enter metres" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Machine Odometer</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="machine_odometer" name="machine_odometer"
                                   value=""
                                   placeholder="" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="leave_type" class="col-sm-2 control-label">Driver</label>
                        <div class="col-sm-8">
                            <select id="last_driver_id" name="last_driver_id" class="form-control">
                                <option value="0">*** Select a driver ***</option>
                                @foreach($users as $details)
                                    <option value="{{ $details->id }}">{{$details->first_name . ' ' .  $details->surname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group mechanic_row">
                        <label for="leave_type" class="col-sm-2 control-label">Mechanic</label>
                        <div class="col-sm-8">
                            <select id="mechanic_id" name="mechanic_id" class="form-control">
                                <option value="0">*** Select a mechanic ***</option>
                                @foreach($users as $details)
                                    <option value="{{ $details->id }}">{{ $details->first_name . ' ' .  $details->surname}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
					<div id="tab_10">
						<hr class="hr-text" data-content="Instructions">
						<div class="row" id="tab_tab">							
							<div class="col-sm-12" style="display:none;" id="instructions_row">
								<textarea class="form-control" id="instruction" name="instruction"
									placeholder="Enter a inspection Info" rows="3" disabled="disabled"></textarea>
							</div>
							<div class="col-sm-12" id="1" name="1" style="margin-bottom: 15px;">
								<textarea class="form-control" id="instruction[1]" name="instruction[1]"
									placeholder="Enter a inspection Info" rows="3"></textarea>
							</div>
						</div>
						<div class="row" id="final_row">
							<div class="col-sm-12">
								<button type="button" class="btn btn-default btn-block btn-flat add_more" onclick="addFile()">
									<i class="fa fa-clone"></i> Add More
								</button>
							</div>
						</div>
					</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="update_jobcard" class="btn btn-warning"><i class="fa fa-cloud-upload"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
           