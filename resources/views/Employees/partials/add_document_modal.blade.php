<div id="add-document-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-document-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add New Document </h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group">
                        <label for="doc_description" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="doc_description" name="doc_description" value=""
                                   placeholder="Enter Description" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="date_from" class="col-sm-2 control-label">Date From</label>

                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="text" class="form-control datepicker" id="date_from" name="date_from"
                                       value="{{ old('date_from') }}" placeholder="Click to Select a Date...">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="expirydate" class="col-sm-2 control-label">Expiry Date</label>

                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="text" class="form-control datepicker" id="expirydate" name="expirydate"
                                       value="{{ old('expirydate') }}" placeholder="Click to Select a Date...">
                            </div>
                        </div>
                    </div>
                    <div class="form-group supDoc-field{{ $errors->has('supporting_docs') ? ' has-error' : '' }}">
                        <label for="days" class="col-sm-2 control-label">Supporting Document</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-upload"></i>
                                </div>
                                <input type="file" id="supporting_docs" name="supporting_docs" class="file file-loading"
                                       data-allowed-file-extensions='["pdf","docx","txt","doc"]'
                                       data-show-upload="false">
                            </div>
                        </div>
                    </div>
					<div class="form-group {{ $errors->has('doc_type_id') ? ' has-error' : '' }}">
						<label for="doc_type_id" class="col-sm-2 control-label">Document Type</label>
						<div class="col-sm-8">
							<div class="input-group">
								<select class="form-control select2" style="width:170px;" id="doc_type_id" name="doc_type_id">	
								<option value="">*** Select document type ***</option>
								@foreach($types as $type)
									<option value="{{ $type->id }}">{{ $type->name}}</option>
								@endforeach
								</select>
							</div>
						</div>
					</div>
                    <input type="hidden" id="hr_person_id" name="hr_person_id"
                           value="{{ !empty($employee->id) ? $employee->id : ''}}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add_document" class="btn btn-primary"><i class="fa fa-cloud-upload"></i>
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
           