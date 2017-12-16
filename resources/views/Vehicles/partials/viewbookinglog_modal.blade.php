<div id="add-vehiclebookinglog-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-vehiclebookinglog-form">
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
                                    value="{{  $vehiclebookinglog->capturer_id }}" readonly>
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
                                    value="{{  $vehiclebookinglog->firstname . ' ' . $vehiclebookinglog->surname }}"
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
                                    value="{{ date("F j, Y, g:i a", $vehiclebookinglog->require_datetime)  }}"
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
                                    value="{{ date("F j, Y, g:i a", $vehiclebookinglog->return_datetime)  }}"
                                    readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="path" class="col-sm-2 control-label">vehiclebookinglog Type </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" id="collect_timestamp"
                                    class="form-control pull-left" name="collect_timestamp"
                                    value="{{ $usageType[$vehiclebookinglog->usage_type] }}"
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
                                    value="{{ $vehiclebookinglog->purpose }}"
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
                                    value="{{ $vehiclebookinglog->destination }}"
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
                                    value="{{ $vehiclebookinglogStatus[$vehiclebookinglog->status] }}"
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
                                    value="{{ !empty($vehiclebookinglog->collect_timestamp ) ?  date("F j, Y, g:i a", $vehiclebookinglog->collect_timestamp)  : ''}}"
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
                                    value="{{  $vehiclebookinglog->start_mileage_id }}" readonly>
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




                        <div class="form-group{{ $errors->has('document') ? ' has-error' : '' }}">
                           <label for="end_mileage_id" class="col-sm-2 control-label">Inpection Documents Collect </label>
                           <div class="col-sm-8">
                            @if(!empty($vehiclebookinglog->collectDoc))
                            <a class="btn btn-default btn-flat btn-block pull-right "
                            href="{{  (!empty($vehiclebookinglog->collectDoc)) ? Storage::disk('local')->url("projects/collectiondocuments/$vehiclebookinglog->collectDoc") : '' }}" target="_blank"><i
                            class="fa fa-file-pdf-o"></i> View Document</a>
                            @else
                            <a class="btn btn-default pull-centre "><i
                                class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('document') ? ' has-error' : '' }}">
                           <label for="end_mileage_id" class="col-sm-2 control-label">Inpection Documents Return  </label>
                           <div class="col-sm-8">
                            @if(!empty($vehiclebookinglog->returnDoc))
                            <a class="btn btn-default btn-flat btn-block pull-right "
                            href="{{ $vehiclebookinglog->returnDoc}}" target="_blank"><i
                            class="fa fa-file-pdf-o"></i> View Document</a>
                            @else
                            <a class="btn btn-default pull-centre "><i
                                class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
                                @endif
                            </div>
                        </div>

                        

                        <input type="hidden" id="valueID" name="valueID"
                        value="{{ !empty($maintenance->id) ? $maintenance->id : ''}}">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
               <!--  <button type="button" id="add_vehiclefuellog" class="btn btn-warning"><i class="fa fa-cloud-upload"></i>
                Save -->
            </button>
        </div>
    </form>
</div>
</div>
</div>
</div>

