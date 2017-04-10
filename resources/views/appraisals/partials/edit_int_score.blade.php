<div id="edit-int-score-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-int-score-form">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit KPI Score</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group">
                        <label for="score" class="col-sm-2 control-label">Score</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-hashtag"></i></span>
                                <input type="number" class="form-control" id="score" name="score" placeholder="Enter the Score" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="percentage" class="col-sm-2 control-label">Percentage</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <input type="number" class="form-control" id="percentage" name="percentage" value="" placeholder="Enter the Percentage" required>
                                <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Close</button>
                    <button type="button" id="update-score" class="btn btn-primary"><i class="fa fa-upload"></i> Update</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>