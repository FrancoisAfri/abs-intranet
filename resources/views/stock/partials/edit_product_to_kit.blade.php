<div id="edit-product-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-product-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

				<div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Product</h4>
                </div>
                <div class="form-group">
						<label for="number_required" class="col-sm-2 control-label">Number Required</label>
						<div class="col-sm-8">
							<input type="number" class="form-control" id="number_required" name="number_required" value="" placeholder="Enter Number Required">
						</div>
					</div> 
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="edit_pro" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>         