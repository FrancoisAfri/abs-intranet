<div id="add-asset-modal" class="modal modal-default fade">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-asset-form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Asset</h4>
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
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label"> Description</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="description" name="description" value=""
                                   placeholder="Enter Description" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label"> Serial Number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="serial_number" name="serial_number" value=""
                                   placeholder="Enter Serial Number" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label"> Asset Tag</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="asset_tag" name="asset_tag" value=""
                                   placeholder="Enter Asset Tag Number" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label"> Model Number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="model_number" name="model_number" value=""
                                   placeholder="Enter Asset Model Number" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label"> Make Number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="make_number" name="make_number" value=""
                                   placeholder="Enter Make Number" required>
                        </div>
                    </div>

                    <input type="hidden" value="{{ Auth::user()->id }}" name="user_id" id="user_id">

                    <input type="hidden" value="Un Allocated" name="asset_status" id="asset_status">

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label"> Price</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="price" name="price" value=""
                                   placeholder="Enter Price" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Asset Type</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-barcode"></i>
                                </div>
                                <select class="form-control select2" style="width: 100%;"
                                        id="asset_type_id" name="asset_type_id">
                                    <option value="0">*** Select a Asset Type ***</option>
                                    @foreach($assetType as $assets)
                                        <option value="{{ $assets->id }}">{{ $assets->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="image" class="col-sm-2 control-label">Image</label>
                        <div class="col-sm-8">
                            <input type="file" id="picture" name="picture" class="file file-loading"
                                   data-allowed-file-extensions='["jpg", "jpeg", "png"]' data-show-upload="false">
                        </div>
                    </div>
					
					<div class="form-group new-field {{ $errors->has('transfer_date') ? ' has-error' : '' }}">
                        <label for="TransferDate" class="col-sm-2 control-label">Date Assigned</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control datepicker" name="transfer_date"
                                   placeholder="  dd/mm/yyyy" value="{{ old('transfer_date') }}">
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('transfer_type') ? ' has-error' : '' }}">
                        <label for="meeting_type" class="col-sm-2 control-label">Assign to</label>

                        <div class="col-sm-10">

                            <label class="radio-inline"><input type="radio" id="rdo_user" name="transfer_to" value="1"
                                                               checked> User </label>

                            <label class="radio-inline"><input type="radio" id="rdo_store" name="transfer_to"
                                                               value="2"> Store </label>
                        </div>
                    </div>
					<div class="form-group user-field">
						<label for="company" class="col-sm-2 control-label">User</label>
						<div class="col-sm-8">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-users"></i>
								</div>
								<select class="form-control select2" style="width: 100%;" id="user_id"
										name="user_id">
									<option value="0">*** Select User ***</option>
									@foreach($users as $user)
										<option value="{{ $user->id }}" {{ ($user->first_name . ' '. $user->surname) }}>
											{{ $user->first_name . ' '. $user->surname }}
										</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>
                    <div class="form-group store-field">
                        <label for="financial_institution" class="col-sm-2 control-label">Store</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-building-o"></i>
                                </div>
                                <select class="form-control select2 no-display
                                " style="width: 100%;" id="store_id"
                                        name="store_id">
                                    <option value="0">*** Select Store ***</option>
                                    @foreach($stores as $store)
                                        <option value="{{ $store->id }}" {{ ($store->name) }}>
                                            {{ $store->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="image" class="col-sm-2 control-label">Transfer Image</label>
                        <div class="col-sm-8">

                            <input type="file" id="picture" name="picture_transfer[]" multiple class="file file-loading"
                                   data-allowed-file-extensions='["jpg", "jpeg", "png"]' data-show-upload="false">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add-asset" class="btn btn-warning"><i
                                class="fa fa-cloud-upload"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


