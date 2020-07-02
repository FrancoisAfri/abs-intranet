<div id="add-maintenance-modal" class="modal modal-default fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add_new_site-form" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <i class="fa fa-truck pull-left"></i>
                     <h4 class="modal-title">Add New Maintenance.. </h4>
                </div>
                <div class="modal-body" style="max-height: 330px; overflow-y: scroll;">
                    <div id="leave-invalid-input-alert"></div>
                    <div id="success-alert"></div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Vehicle</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-truck"></i>
                                </div>
                                <select class="form-control select2" style="width: 100%;" id="vehicle" name="vehicle">
                                    <option value="">*** Select a Vehicle  ***</option>
                                    @foreach($Vehicle_managemnt as $Vehicle)
                                        <option value="{{ $Vehicle->id }}">{{ $Vehicle->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

				 <div class="form-group">
                        <label for="start_date" class="col-sm-2 control-label">Job Card Date</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control datepicker" id="job_card_date" name="job_card_date" value="{{ old('job_card_date') }}" placeholder="Click to Select a Date...">
                            </div>
                        </div>
                    </div>

                     <div class="form-group">
                        <label for="schedule_date" class="col-sm-2 control-label">Schedule Date</label>

                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control datepicker" id="schedule_date" name="schedule_date" value="{{ old('schedule_date') }}" placeholder="Click to Select a Date...">
                            </div>
                        </div>
                      </div>

					  <div class="form-group">
                        <label for="completion_date" class="col-sm-2 control-label">Completion Date</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control datepicker" id="completion_date" name="completion_date" value="{{ old('completion_date') }}" placeholder="Click to Select a Date...">
                            </div>
                        </div>
                      </div>
					  
                    <div class="form-group">
                        <label for="end_date" class="col-sm-2 control-label"> Service by Agent</label>

                        <div class="col-sm-8">

                         <input type="checkbox" id="external_service" value="1" name="external_service" onclick="showHide();" >  Send Email
                            </div>
                    </div>

                   

                    <div class="form-group">
                        <label for="booking_date" class="col-sm-2 control-label">Booking Date</label>

                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control datepicker" id="booking_date" name="booking_date" value="{{ old('booking_date') }}" placeholder="Click to Select a Date...">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="supplier" class="col-sm-2 control-label">Supplier</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-truck"></i>
                                </div>
                                <select class="form-control select2" style="width: 100%;" id="supplier" name="supplier">
                                    <option value="">*** Select a Supplier  ***</option>
                                    @foreach($Vehicle_managemnt as $Vehicle)
                                        <option value="{{ $Vehicle->id }}">{{ $Vehicle->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="service_type" class="col-sm-2 control-label">Service Type</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-truck"></i>
                                </div>
                                <select class="form-control select2" style="width: 100%;" id="service_type" name="service_type">
                                    <option value="">*** Select Service Type  ***</option>
                                    @foreach($Vehicle_managemnt as $Vehicle)
                                        <option value="{{ $Vehicle->id }}">{{ $Vehicle->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Estimated Hours</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control"  id="estimated_hours" name="estimated_hours" value="" placeholder="Enter hours" >
                        </div>

                    </div>

                       <div class="form-group supDoc-field{{ $errors->has('service_docs') ? ' has-error' : '' }}">
                        <label for="days" class="col-sm-2 control-label">Service File Upload</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-upload"></i>
                                </div>
                                <input type="file" id="service_docs" name="service_docs" class="file file-loading" data-allowed-file-extensions='["pdf", "docx", "doc"]' data-show-upload="false">
                            </div>
                        </div>
                    </div>



                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Service Time</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control"  id="service_time" name="service_time" value="" placeholder="Enter service time" >
                        </div>
                    </div>

                     <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Machine Hour Metre </label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control"  id="machine_hour_metre" name="machine_hour_metre" value="" placeholder="Enter service time" >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Machine Odometer</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control"  id="machine_odometer" name="machine_odometer" value="" placeholder="Enter service time" >
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Last Driver</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-truck"></i>
                                </div>
                                <select class="form-control select2" style="width: 100%;" id="last_driver" name="last_driver">
                                    <option value="">*** Select Driver ***</option>
                                    @foreach($Vehicle_managemnt as $Vehicle)
                                        <option value="{{ $Vehicle->id }}">{{ $Vehicle->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group notes-field{{ $errors->has('description') ? ' has-error' : '' }}">
                        <label for="days" class="col-sm-2 control-label">Inspection Info</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-sticky-note"></i>
                                </div>
                                <textarea class="form-control" id="inspection_info" name="inspection_info" placeholder="Enter a inspection_info..." rows="2">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group supDoc-field{{ $errors->has('inspection_docs') ? ' has-error' : '' }}">
                        <label for="inspection_docs" class="col-sm-2 control-label">Inspection Document</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-upload"></i>
                                </div>
                                <input type="file" id="inspection_docs" name="inspection_docs" class="file file-loading" data-allowed-file-extensions='["pdf", "docx", "doc"]' data-show-upload="false">
                            </div>
                        </div>
                    </div>

                     <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Mechanic </label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-truck"></i>
                                </div>
                                <select class="form-control select2" style="width: 100%;" id="mechanic" name="mechanic">
                                    <option value="">*** Select Mechanic ***</option>
                                    @foreach($Vehicle_managemnt as $Vehicle)
                                        <option value="{{ $Vehicle->id }}">{{ $Vehicle->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="end_date" class="col-sm-2 control-label"> Action</label>

                        <div class="col-sm-8">

                           <!--  <input type="checkbox" id="external_service" value="1" name="external_service" onclick="myFunction();" >  Send Email -->
                           <input type="checkbox" id="external_service" name="external_service" title="Select All" /> 
                           <label for="check_all_1">Email</label>
                            </div>
                        </div>

                     <div class="form-group">
                        <label for="path" class="col-sm-2 control-label"> </label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-envelope"></i>
                                </div>
                                <select class="form-control select2" style="width: 100%;" id="emails" name="emails">
                                    <option value="">*** Select a Vehicle  ***</option>
                                    @foreach($Vehicle_managemnt as $Vehicle)
                                        <option value="{{ $Vehicle->id }}">{{ $Vehicle->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>



                    <div class="form-group notes-field{{ $errors->has('instruction') ? ' has-error' : '' }}">
                        <label for="instruction" class="col-sm-2 control-label">Instruction</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-sticky-note"></i>
                                </div>
                                <textarea class="form-control" id="instruction" name="instruction" placeholder="Enter Instruction..." rows="2">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add_maintenance" class="btn btn-warning">Add Maintenance</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>