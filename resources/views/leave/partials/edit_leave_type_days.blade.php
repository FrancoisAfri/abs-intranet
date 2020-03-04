<div id="edit-leave_taken-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="" method="POST" name="edit_leavetypedays-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Leave Type</h4>
                </div>
                <div class="modal-body">
                    <div id="leave-invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group">
                            <div class="form-group">
                        <label for="name" class="control-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="" placeholder="" disabled>
                            </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="form-group">
                                <label for="path" class="control-label">5-day employees</label>
                                <input type="text" class="form-control" id="day5min" name="day5min" value="" placeholder="Enter leave days" required >
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <label for="path" class=" control-label">5-day employee max</label>
                                <input type="text" class="form-control" id="day5max" name="day5max" value="" placeholder="Enter leave days" required >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5">

                            <div class="form-group">
                                <label for="path" class="control-label">6-day employees</label>
                                <input type="text" class="form-control" id="day6min" name="day6min" value="" placeholder="Enter leave days" required >
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <label for="path" class=" control-label">6-day employee max</label>
                                <input type="text" class="form-control" id="day6max" name="day6max" value="" placeholder="Enter leave days" required >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="form-group">
                                <label for="path" class="control-label">Shift employees</label>
                                <input type="text" class="form-control" id="shiftmin" name="shiftmin" value="" placeholder="Enter leave days" required >
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <label for="path" class=" control-label">Shift employee max</label>
                                <input type="text" class="form-control" id="shiftmax" name="shiftmax" value="" placeholder="Enter leave days" required >
                            </div>
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="update-leave_taken" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>