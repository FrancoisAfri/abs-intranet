<div id="add-package-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-module-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

               <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add New Package</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
					 <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Name</label>
                             <div class="col-sm-8">
                            <input type="text" class="form-control" id="name" name="name" value="" placeholder="Enter name" required>
                        </div>
                           </div>
						    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label"> Description</label>
                             <div class="col-sm-8">
                            <input type="text" class="form-control" id="description" name="description" value="" placeholder="Enter Description" required>
                        </div>
                           </div>
						   
                        
					
					   <div class="form-group gen-pub-field{{ $errors->has('gen_public_id') ? ' has-error' : '' }}">
                                <label for="product_id" class="col-sm-2 control-label">Product Type</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <select class="form-control select2" style="width: 100%;" multiple="multiple" id="product_id" name="product_id[]">
                                            <option value="">*** Select a Product Type ***</option>
                                            @foreach($Product as $product)
                                            <option value="{{ $product->id }}">{{ $product->name . ' '. '_' . ' ' . $product->price }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
							
							

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Discount %</label>
                             <div class="col-sm-8">
                             <input type="number" class="form-control" id="discount" name="discount" value="" placeholder="Enter Discount" >
                        </div>
                     </div>
                             </div>  
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add-package" class="btn btn-primary"><i class="fa fa-cloud-upload"></i> Save</button>
                </div>
             </form>
            </div>
         </div>
            </div>
            </div>
           