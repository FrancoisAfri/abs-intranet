<div id="add-new-module-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-module-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add New Module</h4>
                </div>
                <div class="modal-body">
                    <div id="module-invalid-input-alert"></div>
                    <div id="module-success-alert"></div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Name</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" id="module_name" name="module_name" value="" placeholder="Enter Module Name" >
                            </div>
                        </div>
                    </div> 
					<div class="form-group">
                        <label for="path" class="col-sm-3 control-label">Path</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" id="module_path" name="module_path" value="" placeholder="Enter Module Path"  >
                            </div>
                        </div>
                    </div>
					<div class="form-group">
                        <label for="font_awesome" class="col-sm-3 control-label">Font awsone</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" id="font_awesome" name="font_awesome" value="" placeholder="Enter Module Font Awesome"  >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add-module" class="btn btn-primary">Add Module</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>