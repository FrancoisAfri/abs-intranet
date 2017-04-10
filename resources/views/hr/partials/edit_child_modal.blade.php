<div id="edit-child-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="company-modal-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

               <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit {{$parentDiv->name}}</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                     <div class="form-group">
                       <label for="action" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" name="name" value=""  placeholder="Enter Name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="action" class="col-sm-2 control-label">Manager Name</label>
                         <div class="col-sm-10">
                             <select id="manager_id" name="manager_id" class="form-control select2"  style="width: 100%;" >
                                <option selected="selected" value="" >*** Select a Manager ***</option>
                                    @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->surname }}</option>
                                    @endforeach
                        </select>
                           </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="update_child-modal" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Update</button>
                </div>
            </form>

            </div>
         
            </div>
            </div>
             
                   