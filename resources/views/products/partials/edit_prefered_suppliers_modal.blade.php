<div id="edit-preferred-supplier-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
			<form class="form-horizontal" name="edit-preferred-supplier-form" enctype="multipart/form-data">
				{{ csrf_field() }}
				{{ method_field('PATCH') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Preferred Supplier Details</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
					<div class="box-body">
						<div class="form-group">
							<label for="order_no" class="col-sm-2 control-label">Order No</label>
							<div class="col-sm-8">
								<input type="number" class="form-control" id="order_no" name="order_no" value=""
									   placeholder="Enter Order No">
							</div>
						</div>
						<div class="form-group">
							<label for="supplier_id" class="col-sm-2 control-label">Supplier </label>
							<div class="col-sm-8">
								<select class="form-control select2" style="width: 100%;" id="supplier_id" name="supplier_id">
									<option value="0">*** Select a Supplier ***</option>
									@foreach($ContactCompany as $supplier)
									   <option value="{{ $supplier->id }}" >{{ $supplier->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="description" class="col-sm-2 control-label">Description</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="description" name="description"
									   value="" placeholder="Enter Description">
							</div>
						</div>
						<div class="form-group">
							<label for="inventory_code" class="col-sm-2 control-label">Inventory Code</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="inventory_code" name="inventory_code" value=""
									   placeholder="Enter Inventory Code">
							</div>
						</div>
					</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="update_preferred_supplier" class="btn btn-primary"><i class="fa fa-floppy-o"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>