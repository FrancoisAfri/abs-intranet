<div id="edit-company-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="company-modal-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

               <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit {{$highestLvl->name}}</h4>
                </div>
                <div class="modal-body">
                    <div id="module-invalid-input-alert"></div>
                    <div id="module-success-alert"></div>

                     <div class="form-group">
                       <label for="action" class="col-sm-3 control-label">Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" name="name" value="" placeholder="Enter Name" required>
                        </div>
                               </div>
                                  </div>
                            

                    <div class="form-group">
                        <label for="action" class="col-sm-3 control-label">Manager's Name</label>
                        <div class="col-sm-9">
                   <select id="manager_id" name="manager_id" class="form-control select2"  style="width: 100%;" disabled="">
                                <option selected="selected" value="0" required >*** Select a Manager ***</option>==$0
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->surname }}</option>
                                    @endforeach
                        </select>
                             </div>
                             </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="update_company-modal" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
                </div>
            </form>

            </div>
         
            </div>
            </div>
             
                   