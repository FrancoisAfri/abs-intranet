<div id="add-product-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-product-form">
                {{ csrf_field() }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Product</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
					<div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
                        <label for="{{ 'category_id' }}" class="col-sm-2 control-label">Category </label>
                        <div class="col-sm-8">
                            <select id="category_id" name="category_id" class="form-control select2" style="width: 100%;"
                                    onchange="productcategoryDDOnChange(this)">
                                <option value="">*** Please Select a Category ***</option>
                                <option value="0"></option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('product_id') ? ' has-error' : '' }}">
                        <label for="{{ 'product_id' }}" class="col-sm-2 control-label">Product </label>

                        <div class="col-sm-8">
                            <select id="product_id" name="product_id" class="form-control select2"
                                    style="width: 100%;">
                                <option value="">*** Please Select a Category First ***</option>
                            </select>
                        </div>
                    </div>
					<div class="form-group">
						<label for="number_required" class="col-sm-2 control-label">Number Required</label>
						<div class="col-sm-8">
							<input type="number" class="form-control" id="number_required" name="number_required" value="" placeholder="Enter Number Required">
						</div>
					</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add-product" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>