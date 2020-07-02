<div id="document-jobcard-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="document-jobcard-form">
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
					<div class="form-group">
                        <label for="completion_date" class="col-sm-2 control-label">Completion Date</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control datepicker" id="completion_date"
                                   name="completion_date" value="{{ old('completion_date') }}"
                                   placeholder="Click to Select a Date...">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="service_time" class="col-sm-2 control-label">Service Time</label>
                        <div class="col-sm-8">

                            <input type="number" class="form-control" id="service_time" name="service_time" value=""
                                   placeholder="Enter service time">
                        </div>
                    </div>
					<div class="form-group supDoc-field{{ $errors->has('documents') ? ' has-error' : '' }}">
                        <label for="documents" class="col-sm-2 control-label">Service File Upload </label>
                        <div class="col-sm-8">

                            <input type="file" id="service_file_upload" name="service_file_upload"
                                   class="file file-loading" data-allowed-file-extensions='["pdf", "docx", "doc"]'
                                   data-show-upload="false">
                        </div>
                    </div>
                    <input type="hidden" id="jobcard_id" name="jobcard_id" value="{{ $card->id }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="document_jobcard" class="btn btn-warning"><i class="fa fa-cloud-upload"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>