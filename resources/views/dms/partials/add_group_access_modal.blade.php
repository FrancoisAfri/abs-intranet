<div id="add-group-access-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-group-access-form">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Group Access Information</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
					<div class="form-group{{ $errors->has('access_gr_type') ? ' has-error' : '' }}">
						<label for="access_gr_type" class="col-sm-2 control-label">Access Type</label>
						<div class="col-sm-10">
							<div class="input-group">
								<label class="radio-inline"><input type="radio" id="rdo_fol_gr" name="access_gr_type" value="1" checked> Folder</label>
								<label class="radio-inline"><input type="radio" id="rdo_fil_gr" name="access_gr_type" value="2"> File</label>
							</div>
						</div>
                    </div>
					<div class="form-group{{ $errors->has('group_id') ? ' has-error' : '' }}">
                        <label for="group_id" class="col-sm-2 control-label">Group</label>
                        <div class="col-sm-8">
                                <select class="form-control select2" style="width: 100%;"
                                        id="group_id" name="group_id">
                                    <option value="">*** Select a Group ***</option>
                                    @foreach($groups as $group)
                                        <option value="{{ $group->id }}">{{ $group->group_name }}</option>
                                    @endforeach
                                </select>
                        </div>
                    </div>
					<div class="form-group folder-field{{ $errors->has('folder_id_gr') ? ' has-error' : '' }}">
                        <label for="folder_id_gr" class="col-sm-2 control-label">Folders</label>
                        <div class="col-sm-8">
                                <select class="form-control select2" style="width: 100%;"
                                        id="folder_id_gr" name="folder_id_gr">
                                    <option value="">*** Select a Folder ***</option>
                                    @foreach($folders as $folder)
                                        <option value="{{ $folder->id }}">{{ $folder->folder_name }}</option>
                                    @endforeach
                                </select>
                        </div>
                    </div>
					<div class="form-group file-field{{ $errors->has('file_id_gr') ? ' has-error' : '' }}">
                        <label for="file_id_gr" class="col-sm-2 control-label">Files</label>
                        <div class="col-sm-8">
                                <select class="form-control select2" style="width: 100%;"
                                        id="file_id_gr" name="file_id_gr">
                                    <option value="">*** Select a File ***</option>
                                    @foreach($files as $file)
                                        <option value="{{ $file->id }}">{{ $file->document_name }}</option>
                                    @endforeach
                                </select>
                        </div>
                    </div>
					<div class="form-group">
                        <label for="expiry_gr_date" class="col-sm-2 control-label">Expiry date</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="expiry_gr_date" name="expiry_gr_date"
                                   value="{{ old('expiry_gr_date') }}" placeholder="Select  Expiry Date  ...">
                        </div>
                    </div>
					<div class="form-group {{ $errors->has('admini_id') ? ' has-error' : '' }}">
                        <label for="admini_id" class="col-sm-2 control-label">Administrator</label>
                        <div class="col-sm-8">
                                <select class="form-control select2" style="width: 100%;"
                                        id="admini_id" name="admini_id">
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
                    <button type="button" id="add_group_access" class="btn btn-warning"><i class="fa fa-cloud-upload"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
           