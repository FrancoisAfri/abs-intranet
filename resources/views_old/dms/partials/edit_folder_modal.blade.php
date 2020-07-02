<div id="edit-folder-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-folder-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Folder Details</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
					<div class="form-group">
                        <label for="folder_name" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="folder_name" name="folder_name" value=""
                                   placeholder="Enter Name">
                        </div>
                    </div>
					<div class="form-group {{ $errors->has('visibility') ? ' has-error' : '' }}">
                        <label for="visibility" class="col-sm-2 control-label">Visibility</label>
                        <div class="col-sm-8">
							<select class="form-control select2" style="width: 100%;"
									id="visibility" name="visibility">
								<option value="">*** Select a Visibility ***</option>
								<option value="1">Private</option>
								<option value="2">All Employees</option>
							</select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="size" class="col-sm-2 control-label">Maximum Size</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="size" name="size" value=""
                                   placeholder="Enter Maximum Size">MB
                        </div>
                    </div>
					<div class="form-group {{ $errors->has('responsable_person') ? ' has-error' : '' }}">
                        <label for="responsable_person" class="col-sm-2 control-label">Employees</label>
                        <div class="col-sm-8">
                                <select class="form-control select2" style="width: 100%;"
                                        id="responsable_person" name="responsable_person">
                                    <option value="">*** Select an Employee ***</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->surname }}</option>
                                    @endforeach
                                </select>
                        </div>
                    </div>
					@foreach($division_levels as $division_level)
                        <div class="form-group manual-field{{ $errors->has('division_level_' . $division_level->level) ? ' has-error' : '' }}">
                            <label for="{{ 'division_level_' . $division_level->level }}"
                                   class="col-sm-2 control-label">{{ $division_level->name }}</label>
                            <div class="col-sm-8">
								<select id="{{ 'division_level_' . $division_level->level }}"
										name="{{ 'division_level_' . $division_level->level }}"
										class="form-control"
										onchange="divDDOnChange(this, null, 'edit-folder-modal')">
								</select>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="edit_folder" class="btn btn-warning"><i class="fa fa-cloud-upload"></i>
                        Save
                    </button>
                </div>
            </form>☺
        </div>
    </div>
</div>
</div>
           