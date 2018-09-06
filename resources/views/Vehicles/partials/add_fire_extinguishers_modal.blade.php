<div id="add_fireextinguishers-modal" class="modal modal-default fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
          
            <form class="form-horizontal" method="POST" name="add-fireextinguishers-form">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Vehicle Fire  Extinguishers </h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="box-body" id="vehicle_details">
                     <div class="form-group">
                        <label for="bar_code" class="col-sm-2 control-label"> Barcode</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="bar_code" name="bar_code"
                                   value="" placeholder="Enter Barcode ">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="item_no" class="col-sm-2 control-label"> Item</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="item_no" name="item_no"
                                   value="" placeholder="Enter Item">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Description" class="col-sm-2 control-label"> Description</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="Description" name="Description"
                                   value="" placeholder="Enter Description ">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Weight" class="col-sm-2 control-label"> Weight (kg)</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="Weight" name="Weight"
                                   value="" placeholder="Enter Weight (kg)">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Serial_number" class="col-sm-2 control-label"> Serial Number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="Serial_number" name="Serial_number"
                                   value="" placeholder="Enter Serial Number">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="invoice_number" class="col-sm-2 control-label"> Invoice Number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="invoice_number" name="invoice_number"
                                   value="" placeholder="Enter  Invoice Number">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="purchase_order" class="col-sm-2 control-label"> Purchase Order Number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="purchase_order" name="purchase_order"
                                   value="" placeholder="Enter Purchase Order Number">
                        </div>
                    </div>
                    <div class="form-group user-field">
                        <label for="issued_to" class="col-sm-2 control-label">Supplier </label>
                        <div class="col-sm-8">
                            <select class="form-control select2" style="width: 100%;" id="supplier_id" name="supplier_id">
                                <option value="0">*** Select a Supplier ***</option>
                                @foreach($ContactCompany as $supplier)
                                   <option value="{{ $supplier->id }}" >{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="date_purchased" class="col-sm-2 control-label">Date Purchased </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="date_purchased" name="date_purchased"
                                   value="{{ old('date_purchased') }}" placeholder="Select  Purchased date ...">
                        </div>
                    </div>   
                    <div class="form-group">
                        <label for="Cost" class="col-sm-2 control-label">Cost</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="Cost" name="Cost" value=""
                                   placeholder="Enter Cost">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="image" class="col-sm-2 control-label">Image</label>
                        <div class="col-sm-8">
                            @if(!empty($avatar))
                                <div style="margin-bottom: 10px;">
                                    <img src="{{ $avatar }}" class="img-responsive img-thumbnail" width="200"
                                         height="200">
                                </div>
                            @endif
                            <input type="file" id="image" name="image" class="file file-loading"
                                   data-allowed-file-extensions='["jpg", "jpeg", "png"]' data-show-upload="false">
                        </div>
                    </div>
					<div class="form-group supDoc-field{{ $errors->has('documents') ? ' has-error' : '' }}">
                        <label for="documents" class="col-sm-2 control-label">Document </label>
                        <div class="col-sm-8">

                            <input type="file" id="documents" name="documents"
                                   class="file file-loading" data-allowed-file-extensions='["pdf", "docx", "doc"]'
                                   data-show-upload="false">
                        </div>
                    </div>
                    <input type="hidden" id="valueID" name="valueID"
                           value="{{ !empty($maintenance->id) ? $maintenance->id : ''}}">  
					</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add-fire-extinguishers" class="btn btn-warning"><i
                                class="fa fa-cloud-upload"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>