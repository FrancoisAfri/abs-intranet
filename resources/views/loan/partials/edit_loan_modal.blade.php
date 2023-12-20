<div id="edit-loan-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-loan-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Application </h4>
                </div>
				<div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                        <label for="meeting_type" class="col-sm-2 control-label">Application Type</label>

                        <div class="col-sm-10">

                            <label class="radio-inline"><input type="radio" id="rdo_user" name="type" value="1"
                                                               > Advance </label>
                            <label class="radio-inline"><input type="radio" id="rdo_store" name="type"
                                                               value="2"> Loan </label>
                        </div>
                    </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group">
                        <label for="amount" class="col-sm-2 control-label">Amount</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="amount" name="amount" value=""
                                   placeholder="Enter Amount" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reason" class="col-sm-2 control-label">Comment</label>
                        <div class="col-sm-8">
						<textarea type="text" class="form-control" id="reason" name="reason" value=""
                                   placeholder="Enter Comment" required></textarea>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('repayment_month') ? ' has-error' : '' }}">
                        <label for="repayment_month" class="col-sm-2 control-label">Repayment Month</label>
                        <div class="col-sm-8">
                                <input type="text" class="form-control" id="reason" name="reason" value=""
                                   placeholder="Enter Reason" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="edit_loan" class="btn btn-primary"><i class="fa fa-floppy-o"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
        
           