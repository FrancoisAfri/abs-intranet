<div id="edit-stock-info-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
			<form class="form-horizontal" name="edit-stock-info-form" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Stock Info</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                     <div class="form-group odometer-field">
                        <label for="description" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="description" name="description"
                                   value="" placeholder="Enter Description">
                        </div>

                    </div>
                    <div class="form-group hours-field">
                        <label for="location" class="col-sm-2 control-label">Location</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="location" name="location" value=""
                                   placeholder="Enter Location">
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="picture" class="col-sm-2 control-label">Picture</label>

                        <div class="col-sm-8">
                            @if(!empty($avatar))
                                <div style="margin-bottom: 10px;">
                                    <img src="{{ $avatar }}" class="img-responsive img-thumbnail" width="200"
                                         height="200">
                                </div>
                            @endif
                            <input type="file" id="picture" name="picture" class="file file-loading"
                                   data-allowed-file-extensions='["jpg", "jpeg", "png"]' data-show-upload="false">
                        </div>
                    </div>					
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="update_stock_info" class="btn btn-primary"><i class="fa fa-floppy-o"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>