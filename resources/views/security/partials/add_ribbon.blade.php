<div id="add-new-ribbon-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-ribbon-form">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add New ribbon</h4>
                </div>
                <div class="modal-body">
                    <div id="ribbon-invalid-input-alert"></div>
                    <div id="ribbon-success-alert"></div>
                    <div class="form-group">
                        <label for="ribbon_name" class="col-sm-3 control-label">Name</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" id="ribbon_name" name="ribbon_name" value="" placeholder="Enter ribbon Name" required>
                            </div>
                        </div>
                    </div> 
					<div class="form-group">
                        <label for="description" class="col-sm-3 control-label">Description</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" id="description" name="description" value="" placeholder="Enter ribbon Description" required>
                            </div>
                        </div>
                    </div> 
					<div class="form-group">
                        <label for="ribbon_path" class="col-sm-3 control-label">Path</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" id="ribbon_path" name="ribbon_path" value="" placeholder="Enter ribbon Path" required >
                            </div>
                        </div>
                    </div>
					<div class="form-group">
                        <label for="sort_order" class="col-sm-3 control-label">Sort Number</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="number" class="form-control" id="sort_order" name="sort_order" value="" placeholder="Enter Sort Number" required >
                            </div>
                        </div>
                    </div>
					<div class="form-group">
                        <label for="access_level" class="col-sm-3 control-label">Access Right</label>
                        <div class="col-sm-9">
						<select class="form-control" name="access_level" id="access_level" placeholder="" required>
						<option value="0">None</option>
						<option value="1">Read</option>
						<option value="2">Write</option>
						<option value="3">Modify</option>
						<option value="4">Admin</option>
						<option value="5">Super User</option>
					  </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add-ribbon" class="btn btn-primary">Add ribbon</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>