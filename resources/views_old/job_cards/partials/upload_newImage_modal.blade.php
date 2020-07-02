<div id="upload-image-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" name="add-new-vehicleImage-form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Upload new Image</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                    <input type="hidden" id="valueID" name="valueID"
                           value="{{ !empty($images->vehicle_id) ? $images->vehicle_id : ''}}">

                    <div class="form-group single-field">
                        <label for="image" class="col-sm-3 control-label">Upload</label>

                        <div class="col-sm-8">
                            <input type="file" id="image" name="images[]" multiple class="file file-loading"
                                   data-allowed-file-extensions='["jpg", "jpeg", "png"]' data-show-upload="false">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="days" class="col-sm-3 control-label">Note</label>
                        <div class="col-sm-8">

                            <textarea class="form-control" id="note"
                                      placeholder="For multiple  pictures upload Click ctrl A to select all pictures in the directory. Or choose the one you want to upload ..."
                                      rows="3" readonly="">{{ old('note') }}</textarea>
                        </div>
                    </div>

                    <input type="hidden" id="jobcard_id" name="jobcard_id"
                           value="{{ !empty($images->id) ? $images->id : ''}}">
                   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add-vehicle_image" class="btn btn-warning"><i
                                class="fa fa-cloud-upload"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
           