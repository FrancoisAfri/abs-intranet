<div id="add-returnimage-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" name="add-new-returnimage-form" enctype="multipart/form-data">
                {{ csrf_field() }}


                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Upload new Image</h4>
                </div>


                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                    <div class="form-group{{ $errors->has('image_type') ? ' has-error' : '' }}">
                        <label for="Leave_type" class="col-sm-3 control-label"> Upload Type</label>

                        <div class="col-sm-8">
                            <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_single"
                                        name="image_type" value="1" checked> Single </label>
                                                                                          
                            <!-- <label class="radio-inline"><input type="radio" id="rdo_zip" name="image_type" value="2">
                                Bulk </label>-->
                        </div>
                    </div>

                    <div class="form-group Single-field">
                        <label for="path" class="col-sm-3 control-label">Image name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="name" name="name" value=""
                                   placeholder="Enter name" required>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="path" class="col-sm-3 control-label">Description </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="description" name="description"
                                   value=" " placeholder="Enter  Description ..." >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="image" class="col-sm-3 control-label">Upload</label>

                        <div class="col-sm-8">
                            <input type="file" id="image" name="image" class="file file-loading"
                                   data-allowed-file-extensions='["jpg", "jpeg", "png"]' data-show-upload="false">
                        </div>
                    </div>

                    <div class="form-group zip-field">
                        <label for="days" class="col-sm-3 control-label">Note</label>
                        <div class="col-sm-8">

                            <textarea class="form-control" id="note" name="note"
                                      placeholder="Please make sure you zip the files you wish to upload and then upload the zip file. The files in zip file will then be uploaded..."
                                      rows="3" readonly="">{{ old('note') }}</textarea>

                        </div>
                    </div>

                    <div class="form-group zip-field">
                        <label for="image" class="col-sm-3 control-label">Upload Zip File</label>

                        <div class="col-sm-8">

                            <input type="file" id="images" name="images" class="file file-loading"
                                   data-allowed-file-extensions='["jpg", "jpeg", "png"]' data-show-upload="false">
                        </div>
                    </div>

                    <input type="hidden" id="vehicleID" name="vehicleID"
                           value="{{ !empty($vehiclebookings->vehicle_id) ? $vehiclebookings->vehicle_id : ''}}">    
                         
                    <input type="hidden" id="bookingID" name="bookingID"
                           value="{{ !empty($returnVeh->id) ? $returnVeh->id : ''}}">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add-return_image" class="btn btn-warning"><i
                                class="fa fa-cloud-upload"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
           