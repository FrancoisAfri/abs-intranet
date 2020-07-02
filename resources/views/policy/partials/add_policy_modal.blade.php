<div id="add-policy-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-policy-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Create Policy</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
					<div class="form-group {{ $errors->has('category_id') ? ' has-error' : '' }}">
                        <label for="category_id" class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-8">
							<select class="form-control select2" style="width: 100%;"
									id="category_id" name="category_id">
								<option value="">*** Select a Category ***</option>
								@foreach($categories as $category)
									<option value="{{ $category->id }}" {{ ($category->id == $policyCat->id) ? ' selected' : '' }}>{{ $category->name }}</option>
								@endforeach
							</select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="name" name="name" value=""
                                   placeholder="Enter name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="description" name="description" value=""
                                   placeholder="Enter Description" required>
                        </div>
                    </div>
                    <hr class="hr-text" data-content="DOCUMENTS UPLOAD">
                    <div class="form-group supDoc-field{{ $errors->has('documents') ? ' has-error' : '' }}">
                        <label for="documents" class="col-sm-2 control-label">Attachment </label>
                        <div class="col-sm-8">
                            <input type="file" id="document" name="document"
                                   class="file file-loading" data-allowed-file-extensions='["pdf", "docx", "doc","txt"]'
                                   data-show-upload="false">
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="date" class="col-sm-2 control-label">Completion date</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="date" name="date"
                                   value="{{ old('date') }}" placeholder="Select  Inception date  ...">
                        </div>
                    </div>
                    <hr class="hr-text" data-content="Add Policy Users">
                    @foreach($division_levels as $division_level)
                        <div class="form-group manual-field{{ $errors->has('division_level_' . $division_level->level) ? ' has-error' : '' }}">
                            <label for="{{ 'division_level_' . $division_level->level }}"
                                   class="col-sm-2 control-label">{{ $division_level->name }}</label>
                            <div class="col-sm-8">
                                    <select id="{{ 'division_level_' . $division_level->level }}"
                                            name="{{ 'division_level_' . $division_level->level }}"
                                            class="form-control"
                                            onchange="divDDOnChange(this, null, 'add-policy-modal')">
                                    </select>
                            </div>
                        </div>
                    @endforeach
                    <div class="form-group {{ $errors->has('hr_person_id') ? ' has-error' : '' }}">
                        <label for="hr_person_id" class="col-sm-2 control-label">Employees</label>
                        <div class="col-sm-8">
                                <select class="form-control select2" multiple="multiple" style="width: 100%;"
                                        id="hr_person_id" name="hr_person_id[]">
                                    {{--<option value="">*** Select an Employee ***</option>--}}
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->surname }}</option>
                                    @endforeach
                                </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add-policy" class="btn btn-warning"><i class="fa fa-cloud-upload"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
           