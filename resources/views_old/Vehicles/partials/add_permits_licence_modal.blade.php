<div id="add-permit-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-permit-form" enctype="multipart/form-data">
                {{ csrf_field() }}


                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"> Add Permits/Licences </h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                    <div class="form-group safe-field">
                        <label for="permit_licence" class="col-sm-3 control-label">License Permit/Licence </label>
                        <div class="col-sm-8">
                            <select class="form-control select2" style="width: 100%;" id="permit_licence"
                                    name="permit_licence">
                                <option value="0">*** Select a Permit/Licence ***</option>
                                @foreach($permitlicence as $permit)
                                    <option value="{{ $permit->id }}"> {{ $permit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group safe-field">
                        <label for="supplier_id" class="col-sm-3 control-label">Supplier </label>
                        <div class="col-sm-8">
                            <select class="form-control select2" style="width: 100%;" id="supplier_id" name="supplier_id">
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
                                   value=" {{ old('permits_licence_no') }}"
                                   placeholder="Enter permit licence number ...">
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="path" class="col-sm-3 control-label">Date Issued </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="date_issued" name="date_issued"
                                   value="{{ old('date_issued') }}" placeholder="Select  Issued date ...">
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="path" class="col-sm-3 control-label">Expiry Date </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="exp_date" name="exp_date"
                                   value="{{ old('exp_date') }}" placeholder="Select Expiry Date ...">
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
                            <input type="text" class="form-control" id="captured_by" name="captured_by"
                                   value="{{ !empty($name)  ? $name : ''}}" placeholder="Select  User ..." readonly="">
                        </div>
                    </div>
                    <div class="form-group supDoc-field{{ $errors->has('attachement') ? ' has-error' : '' }}">
                        <label for="attachement" class="col-sm-3 control-label">Document </label>
                        <div class="col-sm-8">
                            <input type="file" id="attachement" name="attachement"
                                   class="file file-loading" data-allowed-file-extensions='["pdf", "docx", "doc"]'
                                   data-show-upload="false">
                        </div>
                    </div>
					<input type="hidden" id="valueID" name="valueID"
                        value="{{ !empty($maintenance->id) ? $maintenance->id : ''}}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add_permit" class="btn btn-warning"><i class="fa fa-cloud-upload"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<script type="text/javascript">

</script>
           