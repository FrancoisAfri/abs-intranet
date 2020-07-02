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

                    <div class="form-group{{ $errors->has('transaction') ? ' has-error' : '' }}">
                        <label for="transaction" class="col-sm-2 control-label"> Tanks and Other </label>
                        <div class="col-sm-8">
                            <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_transaction"
                                                                                          name="transaction" value="1" checked>Tank
                            </label>
                            <label class="radio-inline"><input type="radio" id="rdo_Other" name="transaction" value="2">
                                Other
                            </label>
                        </div>
                    </div>
                    <div class="form-group transaction-field{{ $errors->has('transaction_type') ? ' has-error' : '' }}">
                        <label for="transaction_type" class="col-sm-2 control-label"> Transaction Type </label>
                        <div class="col-sm-8">
                            <label class="radio-inline" ><input type="radio" id="rdo_fulltank"  name="transaction_type" value="1">Full Tank </label>
                            <label class="radio-inline"><input type="radio" id="rdo_topup" name="transaction_type" value="2"> Top Up </label>
                        </div>
                    </div>
                    <div class="form-group Tanks-field">
                        <label for="path" class="col-sm-2 control-label">Tanks </label>
                        <div class="col-sm-8">
                            <select class="form-control select2" style="width: 100%;"
                                    id="tank_name" name="tank_name">
                                <option value="0">*** Select tank  ***</option>
                                @foreach($fueltank as $tank)
                                <option value="{{ $tank->id }}">{{ $tank->tank_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group  transaction-field">
                        <label for="path" class="col-sm-2 control-label">Service Station </label>
                        <div class="col-sm-8">
                            <select class="form-control select2" style="width: 100%;"
                                    id="service_station" name="service_station">
                                <option value="0">*** Select tank  ***</option>
                                @foreach($servicestation as $station)
                                <option value="{{ $station->id }}">{{ $station->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Litres</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="litres_new" name="litres_new" value=""
                                   min="0" step="0.001"
                                   placeholder="Enter Litres" required>
                        </div>
                    </div>
                    <div class="form-group  transaction-field">
                        <label for="path" class="col-sm-2 control-label">Cost per Litre </label>
                        <div class="col-sm-8">
                            <input type="teXt" class="form-control" id="cost_per_litre" name="cost_per_litre" value=""  min="0" step="0.01"
                                   placeholder="Enter Litres" required>
                        </div>
                    </div>
                    <div class="form-group  transaction-field">
                        <label for="path" class="col-sm-2 control-label">Total Cost</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="total_cost" name="total_cost" value="0"
                                   placeholder="Enter Litres" required>
                        </div>
                    </div>
                    @if (isset($MetreType) && $MetreType === 1)
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Odometer Reading </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="Odometer_reading" name="Odometer_reading" value=""
                                   placeholder="Enter Odometer reading Reading" required>
                        </div>
                    </div>
                    @else
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Hours Reading</label>
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
                                      placeholder="Enter description..." rows="3">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Captured By</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="captured_by" name="captured_by" value="{{ $name }}"
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
                value="{{ !empty($vehiclebookings->vehicle_id) ? $vehiclebookings->vehicle_id : ''}}">
                <input type="hidden" id="vehiclebookingID" name="vehiclebookingID"
                value="{{ !empty($returnVeh->id) ? $returnVeh->id : ''}}">
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

