<div id="add-new-number-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-number-form">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add New Kpi Number</h4>
                </div>
                <div class="modal-body">
                    <div id="number-invalid-input-alert"></div>
                    <div id="number-success-alert"></div>
					<div class="form-group">
						<label for="min_number" class="col-sm-3 control-label">Min Number</label>
						<div class="col-sm-9">
							<div class="input-group">
								<input type="number" class="form-control" id="min_number" name="min_number" value="" placeholder="Enter Min Number" required>
							</div>
						</div>
                    </div>
					<div class="form-group">
                        <label for="max_number" class="col-sm-3 control-label">Max Number</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="number" class="form-control" id="max_number" name="max_number" value="" placeholder="Enter Max Number" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add-number" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>