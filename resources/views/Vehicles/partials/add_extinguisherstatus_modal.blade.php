<div id="change_status-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-module-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title">Edit Vehicle Fire Extinguishers  Status</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
        
                    <div class="form-group">
                        <label for="Status" class="col-sm-2 control-label">Status </label>
                        <div class="col-sm-8">
                            <select id="Status" name="Status" class="form-control">
                                <option value="1">*** Select Status ***</option>
                                <option value="2"> Active</option>
                                <option value="3"> In Use</option>
                                <option value="4"> Empty</option>
                                <option value="5"> Evacate</option>
                                <option value="6"> In Storage</option>
                                <option value="7"> Discarded</option>
                                <option value="8"> Rental</option>
                                <option value="9"> Sold</option>
                            </select>
                        </div>
                    </div>
                                        
                <input type="hidden" id="valueID" name="valueID"
                           value="{{ !empty($maintenance->id) ? $maintenance->id : ''}}">            
               </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="change_status" class="btn btn-warning"><i class="fa fa-floppy-o"></i> Save
                    </button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>