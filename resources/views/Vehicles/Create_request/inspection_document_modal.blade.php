<div id="add-document-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-document-form">
                {{ csrf_field() }}


                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"> Upload new Documents</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                    <div class="form-group">
                        <label for="type" class="col-sm-2 control-label"> Document Type</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-filter"></i>
                                </div>

                                <select id="doctype" name="doctype" class="form-control">
                                    <!-- <option value="0">*** Select Fuel Type ***</option> -->
                                    <option value="1"> Inspection</option>
                                    <option value="2"> General Documents</option>
                                    <option value="3"> Tracking Certificates</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="path" class="col-sm-2 control-label">Description </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="description" name="description"
                                   value=" " placeholder="Enter  Description ..." >
                        </div>
                    </div>

                    <div class="form-group supDoc-field{{ $errors->has('documents') ? ' has-error' : '' }}">
                        <label for="documents" class="col-sm-2 control-label">Upload </label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-file-pdf-o"></i>
                                </div>
                                <input type="file" id="documents" name="documents"
                                       class="file file-loading" data-allowed-file-extensions='["pdf", "docx", "doc"]'
                                       data-show-upload="false">
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="vehicleID" name="vehicleID"
                           value="{{ !empty($vehiclebookings->vehicle_id) ? $vehiclebookings->vehicle_id : ''}}">    
                         
                    <input type="hidden" id="bookingID" name="bookingID"
                           value="{{ !empty($collect->id) ? $collect->id : ''}}">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add_document" class="btn btn-warning"><i class="fa fa-cloud-upload"></i>
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

           
