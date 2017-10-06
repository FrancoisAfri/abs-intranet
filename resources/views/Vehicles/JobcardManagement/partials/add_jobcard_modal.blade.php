<div id="add-promotion-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add_new_site-form" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                     <h4 class="modal-title">Add New Maintenance.. </h4>
                </div>
                <div class="modal-body" style="max-height: 330px; overflow-y: scroll;">
                    <div id="leave-invalid-input-alert"></div>
                    <div id="success-alert"></div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Vehicle</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-truck"></i>
                                </div>
                                <select class="form-control select2" style="width: 100%;" id="product" name="product_id">
                                    <option value="">*** Select a Vehicle  ***</option>
                                    @foreach($Vehicle_managemnt as $Vehicle)
                                        <option value="{{ $Vehicle->id }}">{{ $Vehicle->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

				 <div class="form-group">
                        <label for="start_date" class="col-sm-2 control-label">Job Card Date</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control datepicker" id="start_date" name="start_date" value="{{ old('start_date') }}" placeholder="Click to Select a Date...">
                            </div>
                        </div>
                    </div>

                     <div class="form-group">
                        <label for="end_date" class="col-sm-2 control-label">Schedule Date</label>

                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control datepicker" id="end_date" name="end_date" value="{{ old('end_date') }}" placeholder="Click to Select a Date...">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="end_date" class="col-sm-2 control-label"> Service by Agent</label>

                        <div class="col-sm-8">

                            <input type="checkbox" id="external_service" value="1" name="external_service" onclick="showHide();">
                            </div>
                        </div>

                    <div class="form-group">
                        <label for="end_date" class="col-sm-2 control-label">Booking Date</label>

                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control datepicker" id="end_date" name="end_date" value="{{ old('end_date') }}" placeholder="Click to Select a Date...">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Supplier</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-truck"></i>
                                </div>
                                <select class="form-control select2" style="width: 100%;" id="product" name="product_id">
                                    <option value="">*** Select a Supplier  ***</option>
                                    @foreach($Vehicle_managemnt as $Vehicle)
                                        <option value="{{ $Vehicle->id }}">{{ $Vehicle->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Service Type</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-truck"></i>
                                </div>
                                <select class="form-control select2" style="width: 100%;" id="product" name="product_id">
                                    <option value="">*** Select Service Type  ***</option>
                                    @foreach($Vehicle_managemnt as $Vehicle)
                                        <option value="{{ $Vehicle->id }}">{{ $Vehicle->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Estimated Hours</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control"  id="name" name="name" value="" placeholder="Enter hours" required>
                        </div>

                    </div>



                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control"  id="description" name="description" value="" placeholder="Enter name" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Discount</label>
                             <div class="col-sm-8">
                             <input type="number" class="form-control" id="discount" name="discount" value="" placeholder="Enter Discount" >
                        </div>
                    </div>
					
                    <!--<div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Price</label>
                             <div class="col-sm-8">
                             <input type="number" class="form-control" id="price" name="price" value="" placeholder="Enter Price" >
                        </div>
                    </div> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add_promotion" class="btn btn-primary">Add Promotion</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>