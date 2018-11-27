<div id="edit-stock-info-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
			<form class="form-horizontal" name="edit-stock-info-form" enctype="multipart/form-data">
				{{ csrf_field() }}
				{{ method_field('PATCH') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Stock Info</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
					<div class="form-group hours-field">
						<label for="allow_vat" class="col-sm-2 control-label">Allow Vat</label>
						<div class="col-sm-8">
							<select class="form-control select2" style="width: 100%;" id="allow_vat" name="allow_vat">
							<option value="0">*** Allow Vat ***</option>
							<option value="1">Yes</option>
							<option value="2">No</option>
							
						</select>
						
						</div>
					</div>
					<div class="form-group hours-field">
						<label for="mass_net" class="col-sm-2 control-label">Mass Net ({{ !empty($stockSettings->unit_of_measurement) ? $stockSettings->unit_of_measurement : '' }})</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="mass_net" name="mass_net" value=""
								   placeholder="Enter Mass Net">
						</div>
					</div>
					<div class="form-group hours-field">
						<label for="minimum_level" class="col-sm-2 control-label">Minimum Level</label>
						<div class="col-sm-8">
							<input type="number" class="form-control" id="minimum_level" name="minimum_level" value=""
								   placeholder="Enter Minimum Level">
						</div>
					</div>
					<div class="form-group hours-field">
						<label for="maximum_level" class="col-sm-2 control-label">Maximum Level</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="maximum_level" name="maximum_level" value=""
								   placeholder="Enter Maximum Level">
						</div>
					</div>
					<div class="form-group hours-field">
						<label for="bar_code" class="col-sm-2 control-label">Bar Code</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="bar_code" name="bar_code" value=""
								   placeholder="Enter Bar Code">
						</div>
					</div>
					<div class="form-group hours-field">
						<label for="unit" class="col-sm-2 control-label">Unit</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="unit" name="unit" value=""
								   placeholder="Enter Unit">
						</div>
					</div>
					<div class="form-group hours-field">
						<label for="commodity_code" class="col-sm-2 control-label">Commodity Code</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="commodity_code" name="commodity_code" value=""
								   placeholder="Enter Commodity Code">
						</div>
					</div>
					<div class="form-group">
						<label for="picture" class="col-sm-2 control-label">Picture</label>
						<div class="col-sm-8">
							<input type="file" id="picture" name="picture" class="file file-loading"
								   data-allowed-file-extensions='["jpg", "jpeg", "png"]' data-show-upload="false">
								   <input src="" type="image" id="stock_image" width="250"
									 height="200" class="img-responsive img-thumbnail">
						</div>
					</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="update_stock_info" class="btn btn-primary"><i class="fa fa-floppy-o"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>