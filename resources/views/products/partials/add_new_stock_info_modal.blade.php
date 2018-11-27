<div id="add-stock-info-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" name="add-new-stock-info-form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Stock Details </h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
					<div class="form-group hours-field">
						<label for="allow_vat" class="col-sm-2 control-label">Allow Vat</label>
						<div class="col-sm-8">
							<label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_allow_vat_yes" name="allow_vat" value="1" checked> Yes</label>
							<label class="radio-inline"><input type="radio" id="rdo_allow_vat_no" name="allow_vat" value="2"> No</label> 
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
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
					<button type="button" id="add_stock_info" class="btn btn-warning"><i
								class="fa fa-cloud-upload"></i> Save
					</button>
				</div>
            </form>
        </div>
    </div>
</div>