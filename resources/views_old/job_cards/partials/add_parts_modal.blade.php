<div id="add-servicetype-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-servicetype-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Service Type </h4>
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

                    <div class="form-group{{ $errors->has('vehiclemodel_id') ? ' has-error' : '' }}">
                        <label for="{{ 'vehiclemodel_id' }}" class="col-sm-2 control-label">Vehicle Make </label>

                        <div class="col-sm-8">
                            <select id="vehiclemodel_id" name="vehiclemodel_id" class="form-control select2"
                                    style="width: 100%;" onchange="catergoryDDOnChange(this)">
                                <option value="">*** Please Select a Vehicle Make ***</option>
                                <option value="0"></option>
                                @foreach($parts as $vehiclemodel)
                                    <option value="{{ $vehiclemodel->id }}">{{ $vehiclemodel->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add-servicetype" class="btn btn-warning"><i
                                class="fa fa-cloud-upload"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
           