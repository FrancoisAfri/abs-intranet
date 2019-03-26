<div id="add-new-holiday-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-holiday-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add New Holiday</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" name="name" value="" placeholder="Enter Description" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="holiday_date" class="col-sm-2 control-label">Day</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="holiday_date" name="holiday_date" placeholder="Enter Day">
                        </div>
                    </div>
					<div class="form-group">
                        <label for="Status" class="col-sm-2 control-label">Countries </label>
                        <div class="col-sm-10">
                            <select id="country_id" name="country_id" class="form-control">
                                <option value="0">*** Select a Country ***</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
					<div class="form-group">
							<label for="year" class="col-sm-2 control-label"> Once off Holiday</label>
							<div class="col-sm-10">
								<div class="input-group">
									<input type="checkbox" id="year" value="1" name="year">
								</div>
							</div>
					</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add-holiday" class="btn btn-primary">Add Module</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>