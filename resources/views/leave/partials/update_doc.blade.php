<div id="add-document-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="update-doc-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Reject Leave</h4>
                </div>
                <div class="modal-body">
                    <div id="leave-invalid-input-alert"></div>
                    <div id="success-alert"></div>
        
                    <div class="form-group">
                           <label for="days" class="col-sm-2 control-label">Rejection Reason</label>
                            <div class="col-sm-8">
                               <div class="input-group">
                                    <div class="input-group-addon">
                                       <i class="fa fa-comment-o"></i>
                                    </div>
                                    <textarea class="form-control" id="description" name="description" placeholder="Enter rejection reason ..." rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                                            
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add-new-doc" class="btn btn-primary">Save</button>
                    
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>