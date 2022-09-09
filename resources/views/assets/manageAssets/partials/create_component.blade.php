<div id="add-component-modal" class="modal modal-default fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-component-form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Component</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="name" name="name" value=""
                                   placeholder="Enter name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label"> Description</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="description" name="description" value=""
                                   placeholder="Enter Description" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label"> Size</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="size" name="size" value=""
                                   placeholder="Enter Serial Number" required>
                        </div>
                    </div>

                    <input type="hidden" value="{{ $asset->id }}" name="asset_id" id="asset_id">

                    <input type="hidden" value="Un Allocated" name="asset_status" id="asset_status">

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add-component" class="btn btn-warning"><i
                                class="fa fa-cloud-upload"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



