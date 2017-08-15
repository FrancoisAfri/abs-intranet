<div id="edit-quotes-term-modal" class="modal modal-default fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-term-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Quotation Term</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                    <hr class="hr-text" data-content="PROFILE DETAILS" style="margin-top: 0;">

                    

                    <div class="form-group{{ $errors->has('registration_number') ? ' has-error' : '' }}">
                        <label for="{{ 'registration_number' }}" class="col-sm-2 control-label">Registration Number</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="registration_number" name="registration_number" placeholder="Registration Number"
                                   value="{{ old('registration_number') }}">
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('vat_number') ? ' has-error' : '' }}">
                        <label for="{{ 'vat_number' }}" class="col-sm-2 control-label">VAT Number</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="vat_number" name="vat_number" placeholder="VAT Number"
                                   value="{{ old('vat_number') }}">
                        </div>
                    </div>

                    <hr class="hr-text" data-content="BANKING DETAILS">

                    <div class="form-group{{ $errors->has('bank_name') ? ' has-error' : '' }}">
                        <label for="{{ 'bank_name' }}" class="col-sm-2 control-label">Bank</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="Bank Name" value="{{ old('bank_name') }}">
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('bank_branch_code') ? ' has-error' : '' }}">
                        <label for="{{ 'bank_branch_code' }}" class="col-sm-2 control-label">Branch Code</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="bank_branch_code" name="bank_branch_code" placeholder="Branch Code"
                                   value="{{ old('bank_branch_code') }}">
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('bank_account_name') ? ' has-error' : '' }}">
                        <label for="{{ 'bank_account_name' }}" class="col-sm-2 control-label">Account Name</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="bank_account_name" name="bank_account_name" placeholder="Name Of The Account Holder"
                                   value="{{ old('bank_account_name') }}">
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('bank_account_number') ? ' has-error' : '' }}">
                        <label for="{{ 'bank_account_number' }}" class="col-sm-2 control-label">Account Number</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="bank_account_number" name="bank_account_number" placeholder="Account Number"
                                   value="{{ old('bank_account_number') }}">
                        </div>
                    </div>

                    <hr class="hr-text" data-content="QUOTE SETTINGS">

                    

                    <div class="form-group">
                        <label for="letter_head" class="col-sm-2 control-label">Letter Head</label>

                        <div class="col-sm-10">
                            <div id="letterhead-img" style="margin-bottom: 10px;"></div>
                            <input type="file" id="letter_head" name="letter_head" class="file file-loading" data-allowed-file-extensions='["jpg", "jpeg", "png"]' data-show-upload="false">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Cancel</button>
                    <button type="button" id="update-quote-term" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>