<div id="add-level-modal" class="modal modal-default fade">
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
                    <div id="module-invalid-input-alert"></div>
                    <div id="module-success-alert"></div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" name="name" value="" placeholder="Enter Module Name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-3 control-label">Manager</label>
                        <div class="col-sm-9">
                             <select>
                             <option selected="selected" value="" type="text" class="form-control" id="manager_id" name="manager_id">*** Manager ***</option>
                              <option value="">Level 5</option>
                              <option value="">Level 4</option>
                              <option value="">Level 3</option>
                              <option value="">Level 2</option>
                              <option value="">Level 1</option>
                            </select>
  
                        </div>
                    </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="update-module" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
