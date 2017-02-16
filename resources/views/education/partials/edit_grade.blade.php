<div id="edit-grade-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-grade-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Modify Grade</h4>
                </div>
                <div class="modal-body">
                    <div id="grade-invalid-input-alert"></div>
                    <div id="grade-success-alert"></div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Grade</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="grade" name="grade" value="" placeholder="Enter Module Name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-3 control-label">Learner No</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="learner_number" name="learner_number" value="" placeholder="Enter Learner No" required >
                        </div>
                    </div>
					<div class="form-group">
                        <label for="path" class="col-sm-3 control-label">Male No</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="male_number" name="male_number" value="" placeholder="Enter Male No" required >
                        </div>
                    </div>
					<div class="form-group">
                        <label for="path" class="col-sm-3 control-label">Female No</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="female_number" name="female_number" value="" placeholder="Enter Female No" required >
                        </div>
                    </div>
					<div class="form-group">
                        <label for="path" class="col-sm-3 control-label">African No</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="african_number" name="african_number" value="" placeholder="Enter African No" required >
                        </div>
                    </div>
					<div class="form-group">
                        <label for="path" class="col-sm-3 control-label">Asian No</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="asian_number" name="asian_number" value="" placeholder="Enter Asian No" required >
                        </div>
                    </div><div class="form-group">
                        <label for="path" class="col-sm-3 control-label">Caucasian No</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="caucasian_number" name="caucasian_number" value="" placeholder="Enter Caucasian No" required >
                        </div>
                    </div><div class="form-group">
                        <label for="path" class="col-sm-3 control-label">Coloured No</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="coloured_number" name="coloured_number" value="" placeholder="Enter Coloured No" required >
                        </div>
                    </div><div class="form-group">
                        <label for="path" class="col-sm-3 control-label">Indian No</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="indian_number" name="indian_number" value="" placeholder="Enter Indian No" required >
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="update-grade" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>