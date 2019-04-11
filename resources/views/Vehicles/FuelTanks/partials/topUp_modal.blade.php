<div id="add-topUp-modal" class="modal modal-default fade">
    {{--  <div class="modal-dialog">  --}}
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-topUp-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Top Up For {{ !empty($tank->tank_name) ? $tank->tank_name : ''}}</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="box-body" id="view_users">

                        <div class="form-group ">
                            <label for="supplier_id" class="col-sm-2 control-label">Supplier</label>
                            <div class="col-sm-8">
                                <select class="form-control select2" style="width: 100%;" id="supplier_id"
                                        name="supplier_id">
                                    <option value="0">*** Select a Supplier ***</option>
                                    @foreach($ContactCompany as $Supplier)
                                        <option value="{{ $Supplier->id }}">{{ !empty($Supplier->name) ? $Supplier->name : ''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="document_no" class="col-sm-2 control-label">Document Number </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="document_no" name="document_no" value=""
                                       placeholder="Enter Document Number" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="document_date" class="col-sm-2 control-label">Document Date </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="document_date" name="document_date" value=""
                                       placeholder="Enter document date " required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="topup_date" class="col-sm-2 control-label">Top Up Date </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="topup_date" name="topup_date" value=""
                                       placeholder="Enter top up date " required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="type" class="col-sm-2 control-label">Type</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="type" name="type" value="Incoming"
                                       placeholder="Enter tank capacity" readonly="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="reading_before_filling" class="col-sm-2 control-label">Reading before
                                filling</label>
                            <div class="col-sm-8">
							{{ (!empty($current))  ?  $current  : 0 }}
                                <input type="hidden" name="reading_before_filling" value="{{ (!empty($current))  ?  $current  : 0 }}">
                            </div>
                        </div>
                        <!--<div class="form-group">
                            <label for="reading_after_filling" class="col-sm-2 control-label">Reading After
                                filling</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="reading_after_filling"
                                       name="reading_after_filling" value=""
                                       placeholder="Enter reading after filling" required>
                            </div>
                        </div>-->

                        <div class="form-group">
                            <label for="litres_new" class="col-sm-2 control-label">Litres</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="litres_new" name="litres_new" value="$iLitres"
                                       min="0" step="0.001"
                                       placeholder="Enter litres_new " required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cost_per_litre" class="col-sm-2 control-label">Cost per Litre</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="cost_per_litre" name="cost_per_litre"
                                       value="$iLitreCost" min="0" step="0.01"
                                       placeholder="Enter cost per litre " required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="total_cost" class="col-sm-2 control-label">Total Cost</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="total_cost" name="total_cost" value=""
                                       readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="description" name="description" value=""
                                       placeholder="Enter description " required>
                            </div>
                        </div>
                        <div class="form-group safe-field">
                            <label for="received_by" class="col-sm-2 control-label">Received By</label>
                            <div class="col-sm-8">
                                <select class="form-control select2" style="width: 100%;" id="received_by"
                                        name="received_by">
                                    <option value="0">*** Select a User ***</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}"> {{ !empty($employee->first_name . ' ' . $employee->surname) ? $employee->first_name . ' ' . $employee->surname : ''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="captured_by" class="col-sm-2 control-label">Captured By</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="captured_by" name="descaptured_bycription"
                                       value="{{$name}}"
                                       placeholder="" readonly="">
                            </div>
                        </div>

                        <input type="hidden" id="tank_id" name="tank_id" value="{{$ID}}">


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" id="add-tanktopUp" class="btn btn-warning"><i
                                    class="fa fa-cloud-upload"></i> Save
                        </button>
                    </div>
            </form>
        </div>
    </div>
</div>
</div>
           