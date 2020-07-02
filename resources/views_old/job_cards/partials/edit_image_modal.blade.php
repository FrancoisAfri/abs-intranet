<div id="edit-package-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-image-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Image</h4>
                </div> 
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                  <input type="hidden" id="valueID" name="valueID"
                           value="{{ !empty($images->vehicle_id) ? $images->vehicle_id : ''}}">
                    <div class="form-group single-field">
                        <label for="image" class="col-sm-3 control-label">Upload</label>

                        <div class="col-sm-8">
                            <input type="file" id="image" name="image" multiple class="file file-loading"
                                   data-allowed-file-extensions='["jpg", "jpeg", "png"]' data-show-upload="false">
							<input src="" type="image" id="jc_image" width="250"
                                         height="200" class="img-responsive img-thumbnail">
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="edit_image" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>