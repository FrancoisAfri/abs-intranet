<div id="add-new-product_title-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-module-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

               <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add New Product</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                     
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Name</label>
                             <div class="col-sm-8">
                            <input type="text" class="form-control" id="name" name="name" value="" placeholder="Enter Product Name">
                        </div>
                    </div>
					
					<div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Description</label>
                             <div class="col-sm-8">
                            <input type="text" class="form-control" id="description" name="description" value="" placeholder="Enter Product Description">
                        </div>
                    </div>
					<div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Product Price</label>
                             <div class="col-sm-8">
				<input type="number" class="form-control" id="price" name="price" value="" placeholder="Enter Product Price" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Product Code</label>
                             <div class="col-sm-8">
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
                    <button type="button" id="add-product_title" class="btn btn-primary">Save</button>
                </div>
             </form>
            </div>
         </div>
        </div>
 
           