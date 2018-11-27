<div id="edit-stock-location-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
			<form class="form-horizontal" name="edit-stock-location-form" enctype="multipart/form-data">
				{{ csrf_field() }}
				{{ method_field('PATCH') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Stock location</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
					@foreach($stock_levels as $stock_level)
						<div class="form-group{{ $errors->has('stock_level_' . $stock_level->level) ? ' has-error' : '' }}">
							<label for="{{ 'stock_level_' . $stock_level->level }}" class="col-sm-2 control-label">{{ $stock_level->name }}</label>

							<div class="col-sm-10">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-black-tie"></i>
									</div>
									<select id="{{ 'stock_level_' . $stock_level->level }}" name="{{ 'stock_level_' . $stock_level->level }}" class="form-control select2" onchange="stockDDOnChange(this,'edit-stock-location-modal')" style="width: 100%;">
									</select>
								</div>
							</div>
						</div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="update_stock_location" class="btn btn-primary"><i class="fa fa-floppy-o"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>