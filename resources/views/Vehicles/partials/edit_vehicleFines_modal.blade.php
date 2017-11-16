<div id="edit-fines-modal" class="modal modal-default fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-fines-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit License Type/Permit</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                  <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Capture Date</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="date_captured" name="date_captured" value=""
                                   placeholder="Enter Capture Date" required readonly="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Registration</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="contact_number" name="contact_number" value=""
                                   placeholder="Enter Registration " required readonly="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="Status" class="col-sm-2 control-label">Type of Fine </label>
                        <div class="col-sm-8">

                            <select id="fine_type" name="fine_type" class="form-control">
                                <option value="0">*** Select Type ***</option>
                                <option value="1"> Speeding</option>
                                <option value="2"> Parking</option>
                                <option value="3"> Moving Violation</option>
                                <option value="4"> Expired Registration</option>
                                <option value="5"> No Drivers Licence</option>
                                <option value="6"> Other</option>
                            </select>

                        </div>
                    </div>
                     
                     <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Fine Ref</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="fine_ref" name="fine_ref" value=""
                                   placeholder="Enter Fine Ref" required>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="date" class="col-sm-2 control-label">Date Of Fine </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="dateoffine" name="date_of_fine"
                                   value="{{ old('date') }}" placeholder="Select date of fine  ...">
                        </div>
                    </div>

                     <div class="form-group ">
                        <label for="date" class="col-sm-2 control-label">Time of Fine</label>
                        <div class="col-sm-8">
                           <input type="text" class="form-control" id="timeoffine" name="time_of_fine" value="{{ old('time_of_fine') }}" placeholder="Select Time of Fine...">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Amount</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="amount" name="amount" value=""
                                   placeholder="Enter amount" required>
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Reduced</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="reduced" name="reduced" value=""
                                   placeholder="Enter reduced" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Additional Fee </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="additional_fee" name="additional_fee" value="" placeholder="Enter additional Fee " required>
                                  
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Location </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="location" name="location" value="" placeholder="Enter location " required>
                                  
                        </div>
                    </div>
                      <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Speed </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="speed" name="speed" value="" placeholder="Enter speed " required>
                                  
                        </div>
                    </div>
                      <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Zone Speed </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="zone_speed" name="zone_speed" value="" placeholder="Enter zone speed " required>
                                  
                        </div>
                    </div>

                        <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Driver </label>
                        <div class="col-sm-8">
                            <select class="form-control select2" style="width: 100%;"
                                    id="driver" name="driver">
                                <option value="0">*** Select Service Provider ***</option>
                                @foreach($employees as $driver)
                                    <option value="{{ $driver->id }}">{{ $driver->first_name . ' ' . $driver->surname}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                      <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Magistrate Office </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="magistrate_office" name="magistrate_office" value="" placeholder="Enter magistrate office " required>
                                  
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="date" class="col-sm-2 control-label">Court Date</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="courtdate" name="court_date"
                                   value="{{ old('date') }}" placeholder="Select  court date   ...">
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="date" class="col-sm-2 control-label">Paid Date</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="paiddate" name="paid_date"
                                   value="{{ old('date') }}" placeholder="Select paid date   ...">
                        </div>
                    </div>

                      <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Amount Paid </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="amount_paid" name="amount_paid" value="" placeholder="Enter amount paid  " required>
                                  
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
                        <label for="Status" class="col-sm-2 control-label">Fine Status </label>
                        <div class="col-sm-8">

                            <select id="fine_status" name="fine_status" class="form-control">
                                <option value="0">*** Select Status ***</option>
                                <option value="1"> Captured</option>
                                <option value="2"> Fine Queried</option>
                                <option value="3"> Fine Revoked</option>
                                <option value="4">  Fine Paid</option>
                            </select>

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

                       <div class="form-group supDoc-field{{ $errors->has('documents') ? ' has-error' : '' }}">
                        <label for="documents" class="col-sm-2 control-label">Attachment </label>
                        <div class="col-sm-8">

                            <input type="file" id="documents1" name="documents1"
                                   class="file file-loading" data-allowed-file-extensions='["pdf", "docx", "doc"]'
                                   data-show-upload="false">
                        </div>

                    </div>

                    
                    <input type="hidden" id="valueID" name="valueID"
                           value="{{ !empty($maintenance->id) ? $maintenance->id : ''}}">


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="edit_fines" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
        
           