<div id="add-new-template-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-template-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add New Template</h4>
                </div>
                <div class="modal-body">
                    <div id="template-invalid-input-alert"></div>
                    <div id="template-success-alert"></div>
                    <div class="form-group">
                        <label for="template" class="col-sm-3 control-label">Name</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" id="template" name="template" value="" placeholder="Enter Template Name" >
                            </div>
                        </div>
                    </div>
					<div class="form-group">
						<label for="job_title_id" class="col-sm-3 control-label">Job Title</label>

						<div class="col-sm-9">
							<div class="input-group">
								<select id="job_title_id" name="job_title_id" class="form-control" required>
									<option value="">*** Select a Job Title ***</option>
									@foreach($JobTitles as $JobTitle)
										<option value="{{ $JobTitle->id }}">{{ $JobTitle->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add-template" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>