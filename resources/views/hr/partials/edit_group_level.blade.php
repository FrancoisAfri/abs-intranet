<div id="edit-grouplevel-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-grouplevel-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="group_level_title">Edit Group Level</h4>
                </div>
                <div class="modal-body">
                    <div id="ribbon-invalid-input-alert"></div>
                    <div id="ribbon-success-alert"></div>
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" name="name" value="" placeholder="Enter Name" required>
                        </div>
                    </div> 
					<div class="form-group">
                        <label for="plural_name" class="col-sm-2 control-label">Plural Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="plural_name" name="plural_name" value="" placeholder="Enter Plural Name" required>
                        </div>
                    </div> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="save_grouplevel" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>