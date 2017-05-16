<div id="add-child-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-module-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

               <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add New {{$childLevel->name}} </h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                     <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" name="name" value="" placeholder="Enter Name" required>
                        </div>
                    </div><br><br>
                    <div class="form-group">
                        <label for="path" class="col-sm-3 control-label">Manager</label>
                        <div class="col-sm-9">
                             <select id="manager_id" name="manager_id" class="form-control select2"  style="width: 100%;" required>
                                <option selected="selected" value="" required >*** Select a Manager ***</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->surname }}</option>
                                    @endforeach
                        </select>
                           </div>
                             </div>
                             </div><br>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="save_childlevel" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
                </div>
            </form>

            </div>
         
            </div>
            </div>
           
    
                      