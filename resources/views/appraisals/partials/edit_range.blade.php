<div id="edit-range-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-range-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Kpi Range</h4>
                </div>
                <div class="modal-body">
                    <div id="range-invalid-input-alert"></div>
                    <div id="range-success-alert"></div>
					<div class="form-group">
						<label for="range_from" class="col-sm-3 control-label">Range From</label>
						<div class="col-sm-9">
							<div class="input-group">
								<input type="number" class="form-control" id="range_from" name="range_from" value="" placeholder="Enter Range From" required>
							</div>
						</div>
                    </div>
					<div class="form-group">
                        <label for="range_to" class="col-sm-3 control-label">Range To</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="number" class="form-control" id="range_to" name="range_to" value="" placeholder="Enter Range To" required>
                            </div>
                        </div>
                    </div>
					<div class="form-group">
                        <label for="percentage" class="col-sm-3 control-label">Percentage</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="number" class="form-control" id="percentage" name="percentage" value="" placeholder="Percentage" >
                                <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="update-range" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>