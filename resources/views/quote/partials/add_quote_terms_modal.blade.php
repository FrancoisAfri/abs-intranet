<div id="add-new-terms-modal" class="modal modal-default fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-new-profile-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add New Term & Condition</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
					 <div class="box box-info">
						<div class="box-header">
						  <h3 class="box-title">CK Editor
							<small>Advanced and full of features</small>
						  </h3>
						  <!-- tools box -->
						  <div class="pull-right box-tools">
							<button type="button" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse">
							  <i class="fa fa-minus"></i></button>
							<button type="button" class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove">
							  <i class="fa fa-times"></i></button>
						  </div>
						  <!-- /. tools -->
						</div>
						<!-- /.box-header -->
						<div class="box-body pad">
						  <form>
								<textarea id="editor1" name="editor1" rows="10" cols="80">
														This is my textarea to be replaced with CKEditor.
								</textarea>
						  </form>
						</div>
					  </div>
                    
                
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Cancel</button>
                    <button type="button" id="save-quote-profile" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>