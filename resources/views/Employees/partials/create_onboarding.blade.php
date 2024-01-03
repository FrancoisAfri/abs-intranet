<div id="create-onboarding-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="reject-leave-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">New Onboarding Leave</h4>
                </div>
                <div class="modal-body">
                    <div id="leave-invalid-input-alert"></div>
                    <div id="success-alert"></div>
        
                    <div class="form-group">
                           <label for="days" class="col-sm-2 control-label">Enter Email Address</label>
                            <div class="col-sm-8">
                               <div class="input-group">
                                    <div class="input-group-addon">
                                       <i class="fa fa-comment-o"></i>
                                    </div>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email">
                                </div>
                            </div>
                        </div>
                                            
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="send-link" class="btn btn-primary">Send Link</button>
                    
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>