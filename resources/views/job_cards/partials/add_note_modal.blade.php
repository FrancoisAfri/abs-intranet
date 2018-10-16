<div id="add-note-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-note-form">
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
                        <label for="note" class="col-sm-2 control-label">Note</label>
                        <div class="col-sm-8">

                            <textarea class="form-control" id="note" name="note" placeholder="Enter note..."
                                      rows="3">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                    <input type="hidden" id="vehicle_id" name="vehicle_id"
                           value="{{$card->vehicle_id}}">
                    <input type="hidden" id="jobcard_id" name="jobcard_id"
                           value="{{ $card->id }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add_notes" class="btn btn-warning"><i class="fa fa-cloud-upload"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>