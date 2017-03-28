<div id="add-new-kpi-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-kpi-form">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add New KPI</h4>
                </div>
                <div class="modal-body">
                    <div id="kpi-invalid-input-alert"></div>
                    <div id="kpi-success-alert"></div>
					<div class="form-group">
						<label for="category_id" class="col-sm-3 control-label">Category</label>
						<div class="col-sm-9">
							<div class="input-group">
								<select id="category_id" name="category_id" class="form-control" required>
                                    <option value="0">*** Select a Category ***</option>
                                    @foreach($kpaCategories as $kpaCategory)
                                        <option value="{{ $kpaCategory->id }}">{{ $kpaCategory->name }}</option>
                                    @endforeach
								</select>
							</div>
						</div>
                    </div>
					<div class="form-group">
						<label for="kpa_id" class="col-sm-3 control-label">KPA</label>
						<div class="col-sm-9">
							<div class="input-group">
								<select id="kpa_id" name="kpa_id" class="form-control" required>
								<option value="0">*** Select a KPA ***</option>
								@foreach($kpas as $kpa)
                                        <option value="{{ $kpa->id }}">{{ $kpa->name }}</option>
                                @endforeach
								</select>
							</div>
						</div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Indicator</label>
                        <div class="col-sm-9">
                            <div class="input-group">
								<textarea class="form-control" rows="3" cols="70" id="indicator" name="indicator" placeholder="Enter Indicator" required></textarea>
                            </div>
                        </div>
                    </div>
					<div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Measurement</label>
                        <div class="col-sm-9">
                            <div class="input-group">
								<textarea class="form-control" rows="3" cols="70" id="measurement" name="measurement" placeholder="Enter Measurement"></textarea>
                            </div>
                        </div>
                    </div>
					<div class="form-group">
                        <label for="source_of_evidence" class="col-sm-3 control-label">Source Of Evidence</label>
                        <div class="col-sm-9">
                            <div class="input-group">
								<textarea class="form-control" rows="3" cols="70" id="source_of_evidence" name="source_of_evidence" placeholder="Enter Source Of Evidence"></textarea>
                            </div>
                        </div>
                    </div>
					<div class="form-group">
                        <label for="weight" class="col-sm-3 control-label">Weight</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="number" class="form-control" id="weight" name="weight" value="" placeholder="Enter Weight" required>
                            </div>
                        </div>
                    </div>
					<div class="form-group">
						<label for="kpi_type" class="col-sm-3 control-label">KPI Type</label>
						<div class="col-sm-9">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-black-tie"></i>
								</div>
								<select id="kpi_type" name="kpi_type" class="form-control" required>
									<option value="0">*** Select a Type ***</option>
									<option value="1">Range</option>
									<option value="2">Number</option>
									<option value="3">From 1 To 10</option>
								</select>
							</div>
						</div>
                    </div>
					<div class="form-group">
						<label for="is_upload" class="col-sm-3 control-label">Is Upload</label>
						<div class="col-sm-9">
							<div class="input-group">
								<span class="input-group-addon">
								  <i class="fa fa-black-tie"></i>
								</span>
								<select id="is_upload" name="is_upload" class="form-control" onchange="hideFields();" required>
									<option value="1">Yes</option>
									<option value="2">No</option>
								</select>
							</div>
						</div>
                    </div>
					<div class="form-group" id="upload_type_div">
						<label for="is_upload" class="col-sm-3 control-label">Upload Type</label>
						<div class="col-sm-9">
							<div class="input-group">
								<span class="input-group-addon">
								  <i class="fa fa-black-tie"></i>
								</span>
								<select id="upload_type" name="upload_type" class="form-control">
									<option value="1">General</option>
									<option value="2">Clock In</option>
									<option value="3">Query Report </option>
								</select>
							</div>
						</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add-kpi" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>