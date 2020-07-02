<div id="edit-permit-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-permit-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Permit/Licence</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group">
                        <label for="permit_licence" class="col-sm-3 control-label">Permit/Licence </label>
                        <div class="col-sm-8">
							<select id="permit_licence" name="permit_licence" class="form-control">
                                <option value="0">*** Select a Permit/Licence ***</option>
                                @foreach($permitlicence as $permit)
                                    <option value="{{ $permit->id }}"> {{ $permit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                   <div class="form-group">
                        <label for="supplier_id" class="col-sm-3 control-label">Supplier </label>
                        <div class="col-sm-8">
							 <select id="supplier_id" name="supplier_id" class="form-control">
                                <option value="0">*** Select a Supplier ***</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}"> {{$company->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="path" class="col-sm-3 control-label">Permit/Licence Number </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="permits_licence_no" name="permits_licence_no"
                                   value="{{ old('permits_licence_no') }}"
                                   placeholder="Enter permit licence number ...">
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="path" class="col-sm-3 control-label">Date Issued </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="date_issued" name="date_issued"
                                   value="" placeholder="Select  issue date ...">
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="path" class="col-sm-3 control-label">Expiry Date </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="exp_date" name="exp_date"
                                   value=" " placeholder="Select  issue date ...">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Status" class="col-sm-3 control-label">Status </label>
                        <div class="col-sm-8">
                            <select id="status" name="status" class="form-control">
                                <option value="0">*** Select Status ***</option>
                                <option value="1"> Active</option>
                                <option value="2"> InActive</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="path" class="col-sm-3 control-label">Captured By </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="employee" name="employee"
                                   value="{{ !empty($name)  ? $name : ''}}" placeholder="Select  User ..." readonly="">
                        </div>
                    </div>
                    <div class="form-group supDoc-field{{ $errors->has('documents') ? ' has-error' : '' }}">
                        <label for="documents" class="col-sm-3 control-label">Attachment </label>
                        <div class="col-sm-8">
                            <input type="file" id="documents" name="documents"
                                   class="file file-loading" data-allowed-file-extensions='["pdf", "docx", "doc"]'
                                   data-show-upload="false">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="edit_permit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>