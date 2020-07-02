<div id="close-jobcard-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="close-jobcard-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">JobCard
                        # {{ !empty($card->jobcard_number) ? $card->jobcard_number : ''}}</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <input type="hidden" name="ID" value="">
                    <div class="form-group ">
                        <label for="completion_comment" class="col-sm-2 control-label">Comment</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="completion_comment" name="completion_comment" placeholder="Enter Comment..."
                                      rows="3">{{ old('completion_comment') }}</textarea>
                        </div>
                    </div>
                    <input type="hidden" id="jobcard_id" name="jobcard_id" value="{{ $card->id }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="close_jobcard" class="btn btn-warning"><i class="fa fa-cloud-upload"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>