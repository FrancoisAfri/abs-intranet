<div id="stock-collection-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="stock-collection-modal">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Request collection</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group">
						<label for="name" class="col-sm-2 control-label">Note</label>
						<div class="col-sm-10">
							<div class="input-group">
								<textarea class="form-control" rows="3" cols="70" id="comment" name="comment"
										  placeholder="Enter Comment"></textarea>
							</div>
						</div>
					</div>
					<div class="form-group">
							<label for="document" class="col-sm-2 control-label">Document Upload</label>
							<div class="col-sm-10">
								<input type="file" id="document" name="document" class="file file-loading" data-allowed-file-extensions='["pdf", "docx", "xlsx", "doc", "xltm"]' data-show-upload="false">
							</div>
					</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Cancel</button>
                    <button type="button" id="close-request" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>