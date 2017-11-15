<div id="edit-servicedetails-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-servicedetails-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Vehicle Service Log</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                   <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Invoice Number </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="invoice_number" name="invoice_number" value=""
                                   placeholder="Enter Invoice number" required>
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Total Cost</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="total_cost" name="total_cost" value=""
                                   placeholder="Enter Total Cost" required>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="date" class="col-sm-2 control-label">Date Serviced</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="dateserviced" name="date_serviced"
                                   value="{{ old('date') }}" placeholder="Select  serviced date  ...">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Garage</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="garage" name="garage" value=""
                                   placeholder="Enter Garage" required>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="date" class="col-sm-2 control-label">Next Service Date</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="nxtservice_date" name="nxt_service_date"
                                   value="{{ old('date') }}" placeholder="Select  Inception date  ...">
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Next Service km</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="nxt_service_km" name="nxt_service_km" value=""
                                   placeholder="Enter Next Service km" required>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="description" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-8">

                            <textarea class="form-control" id="description" name="description"
                                      placeholder="Enter description..." rows="3">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="form-group supDoc-field{{ $errors->has('documents') ? ' has-error' : '' }}">
                        <label for="documents" class="col-sm-2 control-label">Attachment </label>
                        <div class="col-sm-8">

                            <input type="file" id="documents" name="documents"
                                   class="file file-loading" data-allowed-file-extensions='["pdf", "docx", "doc"]'
                                   data-show-upload="false">
                        </div>

                    </div>

                     <div class="form-group supDoc-field{{ $errors->has('documents') ? ' has-error' : '' }}">
                        <label for="documents" class="col-sm-2 control-label">Attachment </label>
                        <div class="col-sm-8">

                            <input type="file" id="documents1" name="documents1"
                                   class="file file-loading" data-allowed-file-extensions='["pdf", "docx", "doc"]'
                                   data-show-upload="false">
                        </div>

                    </div>

                    <input type="hidden" id="valueID" name="valueID"
                           value="{{ !empty($maintenance->id) ? $maintenance->id : ''}}">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="edit_servicedetails" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
        
           