<div id="edit-administrator-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-admin-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Administrator</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
					<div class="form-group {{ $errors->has('leave_types_id') ? ' has-error' : '' }}">
                                <label for="leave_types_id" class="col-sm-2 control-label">Edit Administrator </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-black-tie"></i>
                                        </div>
                                        <select id="admin_id" name="admin_id" class="form-control">
                                            <option value="leavetyes">*** Select Admin  ***</option> 
                                                @foreach($employees as $user)
                                                    <option value="{{ $user->id }}">{{ $user->first_name . ' ' . $user->surname  }}</option>
                                                @endforeach
                                        </select>
                                    </div>
                               </div>
                         </div>
                </div>  
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="update-admin" class="btn btn-primary">Save</button>
                </div>
			</form>
		</div>
	</div>
</div>  