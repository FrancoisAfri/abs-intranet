<div id="purchase-type-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="purchase-type-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Payment Option</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                    <p class="text-center">Please select if the client is making a once-off payment or will be paying monthly over a certain period of time.</p>

                    <div class="form-group{{ $errors->has('payment_option') ? ' has-error' : '' }}">
                        <label for="{{ 'payment_option' }}" class="col-sm-3 control-label">Payment Option</label>

                        <div class="col-sm-9">
                            <label class="radio-inline" style="padding-left: 0px;">
                                <input type="radio" id="rdo_once_off_payment" name="payment_option" value="1" checked> Once-Off Payment
                            </label>
                            <label class="radio-inline">
                                <input type="radio" id="rdo_recurring_payment" name="payment_option" value="2"> Recurring Payment
                            </label>
                        </div>
                    </div>

                    <div class="form-group recurring-payment-field{{ $errors->has('payment_term') ? ' has-error' : '' }}">
                        <label for="{{ 'payment_term' }}" class="col-sm-3 control-label">Payment Term</label>

                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="payment_term" name="payment_term" placeholder="Payment Term (in months)"
                                   value="{{ old('payment_term') }}">
                        </div>
                    </div>

                    <div class="form-group recurring-payment-field{{ $errors->has('first_payment_date') ? ' has-error' : '' }}">
                        <label for="{{ 'first_payment_date' }}" class="col-sm-3 control-label">First Payment Date</label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control datepicker" id="first_payment_date" name="first_payment_date"
                                   placeholder="Click to Select a Date..." value="{{ old('first_payment_date') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Cancel</button>
                    <button type="button" id="save-purchase-type" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>