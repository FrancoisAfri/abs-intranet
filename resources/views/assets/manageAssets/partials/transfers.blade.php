<div id="new-transfer-modal" class="modal modal-default fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class=" dropzone form-horizontal" method="POST" name="new-transfer-form"
                  enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Asset Transfer</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                    {{--  hidden Inputs   --}}
                    <input type="hidden" value="{{ $asset->id}}" name="asset_id" id="asset_id">
                    <input type="hidden" value="{{ $asset->name }}" name="name" id="name">
                    <div class="form-group new-field {{ $errors->has('transfer_date') ? ' has-error' : '' }}">
                        <label for="TransferDate" class="col-sm-2 control-label">Date</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control datepicker" name="transfer_date"
                                   placeholder="  dd/mm/yyyy" value="{{ old('transfer_date') }}">
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('transfer_type') ? ' has-error' : '' }}">
                        <label for="meeting_type" class="col-sm-2 control-label">Transfer to</label>

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
                        <label for="image" class="col-sm-2 control-label">Image</label>
                        <div class="col-sm-8">

                            <input type="file" id="picture" name="picture[]" multiple class="file file-loading"
                                   data-allowed-file-extensions='["jpg", "jpeg", "png"]' data-show-upload="false">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="new-transfer" class="btn btn-success"><i
                                class="fa fa-cloud-upload"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



