<div id="add-gauge-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-module-form">
                {{ csrf_field() }}


                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Fuel Tank</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="box-body" id="view_users">

                        <div class="form-group">
                            <label for="tank_name" class="col-sm-2 control-label">Tank Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="tank_name" name="tank_name" value=""
                                       placeholder="Enter Tank name" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tank_location" class="col-sm-2 control-label">Tank Location</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="tank_location" name="tank_location" value=""
                                       placeholder="Enter tank location" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tank_description" class="col-sm-2 control-label">Tank Description</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="tank_description" name="tank_description"
                                       value=""
                                       placeholder="Enter tank description" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tank_capacity" class="col-sm-2 control-label">Tank Capacity</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="tank_capacity" name="tank_capacity"
                                       value=""
                                       placeholder="Enter tank capacity" required>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" id="add-fueltank" class="btn btn-warning"><i
                                    class="fa fa-cloud-upload"></i> Save
                        </button>
                    </div>
            </form>
        </div>
    </div>
</div>
</div>
           