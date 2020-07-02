<div id="add-booking-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-booking-form">
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
                            <label for="path" class="col-sm-2 control-label">Vehicle Make</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-bullseye"></i>
                                    </div>
                                    <input type="text" id="vehiclemodel" class="form-control pull-left"
                                    name="vehiclemodel" value="{{ $vehiclemaker->name }} "
                                    readonly>
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
                                    <input type="text" id="vehiclemodel" class="form-control pull-left"
                                    name="vehiclemodel" value="{{ $vehiclemodeler->name }} "
                                    readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="path" class="col-sm-2 control-label">Vehicle Reg. No</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-id-card-o"></i>
                                    </div>
                                    <input type="text" id="vehicle_reg" class="form-control pull-left"
                                    name="vehicle_reg"
                                    value="" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="path" class="col-sm-2 control-label">Capturer </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user-o"></i>
                                    </div>
                                    <input type="text" id="capturer_id" class="form-control pull-left"
                                    name="capturer_id"
                                    value="{{  !empty($booking->capturer_id) ? $booking->capturer_id : ''
                                    }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="path" class="col-sm-2 control-label"> VehicleDriver </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user-o"></i>
                                    </div>
                                    <input type="text" id="driver_id" class="form-control pull-left"
                                    name="driver_id"
                                    value="{{ !empty($booking->capturer_id) ? $booking->capturer_id : ''}}"
                                    readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="path" class="col-sm-2 control-label">Required From </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" id="require_datetime"
                                    class="form-control pull-left" name="require_datetime"
                                    value="{{ !empty($booking->require_datetime ) ?  date("F j, Y, g:i a", $booking->require_datetime)  : ''}}"
                                    readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="path" class="col-sm-2 control-label">Required By </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" id="require_datetime"
                                    class="form-control pull-left" name="require_datetime"
                                    value="{{ !empty($booking->return_datetime ) ?  date("F j, Y, g:i a", $booking->return_datetime)  : ''}}"
                                    readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="path" class="col-sm-2 control-label">booking Type </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" id="collect_timestamp"
                                    class="form-control pull-left" name="collect_timestamp"
                                    value="{{  !empty($booking->usage_type) ? $usageType[$booking->usage_type] : ''  }}"
                                    readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="path" class="col-sm-2 control-label">Purpose </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" id="collect_timestamp"
                                    class="form-control pull-left" name="collect_timestamp"
                                    value="{{ !empty($booking->purpose) ? $booking->purpose : ''  }}"
                                    readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="path" class="col-sm-2 control-label">Destination </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" id="collect_timestamp"
                                    class="form-control pull-left" name="collect_timestamp"
                                    value="{{ !empty($booking->destination) ? $booking->destination : '' }}"
                                    readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="path" class="col-sm-2 control-label">Status </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" id="collect_timestamp"
                                    class="form-control pull-left" name="collect_timestamp"
                                    value="{{ !empty($booking->status) ? $bookingStatus[$booking->status]  : '' }}"
                                    readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="path" class="col-sm-2 control-label">Collected </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" id="collect_timestamp"
                                    class="form-control pull-left" name="collect_timestamp"
                                    value="{{ !empty($booking->collect_timestamp ) ?  date("F j, Y, g:i a", $booking->collect_timestamp)  : ''}}"
                                    readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="path" class="col-sm-2 control-label"> Start Odometer
                            Reading </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-tachometer"></i>
                                    </div>
                                    <input type="text" id="start_mileage_id" class="form-control pull-left"
                                    name="start_mileage_id"
                                    value="{{  !empty($booking->start_mileage_id) ? $booking->start_mileage_id : ''   }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="end_mileage_id" class="col-sm-2 control-label"> Collection Processed By</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-tachometer"></i>
                                    </div>
                                    <input type="text" id="end_mileage_id" class="form-control pull-left"
                                    name="end_mileage_id" value="0">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="end_mileage_id" class="col-sm-2 control-label">Approvals </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-tachometer"></i>
                                    </div>
                                    <input type="text" id="end_mileage_id" class="form-control pull-left"
                                    name="end_mileage_id" value="0">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="valueID" name="valueID"
                        value="{{ !empty($maintenance->id) ? $maintenance->id : ''}}">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            </button>
        </div>
    </form>
</div>
</div>
</div>
</div>