<div id="add-jobparts-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-jobparts-form">
                {{ csrf_field() }}
               
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Job Card Parts </h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    @if (count($errors) > 0)
						<div class="alert alert-danger alert-dismissible fade in">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<h4><i class="icon fa fa-ban"></i> Invalid Input Data!</h4>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
                    @endif
                    <div class="form-group{{ $errors->has('jobcard_parts_id') ? ' has-error' : '' }}">
                            <label for="{{ 'jobcard_parts_id' }}" class="col-sm-2 control-label">Parts Catergory </label>

                            <div class="col-sm-8">
                                <select id="jobcard_parts_id" name="jobcard_parts_id" class="form-control select2" style="width: 100%;" onchange="categorypartDDOnChange(this)">
                                    <option value="">*** Please Select a Category  ***</option>
                                    <option value="0"></option>
                                    @foreach($cardparts as $parts)
                                        <option value="{{ $parts->id }}" >{{ $parts->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                    </div>
					<div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
						<label for="{{ 'category_id' }}" class="col-sm-2 control-label">Job Card Parts</label>

						<div class="col-sm-8">
							<select id="category_id" name="category_id" class="form-control select2" style="width: 100%;">
								<option value="">*** Please Select a Category  First ***</option>
							</select>
						</div>
					</div>
                    <div class="form-group">
                        <label for="no_of_parts_used" class="col-sm-2 control-label">Number of Parts</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="no_of_parts_used" name="no_of_parts_used" value=""
                                   placeholder="" required>
                        </div>
                    </div>
                    <input type="hidden" id="jobcard_card_id" name="jobcard_card_id"
                           value="{{ !empty($jobcardparts->id) ? $jobcardparts->id : ''}}">
                 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add_jobparts" class="btn btn-warning"><i class="fa fa-cloud-upload"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
           