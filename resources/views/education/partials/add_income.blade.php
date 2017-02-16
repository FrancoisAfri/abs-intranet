<div id="add-income-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-income-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ $modal_title }}</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group">
                        <label for="amount" class="col-sm-3 control-label">Amount</label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    R
                                </div>
                                <input type="number" class="form-control" id="inc_amount" name="inc_amount" value="{{ old('inc_amount') }}" placeholder="Enter the Amount...">
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
                                <input type="text" class="form-control datepicker" id="inc_date_added" name="inc_date_added" value="{{ old('inc_date_added') }}" placeholder="Click to Select a Date...">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="supplier_id" class="col-sm-3 control-label">Supplier/Payer</label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-briefcase"></i>
                                </div>
                                <input type="text" class="form-control" id="inc_supplier_name" name="inc_supplier_name" value="{{ old('inc_supplier_name') }}" placeholder="Enter the Supplier...">
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
                                <!-- @if(!empty($avatar))
                                        <div style="margin-bottom: 10px;">
                                            <img src="{{ $avatar }}" class="img-responsive img-thumbnail" width="200" height="200">
                                        </div>
                                    @endif -->
                                <input type="file" id="inc_supporting_doc" name="inc_supporting_doc" class="file file-loading form-control" data-allowed-file-extensions='["pdf", "docx"]' data-show-upload="false">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="save_income" class="btn btn-success">Save Income</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
          <br><button type="button" class="btn btn-primary pull-left" id="reject_button" name="command"
                                    value="Reject" data-toggle="modal" data-target="#rejection-reason-modal">Add Learners
                            </button>
                             <button type="submit" class="btn btn-primary pull-right" id="approve_button" name="command"
                                    value="Approve">Add Educators
                            </button>
                    </div>
</div>
