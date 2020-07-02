<div id="add-new-profile-modal" class="modal modal-default fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-new-profile-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add New Quotation Profile</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                    <hr class="hr-text" data-content="PROFILE DETAILS" style="margin-top: 0;">

                    <div class="form-group{{ $errors->has('division_id') ? ' has-error' : '' }}">
                        <label for="{{ 'division_id' }}" class="col-sm-2 control-label">{{ $highestLvl->name }}</label>

                        <div class="col-sm-10">
                            <select id="division_id" name="division_id" class="form-control select2" style="width: 100%;">
                                <option value="">*** Please Select a {{ $highestLvl->name }} ***</option>
								@if($highestLvlWithAllDivs->divisionLevelGroup)
                                    @foreach($highestLvlWithAllDivs->divisionLevelGroup as $division)
                                        <option value="{{ $division->id }}" {{ ($division->id == old('division_id')) ? 'selected' : '' }}>{{ $division->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

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

                    <hr class="hr-text" data-content="CONTACT DETAILS" style="margin-top: 0;">

                    <div class="form-group{{ $errors->has('phys_address') ? ' has-error' : '' }}">
                        <label for="{{ 'phys_address' }}" class="col-sm-2 control-label">Physical Address</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="phys_address" name="phys_address" placeholder="Physical Address"
                                   value="{{ old('phys_address') }}">
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('phys_city') ? ' has-error' : '' }}">
                        <label for="{{ 'phys_city' }}" class="col-sm-2 control-label">City</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="phys_city" name="phys_city" placeholder="City"
                                   value="{{ old('phys_city') }}">
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('phys_postal_code') ? ' has-error' : '' }}">
                        <label for="{{ 'phys_postal_code' }}" class="col-sm-2 control-label">Postal Code</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="phys_postal_code" name="phys_postal_code" placeholder="Postal Code"
                                   value="{{ old('phys_postal_code') }}">
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('phone_number') ? ' has-error' : '' }}">
                        <label for="{{ 'phone_number' }}" class="col-sm-2 control-label">Phone Number</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Phone Number"
                                   value="{{ old('phone_number') }}">
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="{{ 'email' }}" class="col-sm-2 control-label">Email</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="email" name="email" placeholder="Email"
                                   value="{{ old('email') }}">
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

                    <div class="form-group{{ $errors->has('validity_period') ? ' has-error' : '' }}">
                        <label for="{{ 'validity_period' }}" class="col-sm-2 control-label">Quote Validity Period</label>

                        <div class="col-sm-10">
                            <select id="validity_period" name="validity_period" class="form-control select2" style="width: 100%;">
                                <option value="">*** Please Select a Validity Period ***</option>
                                @foreach($validityPeriods as $validityPeriod)
                                    <option value="{{ $validityPeriod }}" {{ ($validityPeriod == old('validity_period')) ? 'selected' : '' }}>{{ $validityPeriod . ' days' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> 
					<div class="form-group{{ $errors->has('authorisation_required') ? ' has-error' : '' }}">
                        <label for="{{ 'authorisation_required' }}" class="col-sm-2 control-label">Authorisation Required</label>

                        <div class="col-sm-10">
                            <select id="authorisation_required" name="authorisation_required" class="form-control select2" style="width: 100%;">
                                <option value="1">No</option>
                                <option value="2">Yes</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="letter_head" class="col-sm-2 control-label">Letter Head</label>

                        <div class="col-sm-10">
                            <!--
                            @if(!empty('var'))
                                <div style="margin-bottom: 10px;">
                                    <img src="{{ 'var' }}" class="img-responsive img-thumbnail" width="200" height="200">
                                </div>
                            @endif
                            -->
                            <input type="file" id="letter_head" name="letter_head" class="file file-loading" data-allowed-file-extensions='["jpg", "jpeg", "png"]' data-show-upload="false">
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