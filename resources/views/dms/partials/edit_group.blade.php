<div id="edit-group-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-group-form">
			<input type="hidden" id="meeting_id" name="meeting_id" value="">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Group</h4>
                </div>
                 <div class="modal-body">
                    <div id="group-invalid-input-alert"></div>
                    <div id="group-success-alert"></div>           
                    <div class="form-group">
                        <label for="group_name" class="col-sm-3 control-label">Name</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <textarea class="form-control" rows="3" cols="70" id="group_name" name="group_name"
                                          placeholder="Enter Name"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="update-group" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>