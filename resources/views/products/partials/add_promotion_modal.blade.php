<div id="add-promotion-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add_new_site-form" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                     <h4 class="modal-title">Add New Promotion </h4>
                </div>
                <div class="modal-body">
                    <div id="leave-invalid-input-alert"></div>
                    <div id="success-alert"></div>
                     <div class="form-group{{ $errors->has('promotion_type') ? ' has-error' : '' }}">
                                <label for="Leave_type" class="col-sm-2 control-label"> Action</label>

                                <div class="col-sm-9">
                                    <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_package" name="promotion_type" value="1" checked> Package  </label>
                                    <label class="radio-inline"><input type="radio" id="rdo_product" name="promotion_type" value="2">  Product  </label>

                                </div>
                            </div>  
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Name</label>
                             <div class="col-sm-8">
                            <input type="text" class="form-control"  id="name" name="name" value="" placeholder="Enter name" required>
                        </div>
						
                   </div>
						
					<div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Description</label>
                             <div class="col-sm-8">
                            <input type="text" class="form-control"  id="description" name="description" value="" placeholder="Enter name" required>
                        </div>
                   </div>	
                      <div class="form-group temp-field">
                                <label for="product_id" class="col-sm-2 control-label">Product </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <select class="form-control select2" style="width: 100%;" id="product" name="product_id">
                                            <option value="">*** Select a Product  ***</option>
                                            @foreach($Product as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
							   <div class="form-group site-field">
                                <label for="package_id" class="col-sm-2 control-label">Package </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <select class="form-control select2" style="width: 100%;" id="package" name="package_id">
                                            <option value="">*** Select a Package  ***</option>
                                            @foreach($package as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div> 
				 <div class="form-group">
                        <label for="start_date" class="col-sm-2 control-label">Start Date</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control datepicker" id="start_date" name="start_date" value="{{ old('start_date') }}" placeholder="Click to Select a Date...">
                            </div>
                        </div>
                    </div>

                     <div class="form-group">
                        <label for="end_date" class="col-sm-2 control-label">End Date</label>

                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control datepicker" id="end_date" name="end_date" value="{{ old('end_date') }}" placeholder="Click to Select a Date...">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Discount</label>
                             <div class="col-sm-8">
                             <input type="number" class="form-control" id="discount" name="discount" value="" placeholder="Enter Discount" >
                        </div>
                    </div>
					
                    <!--<div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Price</label>
                             <div class="col-sm-8">
                             <input type="number" class="form-control" id="price" name="price" value="" placeholder="Enter Price" >
                        </div>
                    </div> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add_promotion" class="btn btn-primary">Add Promotion</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>