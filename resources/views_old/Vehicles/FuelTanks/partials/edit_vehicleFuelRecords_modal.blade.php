<div id="edit-fuel-records-modal" class="modal modal-default fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" name="edit-fuel-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Edit Vehicle Details</h4>
                </div>
				<div class="modal-body">
					<div id="invalid-input-alert"></div>
					<div id="success-alert"></div>
					<div class="box-body">
						<div class="form-group">
                        <label for="driver" class="col-sm-2 control-label">Driver </label>
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
							<label for="document_number" class="col-sm-2 control-label">Document Number</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="document_number" name="document_number" 
								value="{{$details->document_number }}" placeholder="Enter Document Number" required>
							</div>
						</div>
						<div class="form-group ">
							<label for="date_captured" class="col-sm-2 control-label">Date </label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="date_captured" name="date_captured"
									   value="{{ date(' d M Y', $details->date) }}" placeholder="Select date ...">
							</div>
						</div>
						<div class="form-group{{ $errors->has('tank_and_other') ? ' has-error' : '' }}">
							<label for="tank_and_other" class="col-sm-2 control-label"> Tanks and Other </label>
								<div class="col-sm-8">
									<label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_transaction"
											name="tank_and_other" value="1">Tank
								 </label>
									 <label class="radio-inline"><input type="radio" id="rdo_Other" name="tank_and_other" value="2">
											Other
									</label>
								</div>
						</div>
						<div class="form-group transaction-field{{ $errors->has('transaction_type') ? ' has-error' : '' }}">
							<label for="transaction_type" class="col-sm-2 control-label"> Transaction Type </label>
							<div class="col-sm-8">
								<label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_fulltank"
																							  name="transaction_type" value="1">Full Tank
								</label>
								<label class="radio-inline"><input type="radio" id="rdo_topup" name="transaction_type" value="2">
									Top Up
								</label>
							</div>
						</div>
						<div class="form-group Tanks-field">
							<label for="tank_name" class="col-sm-2 control-label">Tanks </label>
							<div class="col-sm-8">
								<select class="form-control select2" style="width: 100%;"
										id="tank_name" name="tank_name">
									<option value="0">*** Select tank  ***</option>
									@foreach($fueltank as $tank)
									<option value="{{ $tank->id }}">{{ $tank->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group  transaction-field">
							<label for="service_station" class="col-sm-2 control-label">Service Station </label>
							<div class="col-sm-8">
								<select class="form-control select2" style="width: 100%;"
										id="service_station" name="service_station">
									<option value="0">*** Service Station  ***</option>
									@foreach($servicestation as $station)
										<option value="{{ $station->id }}">{{ $station->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="litres_new" class="col-sm-2 control-label">Litres </label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="litres_new_one" name="litres_new" value="0"
									   placeholder="Enter Litres" required>
							</div>
						</div>
						<div class="form-group  transaction-field">
							<label for="cost_per_litre" class="col-sm-2 control-label">Cost per Litre </label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="cost_per_litre_one" name="cost_per_litre" value="0"
									   placeholder="Enter Litres" required>
							</div>
						</div>
						<div class="form-group  transaction-field">
							<label for="total_cost" class="col-sm-2 control-label">Total Cost</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="total_cost_one" name="total_cost" value="0"
									   placeholder="Enter Litres" required>
							</div>
						</div>
						@if (isset($metreType) && $metreType== 1)
							<div class="form-group">
								<label for="Odometer_reading" class="col-sm-2 control-label">Km Reading </label>
								<div class="col-sm-8">
									<input type="text" class="form-control" id="Odometer_reading" name="Odometer_reading" value=""
										   placeholder="Enter Odometer reading Reading" required>
								</div>
							</div>
						@else
							<div class="form-group">
								<label for="hours_reading" class="col-sm-2 control-label">Hour Reading</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" id="hours_reading" name="hours_reading" value=""
										   placeholder="Enter Hours Reading" required>
								</div>
							</div>
						@endif
						<div class="form-group ">
							<label for="description" class="col-sm-2 control-label">Description</label>
							<div class="col-sm-8">

								<textarea class="form-control" id="description" name="description"
										  placeholder="Enter Description..." rows="3">{{ old('description') }}</textarea>
							</div>
						</div>							
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
						<button type="button" id="update_fuel" class="btn btn-primary"><i class="fa fa-floppy-o"></i>
							Save
						</button>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>