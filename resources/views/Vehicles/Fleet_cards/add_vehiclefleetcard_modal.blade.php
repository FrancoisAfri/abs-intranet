<div id="add-fleetcard-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-fleetcard-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Fleet Card Type</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Card Type</label>
                        <div class="col-sm-8">

                            <select class="form-control select2" style="width: 100%;"
                                    id="card_type_id" name="card_type_id">
                                <option value="">*** Select a Card Type ***</option>
                                @foreach($fleetcardtype as $card)
                                    <option value="{{ $card->id }}">{{ $card->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Vehicle Fleet Number</label>
                        <div class="col-sm-8">

                            <select class="form-control select2" style="width: 100%;"
                                    id="fleet_number" name="fleet_number">
                                <option value="">*** Select a Vehicle ***</option>
                                @foreach($vehicle_detail as $Fleet)
                                    <option value="{{ $Fleet->id }}">{{ $Fleet->fleet_number }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Issued By</label>
                        <div class="col-sm-8">

                            <select class="form-control select2" style="width: 100%;"
                                    id="company_id" name="company_id">
                                <option value="">*** Select a Company ***</option>
                                @foreach($contactcompanies as $Company)
                                    <option value="{{ $Company->id }}">{{ $Company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label"> Card Holder</label>
                        <div class="col-sm-8">

                            <select class="form-control select2" style="width: 100%;"
                                    id="holder_id" name="holder_id">
                                <option value="">*** Select an Employee ***</option>
                                @foreach($hrDetails as $user)
                                    <option value="{{ $user->id }}">{{ $user->first_name . ' ' .  $user->surname}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Card Number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="card_number" name="card_number" value=""
                                   placeholder="Enter card_number" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">CSV Number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="cvs_number" name="cvs_number" value=""
                                   placeholder="Enter cvs_number" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label"> Date Issued</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="issued_date" name="issued_date" value=""
                                   placeholder="Enter Issued Date" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Expiry Date</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="expiry_date" name="expiry_date" value=""
                                   placeholder="Enter expiry date" required>
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                        <label for="status" class="col-sm-2 control-label"> Status
                        </label>

                        <div class="col-sm-10">
                            <label class="radio-inline" style="padding-left: 0px;"><input type="radio"
                                                                                          id="rdo_package"
                                                                                          name="status"
                                                                                          value="1"
                                                                                          checked>
                                Active
                            </label>
                            <label class="radio-inline"><input type="radio" id="rdo_product"
                                                               name="status" value="2"> Inactive
                            </label>

                        </div>
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" id="add-fleet-card" class="btn btn-warning"><i
                                    class="fa fa-cloud-upload"></i>
                            Save
                        </button>
                    </div>
            </form>
        </div>
    </div>
</div>
</div>
