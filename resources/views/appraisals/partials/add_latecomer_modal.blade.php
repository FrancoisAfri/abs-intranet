<div id="add-latecomer-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-module-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

               <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Days</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                     <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Number of Times</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="number_of_times" name="number_of_times" value="" placeholder="Enter Number of times" required>
                        </div>
                    </div><br><br>
                    <div class="form-group">
                        <label for="action" class="col-sm-3 control-label">Percentage (%)</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar-o"></i>
                                </div>
                                <input type="text" class="form-control" id="percentage" name="percentage" placeholder="Enter percentage ">
                            </div>
                        </div>
                    </div>
                    
                             </div><br>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="update_latecomer" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
                </div>
            </form>

            </div>
         
            </div>
            </div>
           