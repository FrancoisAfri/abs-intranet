<div id="add_fireextinguishers-modal" class="modal modal-default fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" name="add-fireextinguishers-form" enctype="multipart/form-data">
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
                        <label for="path" class="col-sm-2 control-label">Enter Barcode</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="bar_code" name="bar_code"
                                   value="" placeholder="Enter Barcode ">
                        </div>
                    </div>
                        
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Enter Item</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="item_no" name="item_no"
                                   value="" placeholder="Enter Item">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Enter Description</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="Description" name="Description"
                                   value="" placeholder="Enter Description ">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Enter Weight (kg)</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="Weight" name="Weight"
                                   value="" placeholder="Enter Weight (kg)">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Enter Serial Number</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="Serial_number" name="Serial_number"
                                   value="" placeholder="Enter Serial Number">
                        </div>
                    </div>
                   
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Enter Invoice Number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="invoice_number" name="invoice_number"
                                   value="" placeholder="Enter  Invoice Number">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Enter Purchase Order Number</label>
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
                        <label for="path" class="col-sm-2 control-label">Date Purchased </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="date_purchased" name="date_purchased"
                                   value="{{ old('date_issued') }}" placeholder="Select  Purchased date ...">
                        </div>
                    </div>
                        
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Cost</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="Cost" name="Cost" value=""
                                   placeholder="Enter Cost">
                        </div>
                    </div>
                        
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Rental Amount</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="rental_amount" name="rental_amount" value=""
                                   placeholder="Enter Rental Amount">
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
<!--                    <div class="form-group supDoc-field{{ $errors->has('registration_papers') ? ' has-error' : '' }}">
                        <label for="registration_papers" class="col-sm-2 control-label">Documents </label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-file-pdf-o"></i>
                                </div>
                                <input type="file" id="registration_papers" name="registration_papers"
                                       class="file file-loading" data-allowed-file-extensions='["pdf", "docx", "doc"]'
                                       data-show-upload="false">
                            </div>
                        </div>
                    </div>-->
                  
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="addfireextinguishers" class="btn btn-warning"><i
                                class="fa fa-cloud-upload"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>