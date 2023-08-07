<div id="add-file-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-file-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Upload File</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
					<div class="form-group">
                        <label for="document_name" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="document_name" name="document_name" value=""
                                   placeholder="Enter Name" required>
                        </div>
                    </div>
					<div class="form-group ">
                        <label for="description" class="col-sm-2 control-label">Description </label>
                        <div class="col-sm-8">
							<input type="text" class="form-control" id="description" name="description" value=""
                                   placeholder="Enter Description">
                        </div>
                    </div>
					<div class="form-group {{ $errors->has('visibility') ? ' has-error' : '' }}">
                        <label for="visibility" class="col-sm-2 control-label">Visibility</label>
                        <div class="col-sm-8">
							<select class="form-control select2" style="width: 100%;"
									id="visibility" name="visibility">
								<option value="">*** Select a Visibility ***</option>
								<option value="1">Private</option>
								<option value="2">All Employees</option>
							</select>
                        </div>
                    </div>
					<div class="form-group ">
                        <label for="current_version" class="col-sm-2 control-label">Version </label>
                        <div class="col-sm-8">
							<input type="number" class="form-control" id="current_version" name="current_version" value=""
                                   placeholder="Enter Version" required>
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
                                       class="file file-loading" data-allowed-file-extensions='["jpeg","png","webp","pdf", "docx", "doc", "xlsx", "pps", "ppt", "pptx"]'
                                       data-show-upload="false">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add_file" class="btn btn-warning"><i class="fa fa-cloud-upload"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
           