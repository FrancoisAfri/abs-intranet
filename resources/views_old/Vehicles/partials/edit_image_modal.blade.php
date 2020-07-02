<div id="edit-package-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-image-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Image</h4>
                </div>

                {{--<div class="product-img">--}}
                    {{--<img src="{{ (!empty($vehiclemaintenance->vehicle_images)) ? Storage::disk('local')->url("image/$vehiclemaintenance->vehicle_images") : 'http://placehold.it/60x50' }}"--}}
                         {{--alt="Product Image" width="50" height="50">--}}
                {{--</div>--}}
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>


                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" name="name"
                                   value="{{ $vehicle_maintenance->vehicle_images }}" placeholder="Enter Name" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-3 control-label">Description</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="description" name="description"
                                   value="{{ $vehicle_maintenance->image }}" placeholder="Enter Description" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="image" class="col-sm-3 control-label">Upload</label>

                        <div class="col-sm-9">

                            <input type="file" id="image" name="image" class="file file-loading"
                                   data-allowed-file-extensions='["jpg", "jpeg", "png"]' data-show-upload="false">
                        </div>
                    </div>

                    <input type="hidden" id="valueID" name="valueID"
                           value="{{ !empty($maintenance->id) ? $maintenance->id : ''}}">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="edit_image" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
        
           