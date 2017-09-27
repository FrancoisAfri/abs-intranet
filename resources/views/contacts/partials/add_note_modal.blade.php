<div id="add-new-note-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-module-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

               <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Note for:  {{ $company->name}}  </h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                     <div class="form-group{{ $errors->has('originator_type') ? ' has-error' : '' }}">
                        <label for="originator_type" class="col-sm-2 control-label"> Originator</label>
                        <div class="col-sm-9">
                            <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_days" name="originator_type" value="1" checked> From us </label>
                            <label class="radio-inline"><input type="radio" id="rdo_hours" name="originator_type" value="2">  Contact</label>

                        </div>
                    </div>
                    <!--  -->
                         <div class="form-group {{ $errors->has('company_id') ? ' has-error' : '' }}">
                        <label for="company_id" class="col-sm-2 control-label"> Company</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user-circle"></i>
                                </div>
                               <!--  <select class="form-control select2" style="width: 100%;" id="company_id" name="company_id">
                                   -->
                                   <input type="text" class="form-control" id="company" name="company" value="{{ $company->name }}" readonly> 
                                    <input type="hidden" class="form-control"  id="company_id" name="company_id" value="{{ $company->id }}" > 

                               <!--  </select> -->
                            </div>
                        </div>
                    </div>

                    
                     <div class="form-group {{ $errors->has('hr_person_id') ? ' has-error' : '' }}">
                        <label for="hr_person_id" class="col-sm-2 control-label">Company Representative</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user-circle"></i>
                                </div>
                                <select class="form-control select2" style="width: 100%;" id="hr_person_id" name="hr_person_id">
                                    <option value="">*** Select an User ***</option>
                                    @foreach($contactPeople as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->surname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                  
                    <div class="form-group {{ $errors->has('employee_id') ? ' has-error' : '' }}">
                        <label for="employee_id" class="col-sm-2 control-label">Our Representative</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user-circle"></i>
                                </div>
                                <select class="form-control select2" style="width: 100%;" id="employee_id" name="employee_id">
                                    <option value="">*** Select an Employee ***</option>
                                    @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->surname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group " style="display: block;">
                      <label for="date" class="col-sm-2 control-label">Date </label>  
                       <div class="col-sm-4">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control datepicker" id="date" name="date" value="{{ old('date') }}" placeholder="Click to Select a Date...">
                            </div>
                        </div>
                        <label for="time" class="col-sm-1 control-label">Time</label>  
                       <div class="col-sm-3">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                               <input type="text" class="form-control" id="time" name="time" value="{{ old('time') }}" placeholder="Select Start time...">
                            </div>
                        </div>
                    </div>
					
                      <div class="form-group {{ $errors->has('communication_method') ? ' has-error' : '' }}">
                            <label for="communication_method" class="col-sm-2 control-label">Communication Method</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-pinterest-p"></i>
                                    </div>
                                    <select class="form-control select2" style="width: 100%;" id="communication_method" name="communication_method">
                                        <option value="">*** Select Method ***</option>
                                        <option value="1" >Telephone</option>
                                        <option value="2" >Meeting/Interview</option>
                                        <option value="3">Email</option>
                                        <option value="4">Fax</option>
                                        <option value="5">SMS</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    <div class="form-group{{ $errors->has('rensponse_type') ? ' has-error' : '' }}">
                        <label for="rensponse_type" class="col-sm-2 control-label"> Response</label>
                         <div class="col-sm-9">
                            <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_rensonse1" name="rensponse_type" value="1" checked> Unanswered / Unattended </label>
                            <label class="radio-inline"><input type="radio" id="rdo_rensonse" name="rensponse_type" value="2">  Answered / Attended</label>

                         </div>
                    </div>
					
					 <div class="form-group notes-field{{ $errors->has('notes') ? ' has-error' : '' }}">
                           <label for="days" class="col-sm-2 control-label">Note</label>
                            <div class="col-sm-8">
                               <div class="input-group">
                                    <div class="input-group-addon">
                                       <i class="fa fa-ticket"></i>
                                    </div>
                                    <textarea class="form-control" id="notes" name="notes" placeholder="Enter a Note Description ..." rows="3">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                        <div class="form-group {{ $errors->has('next_action') ? ' has-error' : '' }}">
                            <label for="next_action" class="col-sm-2 control-label">Next Action Required</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-pinterest-p"></i>
                                    </div>
                                    <select class="form-control select2" style="width: 100%;" id="next_action" name="next_action">
                                        <option value="">*** Select Option ***</option>
                                        <option value="1" >Test</option>
                                        
                                    </select>
                                </div>
                            </div>
                        </div>


                    <div class="form-group " style="display: block;">
                      <label for="follow_date" class="col-sm-2 control-label">Follow-up Date</label>  
                       <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control datepicker" id="follow_date" name="follow_date" value="{{ old('follow_date') }}" placeholder="Click to Select a Date...">
                            </div>
                        </div>
                    </div>

                 </div>  
                 <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add_notes" class="btn btn-primary">Save</button>
                </div>
             </form>
            </div>
         </div>
        </div>
 
           