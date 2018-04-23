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
                           value="{{ !empty($maintenance->id) ? $maintenance->id : ''}}">
					<!--<div class="form-group{{ $errors->has('upload_type') ? ' has-error' : '' }}">
						<label for="upload_type" class="col-sm-3 control-label">Upload Type</label>
						<div class="col-sm-8">
							<label class="radio-inline" style="padding-left: 0px;">
								<input type="radio" id="rdo_single" name="upload_type" value="1" checked> Single
							</label>
							<label class="radio-inline"><input type="radio" id="rdo_zip" name="upload_type"
															   value="2"> Bulk</label>
						</div>
                    </div>-->
                   <!-- <div class="form-group single-field">
                        <label for="path" class="col-sm-3 control-label">Image name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="name" name="name" value=""
                                   placeholder="Enter name" required>
                        </div>
                    </div>
                    <div class="form-group single-field">
                        <label for="path" class="col-sm-3 control-label">Image Description</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="description" name="description" value=""
                                   placeholder="Enter Description" required>
                        </div>
                    </div>-->
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
                    <!--<div class="form-group zip-field">
                        <label for="image" class="col-sm-3 control-label">Upload Zip File</label>

                        <div class="col-sm-8">

                            <input type="file" id="images" name="images" class="file file-loading"
                                   data-allowed-file-extensions='["jpg", "jpeg", "png"]' data-show-upload="true">
                        </div>
                    </div>-->
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
           