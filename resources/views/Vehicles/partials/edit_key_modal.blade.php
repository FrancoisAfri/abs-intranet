<div id="edit-package-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-module-form">
              <!--   {{ csrf_field() }} -->
                {{ method_field('PATCH') }}

               <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit key Tracking </h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
					
                    <!--  <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" name="name" value="" placeholder="Enter Name" required>
                        </div>
                    </div> -->
                    <div class="form-group">
                        <label for="path" class="col-sm-3 control-label">Description</label>
                             <div class="col-sm-9">
                            <input type="text" class="form-control" id="description" name="description" value="" placeholder="Enter Description" required>
                        </div>
                     </div>
                      <div class="form-group Single-field">
                        <label for="path" class="col-sm-3 control-label">Key Number </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="key_number" name="key_number" value=""
                                   placeholder="Enter Key Number" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="key_type" class="col-sm-3 control-label">Key Type</label>
                        <div class="col-sm-8">

                            <select name="key_type" id="key_type" class="form-control">
                                <option value="0">*** Select a Key Type ***</option>
                                <option value="1"> Main Key</option>
                                <option value="2"> Spare Key</option>
                                <option value="3"> Remote</option>
                            </select>

                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="key_status" class="col-sm-3 control-label">Key Status</label>
                        <div class="col-sm-8">

                            <select name="key_status" id="key_status" onChange= "changetextbox();" class="form-control">
                                <option value="0">*** Select a Key Status ***</option>
                                <option value="1"> In Use</option>
                                <option value="2"> Reallocated</option>
                                <option value="3"> Lost</option>
                                <option value="4"> In Safe</option>
                            </select>

                        </div>
                    </div>

                    <div class="form-group notes-field{{ $errors->has('description') ? ' has-error' : '' }}">
                        <label for="days" class="col-sm-3 control-label">Description</label>
                        <div class="col-sm-8">


                            <textarea class="form-control" id="description" name="description"
                                      placeholder="Enter a Brief Description of the leave Application..."
                                      rows="4">{{ old('description') }}</textarea>

                        </div>
                    </div>
                   
                    <div class="form-group ">
                        <label for="path" class="col-sm-3 control-label">Date Issued </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="date_issued" name="date_issued"
                                   value="{{ old('date_issued') }}" placeholder="Select  issue date ...">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="issued_by" class="col-sm-3 control-label"> Issued By</label>
                        <div class="col-sm-8">
                            <select class="form-control select2" style="width: 100%;" id="issued_by" name="issued_by">
                                <option value="">*** Select a Person ***</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}"> {{ !empty($employee->first_name . ' ' . $employee->surname) ? $employee->first_name . ' ' . $employee->surname : ''}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="path" class="col-sm-3 control-label">Captured By </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="employee" name="employee"
                                   value="{{ !empty($name)  ? $name : ''}}" placeholder="Select  User ..." readonly="">
                        </div>
                    </div>
					  
                  </div>  
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="edit_key" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
                </div>
            </form>
            </div>
         </div>
    </div>
        
           