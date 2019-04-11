<div id="edit-costs-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-costs-form">
            {{ csrf_field() }}
               {{ method_field('PATCH') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit new General Costs</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                    <div class="form-group ">
                        <label for="date" class="col-sm-2 control-label"> Date </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="date" name="date"
                                   value="{{ old('date') }}" placeholder="Select  start date  ...">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Document Number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="document_number" name="document_number" value=""
                                   placeholder="Enter document number" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Supplier Name </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="supplier_name" name="supplier_name" value=""
                                   placeholder="Enter supplier name" required>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="cost_type" class="col-sm-2 control-label">Cost Type </label>
                        <div class="col-sm-8">

                            <select name="cost_type" id="cost_type" class="form-control">
                                <option value="0">*** Select Type of Costs ***</option>
                                <option value="1">Oil</option>

                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label"> Cost</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="cost" name="cost" value=""
                                   placeholder="Enter cost" required>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label"> Litre</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="litres_new" name="litres_new" value=""
                                   placeholder="Enter Litres" required>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="description" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-8">

                            <textarea class="form-control" id="description" name="description"
                                      placeholder="Enter description..." rows="3">{{ old('description') }}</textarea>

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Person Responsible </label>
                        <div class="col-sm-8">
                            <select class="form-control select2" style="width: 100%;"
                                    id="person_esponsible" name="person_esponsible">
                                <option value="0">*** Select User ***</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->surname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <input type="hidden" id="valueID" name="valueID"
                           value="{{ !empty($maintenance->id) ? $maintenance->id : ''}}">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="edit_costs" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
        
           