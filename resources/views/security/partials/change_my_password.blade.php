<div id="myPasswordModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="password_form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Change My Password</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group">
                        <label for="current_password" class="col-sm-3 control-label">Current Password</label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-lock"></i>
                                </div>
                                <input type="password" class="form-control" id="current_password" name="current_password" value="{{ old('current_password') }}" placeholder="Current password">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="new_password" class="col-sm-3 control-label">New Password</label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-lock"></i>
                                </div>
                                <input type="password" class="form-control" id="new_password" name="new_password" value="{{ old('new_password') }}" placeholder="New password">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password" class="col-sm-3 control-label">Confirm Password</label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-lock"></i>
                                </div>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" value="{{ old('confirm_password') }}" placeholder="Retype new password">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" name="command" id="my-password" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->