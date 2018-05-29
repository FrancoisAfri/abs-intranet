<div id="add-jobcard-modal" class="modal modal-default fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-jobcard-form">
            {{ csrf_field() }}
            <!--                {{ method_field('PATCH') }}-->

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add jobcard</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                    <div class="form-group">
                        <label for="leave_type" class="col-sm-2 control-label">Vehicle</label>
                        <div class="col-sm-8">
                            <select id="vehicle_id" name="vehicle_id" class="form-control">
                                <option value=" ">*** Select a Vehicle ***</option>
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
                    <div class="form-group">
                        <label for="completion_date" class="col-sm-2 control-label">Completion Date</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control datepicker" id="completion_date"
                                   name="completion_date" value="{{ old('completion_date') }}"
                                   placeholder="Click to Select a Date...">
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="date" class="col-sm-2 control-label"> Service by Agent</label>
                        <div class="col-sm-8">
                            <input type="checkbox" id="external_service" value="1" name="external_service"
                                   onclick="showHide();">
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
                                <option value=" ">*** Select a Service type ***</option>
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
                    <div class="form-group supDoc-field{{ $errors->has('documents') ? ' has-error' : '' }}">
                        <label for="documents" class="col-sm-2 control-label">Service File Upload </label>
                        <div class="col-sm-8">

                            <input type="file" id="service_file_upload" name="service_file_upload"
                                   class="file file-loading" data-allowed-file-extensions='["pdf", "docx", "doc"]'
                                   data-show-upload="false">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Service Time</label>
                        <div class="col-sm-8">

                            <input type="number" class="form-control" id="service_time" name="service_time" value=""
                                   placeholder="Enter service time" required>
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
                    <div class="form-group notes-field{{ $errors->has('description') ? ' has-error' : '' }}">
                        <label for="days" class="col-sm-2 control-label">Inspection Info</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-sticky-note"></i>
                                </div>
                                <textarea class="form-control" id="inspection_info" name="inspection_info"
                                          placeholder="Enter a inspection Info"
                                          rows="2">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group supDoc-field{{ $errors->has('documents') ? ' has-error' : '' }}">
                        <label for="documents" class="col-sm-2 control-label">Inspection Document</label>
                        <div class="col-sm-8">

                            <input type="file" id="inspection_file_upload" name="inspection_file_upload"
                                   class="file file-loading" data-allowed-file-extensions='["pdf", "docx", "doc"]'
                                   data-show-upload="false">
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
                    <div class="form-group notes-field{{ $errors->has('description') ? ' has-error' : '' }}">
                        <label for="days" class="col-sm-2 control-label">instruction Info</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-sticky-note"></i>
                                </div>
                                <textarea class="form-control" id="instruction" name="instruction"
                                          placeholder="Enter a inspection Info"
                                          rows="4">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add_jobcardtypes" class="btn btn-warning"><i
                                class="fa fa-cloud-upload"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
           