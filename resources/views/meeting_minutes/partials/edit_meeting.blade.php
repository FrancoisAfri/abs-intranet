<div id="edit-meeting-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-meeting-form">
			<input type="hidden" name="meeting_id" id="meeting_id" value="">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ $modal_title }}</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                        <div class="form-group">
							<label for="Meeting Name" class="col-sm-2 control-label">Title</label>
							<div class="col-sm-10">
								<div class="input-group">
									<input type="text" class="form-control" id="meeting_name" name="meeting_name" value="" placeholder="Enter Title">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="Meeting Location" class="col-sm-2 control-label">Location</label>
							<div class="col-sm-10">
								<div class="input-group">
									<input type="text" class="form-control" id="meeting_location" name="meeting_location" value="" placeholder="Enter Location">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="Meeting Agenda" class="col-sm-2 control-label">Agenda</label>
							<div class="col-sm-10">
								<div class="input-group">
								<textarea rows="4" cols="50" class="form-control" id="meeting_agenda" name="meeting_agenda" placeholder="Enter Agenda"></textarea>
								</div>
							</div>
						</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="update-meeting" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
</div>          