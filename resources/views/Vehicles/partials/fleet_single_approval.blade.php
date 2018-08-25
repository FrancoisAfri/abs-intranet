<div id="fleet-reject-single-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="decline-vehicle-modal">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Fleet Rejection Reason</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                    <div class="form-group existing_one">
							<label for="name" class="col-sm-2 control-label">Rejection reason</label>
							<div class="col-sm-10">
								<div class="input-group">
									<textarea class="form-control" rows="3" cols="70" id="rejection_reason" name="rejection_reason"
											  placeholder="Enter Rejection reason"></textarea>
								</div>
							</div>
						</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Cancel</button>
                    <button type="button" id="save-rejection-reason" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>