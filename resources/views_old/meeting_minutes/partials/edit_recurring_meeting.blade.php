<div id="edit-recurring-meeting-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-recurring-meeting-form">
			<input type="hidden" id="meeting_id" name="meeting_id" value="">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Recurring Meeting</h4>
                </div>
                 <div class="modal-body">
                    <div id="recurring-invalid-input-alert"></div>
                    <div id="recurring-success-alert"></div>           
                    <div class="form-group">
                        <label for="meeting_title" class="col-sm-3 control-label">Title</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <textarea class="form-control" rows="3" cols="70" id="meeting_title" name="meeting_title"
                                          placeholder="Enter Meeting Title"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="meeting_location" class="col-sm-3 control-label">Location</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <textarea class="form-control" rows="3" cols="70" id="meeting_location" name="meeting_location"
                                          placeholder="Enter Meeting Location"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="meeting_agenda" class="col-sm-3 control-label">Agenda</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <textarea class="form-control" rows="3" cols="70" id="meeting_agenda"
                                          name="meeting_agenda" placeholder="Enter Meeting Agenda"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="update-recurring-meeting" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>