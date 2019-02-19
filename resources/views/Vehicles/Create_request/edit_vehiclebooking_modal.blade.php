<div id="edit-booking-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-booking-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Vehicle Booking Information</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Vehicle Model</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-bullseye"></i>
                                </div>
                                <input type="text" id ="vehiclemodel" class="form-control pull-left" name="vehiclemodel" value="" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Vehicle Reg. No</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-id-card-o"></i>
                                </div>
                                <input type="text" id ="vehicle_reg" class="form-control pull-left" name="vehicle_reg" value=" " readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Required From </label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control" id="required_from" name="required_from" value=""/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Required To </label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control" id="required_to" name="required_to" value=""/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="Status" class="col-sm-2 control-label">Usage Type </label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-ravelry"></i>
                                </div>
                                <select id="usage_type" name="usage_type" class="form-control">
                                    <option value="0">*** Select a Booking Type  ***</option>
                                    <option value="1"> Usage</option>
                                    <option value="2"> Service</option>
                                    <option value="2"> Maintenance</option>
                                    <option value="2"> Repair</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Vehicle Driver</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user-o"></i>
                                </div>
                                <select class="form-control " style="width: 100%;"
                                        id="driver" name="driver">
                                    <option value="">*** Select Driver ***</option>
                                    @foreach($employees as $user)
                                        <option value="{{ $user->id }}">{{ $user->first_name . ' ' . $user->surname  }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group notes-field{{ $errors->has('purpose') ? ' has-error' : '' }}">
                        <label for="purpose" class="col-sm-2 control-label">Purpose for Request</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-sticky-note"></i>
                                </div>
                                <textarea class="form-control" id="purpose" name="purpose"
                                          placeholder="Enter Extras..."
                                          rows="3">{{ old('Extras') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Destination </label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-anchor"></i>
                                </div>
                                <input type="text" id ="destination" class="form-control form-control-sm pull-left" name="destination" value=" " >
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="vehicle_id" name="vehicle_id"
                           value="">



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="edit_booking" class="btn btn-primary"><i class="fa fa-floppy-o"></i>
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
        
           