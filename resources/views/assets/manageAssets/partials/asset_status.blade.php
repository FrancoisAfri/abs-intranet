<div id="change-asset_status-modal" class="modal modal-default fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="change-status-form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Change Asset Status </h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>


                    <input type="hidden" value="{{ $asset->id }}" name="asset_id" id="asset_id">

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Asset Status</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-barcode"></i>
                                </div>
                                <select class="form-control select2" style="width: 100%;"
                                        id="asset_status" name="asset_status">
                                    <option value="0">*** Select a Status ***</option>
                                    @foreach(\App\Models\Assets::ADMIN_STATUS_SELECT as $assets)
                                        <option value="{{ $assets }}">{{ $assets }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="change-status" class="btn btn-warning"><i
                                class="fa fa-cloud-upload"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


