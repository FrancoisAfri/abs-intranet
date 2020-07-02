<div id="add-warrantie-modal" class="modal modal-default fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-warrantie-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"> Add new vehicle Warrantie Details</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                   <div class="form-group{{ $errors->has('company_id') ? ' has-error' : '' }}">
                            <label for="{{ 'company_id' }}" class="col-sm-2 control-label">Service Provider</label>

                            <div class="col-sm-8">
                                <select id="company_id" name="company_id" class="form-control select2" style="width: 100%;" onchange="contactCompanyDDOnChange(this)">
                                    <option value="">*** Please Select a Company ***</option>
                                    <!-- <option value="0"></option> -->
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ ($company->id == old('company_id')) ? 'selected' : '' }}>{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('contact_person_id') ? ' has-error' : '' }}">
                            <label for="{{ 'contact_person_id' }}" class="col-sm-2 control-label">Contact Person</label>

                            <div class="col-sm-8">
                                <select id="contact_person_id" name="contact_person_id" class="form-control select2" style="width: 100%;">
                                    <option value="">*** Please Select a Company First ***</option>
                                </select>
                            </div>
                        </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Policy/Document Number</label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control" id="policy_no" name="policy_no" value=""
                                   placeholder="Enter Policy/Document Number" required>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="date" class="col-sm-2 control-label">Inception Date </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="inception_date" name="inception_date"
                                   value="{{ old('date') }}" placeholder="Select  Inception date  ...">
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="date" class="col-sm-2 control-label">Expiry Date </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="exp_date" name="exp_date"
                                   value="{{ old('date') }}" placeholder="Select Expiry date  ...">
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('warranty_period') ? ' has-error' : '' }}">
                        <label for="warranty_period" class="col-sm-2 control-label"> Warranty Period</label>
                        <div class="col-sm-8">
                            <label class="radio-inline" style="padding-left: 0px;"><input type="radio"
                                                                                          id="rdo_package"
                                                                                          name="warranty_period"
                                                                                          value="1" checked>6 Months
                            </label>

                            <label class="radio-inline"><input type="radio" id="rdo_product"
                                                               name="warranty_period" value="2"> 12 Months
                            </label>
                            <label class="radio-inline"><input type="radio" id="rdo_products"
                                                               name="warranty_period" value="3"> 24 Months
                            </label>
                            <label class="radio-inline"><input type="radio" id="rdo_products"
                                                               name="warranty_period" value="4"> 36 Months
                            </label>
                            <label class="radio-inline"><input type="radio" id="rdo_products"
                                                               name="warranty_period" value="5"> 48 Months
                            </label>
                            <label class="radio-inline"><input type="radio" id="rdo_products"
                                                               name="warranty_period" value="6"> 60 Months
                            </label>

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Maximum Kilometres Covered</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="kilometers" name="kilometers" value=""
                                   placeholder="Enter Maximum Kilometres Covered" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Type</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="type" name="type" value=""
                                   placeholder="Enter Type" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Warranty Amount </label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="warranty_amount" name="warranty_amount" value=""
                                   placeholder="Enter Warranty Amount" required>

                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="description" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-8">

                            <textarea class="form-control" id="description" name="description"
                                      placeholder="Enter description..." rows="3">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="notes" class="col-sm-2 control-label">Notes</label>
                        <div class="col-sm-8">

                            <textarea class="form-control" id="notes" name="notes"
                                      placeholder="Enter notes..." rows="3">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <hr class="hr-text" data-content="DOCUMENTS UPLOAD">

                    <div class="form-group supDoc-field{{ $errors->has('documents') ? ' has-error' : '' }}">
                        <label for="documents" class="col-sm-2 control-label">Attachment </label>
                        <div class="col-sm-8">

                            <input type="file" id="documents" name="documents"
                                   class="file file-loading" data-allowed-file-extensions='["pdf", "docx", "doc"]'
                                   data-show-upload="false">
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Document Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="name" name="name" value=""
                                   placeholder="Enter Name" required>
                        </div>
                    </div>


                    <input type="hidden" id="valueID" name="valueID"
                           value="{{ !empty($maintenance->id) ? $maintenance->id : ''}}">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add_warrantie" class="btn btn-warning"><i class="fa fa-cloud-upload"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

           