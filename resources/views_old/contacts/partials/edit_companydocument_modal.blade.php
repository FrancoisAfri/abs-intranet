<div id="edit-newdoc-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" name="edit-newdoc-form" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Company Document</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group">
                        <label for="name_update" class="col-sm-2 control-label">Doc Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="name_update" name="name_update" value=""
                                   placeholder="Enter Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description_update" class="col-sm-2 control-label">Doc Description</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="description_update" name="description_update" value=""
                                   placeholder="Enter Description">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="date_from_update" class="col-sm-2 control-label">Date From</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control datepicker" id="date_from_update" name="date_from_update"
                                       value="{{ old('date_from') }}" placeholder="Click to Select a Date...">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exp_date_update" class="col-sm-2 control-label">Expiry Date</label>

                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control datepicker" id="expirydate" name="expirydate"
                                       value="{{ old('expirydate') }}" placeholder="Click to Select a Date...">
                            </div>
                        </div>
                    </div>
                    <div class="form-group supDoc-field{{ $errors->has('supporting_docs_update') ? ' has-error' : '' }}">
                        <label for="supporting_docs_update" class="col-sm-2 control-label">Supporting Document</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-upload"></i>
                                </div>
                                <input type="file" id="supporting_docs_update" name="supporting_docs_update" class="file file-loading"
                                       data-allowed-file-extensions='["pdf","docx","txt","doc"]'
                                       data-show-upload="false">
                            </div>
                        </div>
                    </div>
					<div class="form-group {{ $errors->has('doc_type_update') ? ' has-error' : '' }}">
						<label for="doc_type_update" class="col-sm-2 control-label">Document Type</label>
						<div class="col-sm-8">
							<div class="input-group">
								<select class="form-control" style="width:170px;" id="doc_type_update" name="doc_type_update">	
								<option value="">*** Select a Document Type ***</option>
								@foreach($types as $type)
									<option value="{{ $type->id }}">{{ $type->name}}</option>
								@endforeach
								</select>
							</div>
						</div>
					</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="edit_doc" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
        
           