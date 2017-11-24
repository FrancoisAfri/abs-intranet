<div id="edit-note-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-note-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Notes</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                    <div class="form-group">
                        <label for="captured_by" class="col-sm-2 control-label">Captured By</label>
                        <div class="col-sm-8">
                            <select class="form-control select2" style="width: 100%;" id="capturedby"
                                    name="captured_by">
                                <option value="">*** Select a Role ***</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}"> {{ !empty($employee->first_name . ' ' . $employee->surname) ? $employee->first_name . ' ' . $employee->surname : ''}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="path" class="col-sm-2 control-label"> Date Captured </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="date_captured" name="date_captured"
                                   value="{{ old('date_captured') }}" placeholder="Select  Expiry date ...">
                        </div>
                    </div>

                    <input type="hidden" name="ID" value="">

                    <div class="form-group ">
                        <label for="days" class="col-sm-2 control-label">Note</label>
                        <div class="col-sm-8">

                            <textarea class="form-control" id="notes" name="notes" placeholder="Enter notes..."
                                      rows="3">{{ old('notes') }}</textarea>

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

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="edit_note" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
        
           