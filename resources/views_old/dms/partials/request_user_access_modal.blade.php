<div id="add-user-access-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-user-access-form">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">User Access Information</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
					<div class="form-group{{ $errors->has('access_gr_type') ? ' has-error' : '' }}">
						<label for="access_gr_type" class="col-sm-2 control-label">Access Type</label>
						<div class="col-sm-10">
							<div class="input-group">
								<label class="radio-inline"><input type="radio" id="rdo_fol_usr" name="access_usr_type" value="1" checked> Folder</label>
								<label class="radio-inline"><input type="radio" id="rdo_fil_usr" name="access_usr_type" value="2"> File</label>
							</div>
						</div>
                    </div>
					<div class="form-group folder-field{{ $errors->has('folder_id_usr') ? ' has-error' : '' }}">
                        <label for="folder_id_usr" class="col-sm-2 control-label">Folders</label>
                        <div class="col-sm-8">
                                <select class="form-control select2" style="width: 100%;"
                                        id="folder_id_usr" name="folder_id_usr">
                                    <option value="">*** Select a Folder ***</option>
                                    @foreach($folders as $folder)
                                        <option value="{{ $folder->id }}">{{ $folder->folder_name }}</option>
                                    @endforeach
                                </select>
                        </div>
                    </div>
					<div class="form-group file-field{{ $errors->has('file_id_usr') ? ' has-error' : '' }}">
                        <label for="file_id_usr" class="col-sm-2 control-label">Files</label>
                        <div class="col-sm-8">
                                <select class="form-control select2" style="width: 100%;"
                                        id="file_id_usr" name="file_id_usr">
                                    <option value="">*** Select a File ***</option>
                                    @foreach($files as $file)
                                        <option value="{{ $file->id }}">{{ $file->document_name }}</option>
                                    @endforeach
                                </select>
                        </div>
                    </div>
					<div class="form-group {{ $errors->has('employee_id') ? ' has-error' : '' }}">
                        <label for="employee_id" class="col-sm-2 control-label">Employee</label>
                        <div class="col-sm-8">
                                <select class="form-control select2" style="width: 100%;"
                                        id="employee_id" name="employee_id">
                                    <option value="">*** Select an Employee ***</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->surname }}</option>
                                    @endforeach
                                </select>
                        </div>
                    </div>
					<div class="form-group">
                        <label for="expiry_usr_date" class="col-sm-2 control-label">Expiry date</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="expiry_usr_date" name="expiry_usr_date"
                                   value="{{ old('expiry_usr_date') }}" placeholder="Select  Expiry Date  ...">
                        </div>
                    </div>
					<div class="form-group {{ $errors->has('adminusr_id') ? ' has-error' : '' }}">
                        <label for="adminusr_id" class="col-sm-2 control-label">Administrator</label>
                        <div class="col-sm-8">
                                <select class="form-control select2" style="width: 100%;"
                                        id="adminusr_id" name="adminusr_id">
                                    <option value="">*** Select an Employee ***</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->surname }}</option>
                                    @endforeach
                                </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add_user_access" class="btn btn-warning"><i class="fa fa-cloud-upload"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
           