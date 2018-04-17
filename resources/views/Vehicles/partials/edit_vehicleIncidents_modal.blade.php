<div id="edit-incidents-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-incidents-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Vehicle Incident Log</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                           <div class="form-group ">
                        <label for="date" class="col-sm-2 control-label">Date of Incident</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="dateofincident" name="date_of_incident"
                                   value="{{ old('date') }}" placeholder="Select  date of incident   ...">
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="Status" class="col-sm-2 control-label">Incident Type  </label>
                        <div class="col-sm-8">
                            <select id="incident_type" name="incident_type" class="form-control">
                                <option value="0">*** Select Type ***</option>
                                <option value="1"> Accident</option>
                                <option value="2"> Mechanical Fault </option>
                                <option value="3"> Electronic Fault</option>
                                <option value="4"> Damaged </option>
                                <option value="5"> Attempted Hi-jacking</option>
                                <option value="6">  Hi-jacking</option>
                                <option value="7"> Other</option>
                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="Status" class="col-sm-2 control-label">Severity  </label>
                        <div class="col-sm-8">
                            <select id="severity" name="severity" class="form-control">
                                <option value="0">*** Select a Severity ***</option>
                                <option value="1"> Minor</option>
                                <option value="2"> Major </option>
                                <option value="3"> Critical </option>
                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Reported by </label>
                        <div class="col-sm-8">
                            <select class="form-control select2" style="width: 100%;"
                                    id="reported_by" name="reported_by">
                                <option value="0">*** Select User  ***</option>
                                @foreach($employees as $user)
                                    <option value="{{ $user->id }}">{{ $user->first_name . ' ' . $user->surname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @if (isset($maintenance) && $maintenance->maintenance === 1)
                     <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Odometer Reading</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="odometer_reading" name="odometer_reading" value=""
                                   placeholder="Enter Odometer Reading" required>
                        </div>
                    </div>
                     @else
                     <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Hours Reading</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="hours_reading" name="hours_reading" value=""
                                   placeholder="Enter Odometer Reading" required>
                        </div>
                    </div>
                     @endif

                     <div class="form-group">
                        <label for="Status" class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-8">
                            <select id="status" name="status" class="form-control">
                                <option value="0">*** Select  Status ***</option>
                                <option value="1"> Reported</option>
                                <option value="2"> Scheduled for Repair </option>
                                <option value="3"> Resolved </option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="description" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-8">

                            <textarea class="form-control" id="description" name="description"
                                      placeholder="Enter description..." rows="3">{{ old('description') }}</textarea>
                        </div>
                    </div>

                      <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Claim Number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="claim_number" name="claim_number" value=""
                                   placeholder="Enter Name" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Cost</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="cost" name="Cost" value="0"
                                   placeholder="Enter Cost" required>
                        </div>
                    </div>
                 
                    <div class="form-group supDoc-field{{ $errors->has('documents') ? ' has-error' : '' }}">
                        <label for="documents" class="col-sm-2 control-label">Attachment </label>
                        <div class="col-sm-8">

                            <input type="file" id="documents" name="documents"
                                   class="file file-loading" data-allowed-file-extensions='["pdf", "docx", "doc"]'
                                   data-show-upload="false">
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="name" name="name" value=""
                                   placeholder="Enter Name" required>
                        </div>
                    </div>
                    <input type="hidden" id="valueID" name="valueID"
                           value="{{ !empty($maintenance->id) ? $maintenance->id : ''}}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="edit_vehicleincidents" class="btn btn-primary"><i class="fa fa-floppy-o"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
        
           