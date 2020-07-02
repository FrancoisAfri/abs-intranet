<div id="close-complaints-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" name="close-complaints-form" enctype="multipart/form-data">
			{{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Close Complaints</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group">
                           <label for="summary_corrective_measure" class="col-sm-2 control-label">Summary of Corrective Measure</label>
                            <div class="col-sm-8">
                               <div class="input-group">
                                    <div class="input-group-addon">
                                       <i class="fa fa-comment-o"></i>
                                    </div>
                                    <textarea class="form-control" id="summary_corrective_measure" name="summary_corrective_measure" placeholder="Enter Summary of Corrective Measure ..." rows="4"></textarea>
                                </div>
                            </div>
                    </div>
                    <div class="form-group">
						<label for="closing_comment" class="col-sm-2 control-label">Closing Comment</label>
						<div class="col-sm-8">
						   <div class="input-group">
								<div class="input-group-addon">
								   <i class="fa fa-comment-o"></i>
								</div>
								<textarea class="form-control" id="closing_comment" name="closing_comment" placeholder="Enter Closing Comment ..." rows="4"></textarea>
							</div>
						</div>
                    </div>
					<div class="form-group">
                        <label for="document_upload" class="col-sm-2 control-label">Document Upload</label>
                        <div class="col-sm-10">
                            <input type="file" id="document_upload" name="document_upload" class="file file-loading" data-allowed-file-extensions='["pdf", "docx", "xlsx", "doc", "xltm"]' data-show-upload="false">
                        </div>
                    </div>	
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Close</button>
                    <button type="button" id="close_complaint" class="btn btn-primary"><i class="fa fa-upload"></i> Save</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>