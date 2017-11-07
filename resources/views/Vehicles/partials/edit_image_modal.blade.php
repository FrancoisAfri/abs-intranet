<div id="edit-package-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-module-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

               <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Image</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                      <div class="form-group">
                        <label for="image" class="col-sm-3 control-label">Image</label>

                        <div class="col-sm-9">
                            <div class="product-img">
                                    <img src="{{ (!empty($vehicle_maintenance->image)) ? Storage::disk('local')->url("image/$vehicle_maintenance->image") : 'http://placehold.it/50x50' }}"  alt="Product Image" width="50" height="50">
                            </div>
                            
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('promotion_type') ? ' has-error' : '' }}">
                                <label for="property_type" class="col-xs-3 control-label"> Upload Type </label>

                                <div class="col-sm-9">
                                    <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_package" name="promotion_type" value="1" checked> Single  </label>
                                    <label class="radio-inline"><input type="radio" id="rdo_product" name="promotion_type" value="2">  Bulk </label>
                                    
                                </div>
                         </div>


					
                     <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="image" name="image" value="" placeholder="Enter Name" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-3 control-label">Description</label>
                             <div class="col-sm-9">
                            <input type="text" class="form-control" id="description" name="description" value="" placeholder="Enter Description" required>
                        </div>
                     </div>
					  
                  </div>  
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="edit_license" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
                </div>
            </form>
            </div>
         </div>
    </div>
        
           