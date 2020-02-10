<div id="add-company-access-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-company-access-form">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Company Access Information</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
					<div class="form-group{{ $errors->has('access_com_type') ? ' has-error' : '' }}">
						<label for="access_com_type" class="col-sm-2 control-label">Access Type</label>
						<div class="col-sm-10">
							<div class="input-group">
								<label class="radio-inline"><input type="radio" id="rdo_fol_com" name="access_com_type" value="1" checked> Folder</label>
								<label class="radio-inline"><input type="radio" id="rdo_fil_com" name="access_com_type" value="2"> File</label>
							</div>
						</div>
                    </div>
					@foreach($division_levels as $division_level)
						<div class="form-group {{ $errors->has('division_level_' . $division_level->level) ? ' has-error' : '' }}">
							<label for="{{ 'division_level_' . $division_level->level }}"
								   class="col-sm-2 control-label">{{ $division_level->name }}</label>
							<div class="col-sm-8">
								<select id="{{ 'division_level_' . $division_level->level }}"
										name="{{ 'division_level_' . $division_level->level }}"
										class="form-control"
										onchange="divDDOnChange(this, null, 'add-company-access-modal')">
								</select>
							</div>
						</div>
                    @endforeach
					<div class="form-group folder-field{{ $errors->has('folder_id') ? ' has-error' : '' }}">
                        <label for="folder_id" class="col-sm-2 control-label">Folders</label>
                        <div class="col-sm-8">
                                <select class="form-control select2" style="width: 100%;"
                                        id="folder_id" name="folder_id">
                                    <option value="">*** Select a Folder ***</option>
                                    @foreach($folders as $folder)
                                        <option value="{{ $folder->id }}">{{ $folder->folder_name }}</option>
                                    @endforeach
                                </select>
                        </div>
                    </div>
					<div class="form-group file-field{{ $errors->has('file_id') ? ' has-error' : '' }}">
                        <label for="file_id" class="col-sm-2 control-label">Files</label>
                        <div class="col-sm-8">
                                <select class="form-control select2" style="width: 100%;"
                                        id="file_id" name="file_id">
                                    <option value="">*** Select a File ***</option>
                                    @foreach($files as $file)
                                        <option value="{{ $file->id }}">{{ $file->document_name }}</option>
                                    @endforeach
                                </select>
                        </div>
                    </div>
					<div class="form-group ">
                        <label for="expiry_date" class="col-sm-2 control-label">Expiry date</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="expiry_date" name="expiry_date"
                                   value="{{ old('expiry_date') }}" placeholder="Select  Expiry Date  ...">
                        </div>
                    </div>
					<div class="form-group {{ $errors->has('admin_id') ? ' has-error' : '' }}">
                        <label for="admin_id" class="col-sm-2 control-label">Administrator</label>
                        <div class="col-sm-8">
                                <select class="form-control select2" style="width: 100%;"
                                        id="admin_id" name="admin_id">
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
                    <button type="button" id="add_company_access" class="btn btn-warning"><i class="fa fa-cloud-upload"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
           