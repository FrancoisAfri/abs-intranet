<div id="edit-module-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-module-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Module</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="module_name" name="module_name" value="" placeholder="Enter Module Name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="code_name" class="col-sm-2 control-label">Code Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="code_name" name="code_name" placeholder="Enter Module's Unique Code Name (all small letters, no spaces)" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Path</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="module_path" name="module_path" value="" placeholder="Enter Module Path" required >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="font_awesome" class="col-sm-2 control-label">Font Awesome</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="font_awesome" name="font_awesome" value="" placeholder="Enter Module Font Awesome" required >
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="update-module" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>