<div id="add-new-service_title-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-module-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add New Help Desk</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Name</label>
                             <div class="col-sm-8">
                            <input type="text" class="form-control" id="name" name="name" value="" placeholder="Enter Name">
                        </div>
                    </div>
					<div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Description</label>
                             <div class="col-sm-8">
                            <input type="text" class="form-control" id="description" name="description" value="" placeholder="Enter Description">
                        </div>
                    </div>
                 </div>  
                 <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add_service" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
	</div>
</div>