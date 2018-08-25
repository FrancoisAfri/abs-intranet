<div id="add-preferred-supplier-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" name="add-new-pre-supploer-form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Preferred Supplier </h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="box-body">
						<div class="form-group hours-field">
							<label for="location" class="col-sm-2 control-label">Order No</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="location" name="location" value=""
									   placeholder="Enter Order No">
							</div>
						</div>
						<div class="form-group hours-field">
							<label for="allow_vat" class="col-sm-2 control-label">Supplier</label>
							<div class="col-sm-8">
								<label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_allow_vat_yes" name="allow_vat" value="1" checked> Yes</label>
								<label class="radio-inline"><input type="radio" id="rdo_allow_vat_no" name="allow_vat" value="0"> No</label> 
							</div>
						</div>
						<div class="form-group">
							<label for="description" class="col-sm-2 control-label">Description</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="description" name="description"
									   value="" placeholder="Enter Description">
							</div>
						</div>
						<div class="form-group hours-field">
							<label for="mass_net" class="col-sm-2 control-label">Inventory Code</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="mass_net" name="mass_net" value=""
									   placeholder="Enter Mass Net">
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
						<button type="button" id="add_pre_supplier" class="btn btn-warning"><i
									class="fa fa-cloud-upload"></i> Save
						</button>
					</div>
				</div>
            </form>
        </div>
    </div>
</div>