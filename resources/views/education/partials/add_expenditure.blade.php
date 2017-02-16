<div id="add-expenditure-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-expenditure-form" enctype="multipart\form-data">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ $modal_title }}</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-exp-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group">
                        <label for="amount" class="col-sm-3 control-label">Amount</label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    R
                                </div>
                                <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount') }}" placeholder="Enter the Amount...">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="date_added" class="col-sm-3 control-label">Date</label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control datepicker" id="date_added" name="date_added" value="{{ old('date_added') }}" placeholder="Click to Select a Date...">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="supplier_id" class="col-sm-3 control-label">Supplier/Payee</label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-briefcase"></i>
                                </div>
								<input type="text" class="form-control" id="supplier_name" name="supplier_name" value="{{ old('supplier_name') }}" placeholder="Enter the Supplier...">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="supporting_doc" class="col-sm-3 control-label">Supporting Document</label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-file-pdf-o"></i>
                                </div>
                                <input type="file" id="supporting_doc" name="supporting_doc" class="file file-loading form-control" data-allowed-file-extensions='["pdf", "docx"]' data-show-upload="false">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="save-expenditure" class="btn btn-success">Save Expenditure</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>