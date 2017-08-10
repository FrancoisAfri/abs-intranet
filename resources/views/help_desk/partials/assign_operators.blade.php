<div id="assign-operators-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-module-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

               <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Assign Operator </h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

					
					 <div class="form-group {{ $errors->has('leave_types_id') ? ' has-error' : '' }}">
                                <label for="leave_types_id" class="col-sm-2 control-label">Assign Operator </label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user-secret"></i>
                                        </div>
                                        <select id="operator_id" name="operator_id" class="form-control">
                                            <option value="leavetyes">*** Select an User  ***</option> 
                                                @foreach($operators as $user)
                                                    <option value="{{ $user->id }}">{{ $user->firstname . ' ' . $user->surname  }}</option>
                                                @endforeach
                                        </select>
                                    </div>
                               </div>
                         </div>



                 </div>  
                 <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add_operator" class="btn btn-primary fa fa-ticket">Assign Ticket</button>
                </div>
             </form>
            </div>
         </div>
        </div>
 
           