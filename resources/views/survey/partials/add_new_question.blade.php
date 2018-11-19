<div id="add-new-question-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add_new_leavetype-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add New Question</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
					@foreach($division_levels as $division_level)
						<div class="form-group manual-field{{ $errors->has('division_level_' . $division_level->level) ? ' has-error' : '' }}">
							<label for="{{ 'division_level_' . $division_level->level }}"
								   class="col-sm-2 control-label">{{ $division_level->name }}</label>

							<div class="col-sm-10">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-black-tie"></i>
									</div>
									<select id="{{ 'division_level_' . $division_level->level }}"
											name="{{ 'division_level_' . $division_level->level }}"
											class="form-control"
											onchange="divDDOnChange(this, null, 'add-new-question-modal')">
									</select>
								</div>
							</div>
						</div>
                    @endforeach
					<div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10">
                            <div class="input-group">
							<div class="input-group-addon">
								<i class="fa fa-black-tie"></i>
							</div>
							<textarea rows="4" cols="50" class="form-control" id="description" name="description" placeholder="Enter Description"></textarea>
							</div>
                        </div>
                    </div>				
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add_questions" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>