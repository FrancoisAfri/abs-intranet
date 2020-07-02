<div id="add-note-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-note-form">
                 {{ csrf_field() }}
              
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"> Upload new Notes</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group">
                        <label for="captured_by" class="col-sm-2 control-label">Captured By</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="captured_by" name="captured_by" value="{{ $name }}"
                                   placeholder="{{ $name }}" required readonly="">
                        </div>
                    </div>
                    <input type="hidden" name="ID" value="">

                    <div class="form-group ">
                        <label for="days" class="col-sm-2 control-label">Note</label>
                        <div class="col-sm-8">
                           
                                <textarea class="form-control" id="notes" name="notes" placeholder="Enter notes..." rows="3">{{ old('notes') }}</textarea> 
                        </div>
                     </div>
                      <div class="form-group supDoc-field{{ $errors->has('documents') ? ' has-error' : '' }}">
                        <label for="documents" class="col-sm-2 control-label">Attachment </label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-file-pdf-o"></i>
                                </div>
                                <input type="file" id="documents" name="documents"
                                       class="file file-loading" data-allowed-file-extensions='["pdf", "docx", "doc"]'
                                       data-show-upload="false">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="valueID" name="valueID"
                           value="{{ !empty($maintenance->id) ? $maintenance->id : ''}}">
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