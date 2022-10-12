<div id="cancel-leave-application-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="cancel-leave-application-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Cancel Leave Application</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                    <div class="callout callout-info">
                        <h4><i class="icon fa fa-info"></i> Cancel this application?</h4>

                        <p>You are about to cancel a leave application. Please provide the cancellation reason below.</p>
                    </div>

                    <div class="form-group{{ $errors->has('cancellation_reason') ? ' has-error' : '' }}">
                        <label for="{{ 'cancellation_reason' }}" class="col-sm-2 control-label">Cancellation Reason</label>

                        <div class="col-sm-10">
                            <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" placeholder="Cancellation Reason"
                            >{{ old('cancellation_reason') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-arrow-left"></i> No</button>
                    <button type="button" id="cancel-leave-application" class="btn btn-primary"><i class="fa fa-check"></i> Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>