<div id="add-new-ticket-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-module-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

               <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Your ticket details</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                     
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Name</label>
                             <div class="col-sm-8">
                            <input type="text" class="form-control" id="name" name="name" value="{{ $names. ' ' .$surname}}" placeholder="Enter Name ">
                        </div>
                    </div>
					
					<div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Email</label>
                             <div class="col-sm-8">
                            <input type="email" class="form-control" id="email" name="email" value="{{ $email }}" placeholder="Enter email">
                        </div>
                    </div>
					
                        <div class="form-group {{ $errors->has('helpdesk_id') ? ' has-error' : '' }}">
                                <label for="leave_types_id" class="col-sm-2 control-label">Help Desk </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-black-tie"></i>
                                        </div>
                                        <select id="helpdesk_id" name="helpdesk_id" class="form-control">
                                            <option value="leavetyes">*** Select a Help Desk  ***</option> 
                                                @foreach($Helpdesk as $helpdesk)
                                                    <option value="{{ $helpdesk->id }}">{{ $helpdesk->name }}</option>
                                                @endforeach
                                        </select>
                                    </div>
                               </div>
                         </div>
					
					
							<!--  -->
							<div class="form-group">
								<label for="path" class="col-sm-4 control-label">Subject</label>
									 <div class="col-sm-4">
									<input type="text" class="form-control" id="subject" name="subject" value="" placeholder="Enter subject ">
								</div>
							</div>
					
					 <div class="form-group notes-field{{ $errors->has('message') ? ' has-error' : '' }}">
                           <label for="days" class="col-sm-2 control-label">Your Message</label>
                            <div class="col-sm-8">
                               <div class="input-group">
                                    <div class="input-group-addon">
                                       <i class="fa fa-ticket"></i>
                                    </div>
                                    <textarea class="form-control" id="message" name="message" placeholder="Enter a Brief Description of ticket..." rows="6">{{ old('message') }}</textarea>
                                </div>
                            </div>
                        </div>

                 </div>  
                 <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add_tiket" class="btn btn-primary">Save</button>
                </div>
             </form>
            </div>
         </div>
        </div>
 
           