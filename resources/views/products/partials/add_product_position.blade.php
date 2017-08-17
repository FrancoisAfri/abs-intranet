<div id="add-new-product_package_title-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-module-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

               <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add New Products Type</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                   
							
						<div class="form-group {{ $errors->has('product_id') ? ' has-error' : '' }}">
                                <label for="leave_types_id" class="col-sm-2 control-label">Add product </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-black-tie"></i>
                                        </div>
                                        <select id="product_id" name="product_id" class="form-control">
                                            <option value="leavetyes">*** Select a product  ***</option> 
                                                @foreach($product_packages as $Desk)
                                                    <option value="{{ $Desk->id }}">{{ $Desk->name }}</option>
                                                @endforeach
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
 
           