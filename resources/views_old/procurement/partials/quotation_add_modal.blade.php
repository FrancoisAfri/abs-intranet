<div id="quotation-add-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-quote-form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add New Quotation</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group">
                        <label for="company_id" class="col-sm-2 control-label">Supplier</label>
                        <div class="col-sm-10">
                            <select id="company_id" name="company_id" class="form-control select2" style="width: 100%;" onchange="contactCompanyDDOnChange(this)">
								<option value="">*** Please Select a Supplier ***</option>
								@foreach($companies as $company)
									<option value="{{ $company->id }}">{{ $company->name }}</option>
								@endforeach
							</select>
                        </div>
                    </div>
					<div class="form-group">
                        <label for="contact_person_id" class="col-sm-2 control-label">Contact Person</label>
                        <div class="col-sm-10">
                            <select id="contact_person_id" name="contact_person_id" class="form-control select2" style="width: 100%;">
								<option value="">*** Please Select a Supplier First ***</option>
							</select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="comment" class="col-sm-2 control-label">Comment</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="comment" name="comment" placeholder="Enter Comment" ></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="total_cost" class="col-sm-2 control-label">Total Cost</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <input type="text" class="form-control" id="total_cost" name="total_cost" value="" placeholder="Enter Total Cost">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="attachment" class="col-sm-2 control-label">Attachment</label>

                        <div class="col-sm-10">
                            <input type="file" id="attachment" name="attachment" class="file file-loading" data-allowed-file-extensions='["doc", "pdf"]' data-show-upload="false">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Cancel</button>
                    <button type="button" id="save-quote" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>