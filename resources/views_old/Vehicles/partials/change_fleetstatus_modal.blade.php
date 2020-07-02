<div id="change-fleet-status-modal" class="modal modal-default fade">
    <div class="modal-dialog modal-ls">
        <div class="modal-content">
          
            <form class="form-horizontal" method="POST" name="change-fleet-status-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Change Vehicle Status </h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
					<div class="form-group">
                        <label for="status" class="col-sm-2 control-label">Status </label>
                        <div class="col-sm-8">
						    <select id="status" name="status" class="form-control">
                                <option value="0">*** Select Status ***</option>
								<option value="1"> Active </option>
								<option value="2"> Require Approval </option>
								<option value="3"> Rejected </option>
								<option value="4"> Inactive </option>
                            </select>
                        </div>
                    </div> 
                    <input type="hidden" id="vehicle_id" name="vehicle_id"
                           value="{{ !empty($vehiclemaintenance->id) ? $vehiclemaintenance->id : ''}}">						   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="update-fleet-status" class="btn btn-warning"><i
                                class="fa fa-cloud-upload"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>