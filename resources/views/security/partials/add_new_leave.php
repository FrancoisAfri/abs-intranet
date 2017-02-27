<div id="add-new-leave-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-leave-form">
                <!-- {{ csrf_field() }} -->

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add New leave type</h4>
                </div>
                <div class="modal-body">
                    <div id="leave-invalid-input-alert"></div>
                    <div id="leave-success-alert"></div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Name</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" id="name" name="name" value="" placeholder="Enter Module Name" >
                            </div>
                        </div>
                    </div> 
					<div class="form-group">
                        <label for="path" class="col-sm-3 control-label">Description</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" id="description" name="description" value="" placeholder="Enter Description"  >
                            </div>
                        </div>
                    </div>
					
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add-leave" class="btn btn-primary">Add Leave</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>