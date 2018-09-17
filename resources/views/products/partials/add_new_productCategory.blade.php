<div id="add-category-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-module-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

               <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add New category </h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                     <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" name="name" value="" placeholder="Enter Name" required>
                        </div>
                    </div><br><br>
                    <div class="form-group">
                        <label for="path" class="col-sm-3 control-label">Description</label>
                             <div class="col-sm-9">
                            <input type="text" class="form-control" id="description" name="description" value="" placeholder="Enter Description" required>
                        </div>
                    </div>
					<div class="form-group">
                        <label for="stock_type" class="col-sm-3 control-label">Stock Type</label>
                        <div class="col-sm-9">
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
                    <button type="button" id="save_category" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
                </div>
            </form>

            </div>
         </div>
            </div>
            </div>
           