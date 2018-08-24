<div id="edit-product_title-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-job_title-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Product</h4>
                </div>
                <div class="modal-body">
                    <div id="job_title-invalid-input-alert"></div>
                    <div id="job_title-success-alert"></div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" name="name" value="" placeholder="Enter Name">
                        </div>
                    </div>
					<div class="form-group">
                        <label for="path" class="col-sm-3 control-label">Price</label>
                             <div class="col-sm-9">
								<input type="number" class="form-control" id="price" name="price" value="" placeholder="Enter Product Price" >
                        </div>
                    </div>
					<div class="form-group">
                        <label for="path" class="col-sm-3 control-label">Code</label>
                             <div class="col-sm-9">
                                <input type="text" class="form-control" id="product_code" name="product_code" value="" placeholder="Enter Product Code" >
                        </div>
                    </div>
					<div class="form-group">
                        <label for="fuel_type" class="col-sm-2 control-label">Stock Type</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-filter"></i>
                                </div>
                                <select id="stock_type" name="stock_type" class="form-control">
                                    <option value="0">*** Select product Type ***</option>
                                    <option value="1"> Stock Item</option>
                                    <option value="2"> Non Stock Item </option>
                                    <option value="3"> Both </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="update-product_title" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>