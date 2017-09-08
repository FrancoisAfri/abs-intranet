<div id="capture-payment-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="capture-payment-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Capture Client Payment</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group">
                        <label for="date_added" class="col-sm-3 control-label">Payment Date</label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control datepicker" id="payment_date" name="payment_date" placeholder="  dd/mm/yyyy">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="amount_paid" class="col-sm-3 control-label">Amount</label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    R
                                </div>
                                <input type="number" class="form-control" id="amount_paid" name="amount_paid" placeholder="Enter the amount paid">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="supporting_document" class="col-sm-3 control-label">Supporting Document</label>

                        <div class="col-sm-9">
                            <input type="file" id="supporting_document" name="supporting_document" class="file file-loading"
                                   data-allowed-file-extensions='["jpg", "jpeg", "png", "pdf"]' data-show-upload="false">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="submit-payment" class="btn btn-success">Submit Payment</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>