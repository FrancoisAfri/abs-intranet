<div id="email-voucher-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="email-voucher-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Email Voucher</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                    <!--<hr class="hr-text" data-content="PROFILE DETAILS" style="margin-top: 0;">-->

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="{{ 'email' }}" class="col-sm-2 control-label">Email</label>

                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Recipient's Email Address"
                                   value="{{ old('email') }}">
                        </div>
                    </div>
                    <!--<div class="form-group has-feedback{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                        {!! app('captcha')->display() !!}
                    </div>-->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Cancel</button>
                    <button type="button" id="email-voucher-btn" class="btn btn-primary"><i class="fa fa-send"></i> Send</button>
                </div>
            </form>
        </div>
    </div>
</div>