<div id="add-new-prime-rate-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-prime-rate-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add New Prime Rate</h4>
                </div>
                <div class="modal-body">
                    <div id="prime-rate-invalid-input-alert"></div>
                    <div id="prime-rate-success-alert"></div>
                    <div class="form-group">
                        <label for="prime_rate" class="col-sm-3 control-label">Prime Rate<br>({{ date("d-m-Y") }})</label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-percent"></i>
                                </div>
                                <input type="number" class="form-control" id="prime_rate" name="prime_rate" value="{{ old('prime_rate') }}" placeholder="Enter rate (without the &percnt; sign)">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add-prime-rate" class="btn btn-primary">Add Rate</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>