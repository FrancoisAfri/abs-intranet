<div id="capture-results-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="capture-results-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ $modal_title }}</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <table class="table">
                        <!--<thead>
                        <tr>
                            <th>Subjects</th>
                            <th>Results</th>
                        </tr>
                        </thead>-->
                        <tbody>
                        <tr>
                            <td><label for="result[]" class="control-label">Math</label></td>
                            <td>
                                <div class="input-group">
                                    <input class="form-control input-sm" type="number" class="form-control" name="result[]" placeholder="Enter the Result...">
                                    <div class="input-group-addon">
                                        <i class="fa fa-percent"></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="result[]" class="control-label">Science</label></td>
                            <td>
                                <div class="input-group">
                                    <input class="form-control input-sm" type="number" class="form-control" name="result[]" placeholder="Enter the Result...">
                                    <div class="input-group-addon">
                                        <i class="fa fa-percent"></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Cancel</button>
                    <button type="button" id="add-prime-rate" class="btn btn-success"><i class="fa fa-upload"></i> Save Results</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>