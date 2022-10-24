<div id="add-licence-modal" class="modal modal-default fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-licence-form" enctype="multipart/form-data">

                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add licence</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="name" name="name" value=""
                                   placeholder="Enter name" required>
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('description') ? ' has-error' : '' }}">
                        <label for="description" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <textarea rows="4" cols="50" class="form-control" id="details" name="details"
                                          placeholder="Enter Description">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Serial Number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="serial" name="serial" value=""
                                   placeholder="Enter serial Number" required>
                        </div>
                    </div>


                    <div class="form-group new-field {{ $errors->has('transfer_date') ? ' has-error' : '' }}">
                        <label for="TransferDate" class="col-sm-2 control-label"> Purchase Date </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control datepicker" name="purchase_date" id="purchase_date"
                                   placeholder="  dd/mm/yyyy" value="{{ old('purchase_date') }}">
                        </div>
                    </div>

                    <div class="form-group new-field {{ $errors->has('transfer_date') ? ' has-error' : '' }}">
                        <label for="expiration_date" class="col-sm-2 control-label"> Expiration Date </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control datepicker" name="expiration_date" id="expiration_date"
                                   placeholder="  dd/mm/yyyy" value="{{ old('expiration_date') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Purchase Cost</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="purchase_cost" name="purchase_cost" value=""
                                   placeholder="Enter Purchase Cost" required>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Order Number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="order_number" name="order_number" value=""
                                   placeholder="Enter Order Number" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Total Number</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="total" name="total" value=""
                                   placeholder="Enter Total" required>
                        </div>
                    </div>

                    <div class="form-group store-field">
                        <label for="financial_institution" class="col-sm-2 control-label">License Type</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-building-o"></i>
                                </div>
                                <select class="form-control select2 no-display
                                "  style="width: 100%;" id="asset_type_id" name="asset_type_id">
                                    <option value="0">*** Select Licence type ***</option>
                                    @foreach($licence_type as $type)
                                        <option value="{{ $type->id }}" {{ ($type->name) }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

{{--                    <div class="form-group">--}}
{{--                        <label for="path" class="col-sm-2 control-label">File</label>--}}
{{--                        <div class="col-sm-8">--}}

{{--                            <input type="file" id="path" name="path" class="file file-loading"--}}
{{--                                   data-allowed-file-extensions='["mp4"]' data-show-upload="false">--}}
{{--                            <strong> Allowed filetypes are mp4. Max upload size allowed is 50M."</strong>--}}
{{--                        </div>--}}
{{--                    </div>--}}


                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add-licence" class="btn btn-warning"><i
                                class="fa fa-cloud-upload"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>




