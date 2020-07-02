<div id="end-task-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" name="end-task-form" enctype="multipart/form-data">
			<input type="hidden" name="task_id" id="task_id" value="">
			<input type="hidden" name="employee_id" id="employee_id" value="">
			<input type="hidden" name="upload_required" id="upload_required" value="">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">End Task</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group">
                        <label for="notes" class="col-sm-2 control-label">Note</label>
                        <div class="col-sm-10">
						<textarea rows="4" cols="50" class="form-control" id="notes" name="notes"></textarea>
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
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Close</button>
                    <button type="button" id="end-task" class="btn btn-primary"><i class="fa fa-upload"></i> Save</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>