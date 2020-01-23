<div id="add-division-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-division-form">
                {{ csrf_field() }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add New {{$highestLvl->name}} </h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" name="name" value="" placeholder="Enter Name" required>
                        </div>
                    </div>
					<div class="form-group">
                        <label for="manager_id" class="col-sm-3 control-label">Manager</label>
                        <div class="col-sm-9">
                            <select id="manager_id" name="manager_id" class="form-control select2"  style="width: 100%;">
                                <option selected="selected" value="">*** Select a Manager ***</option>
								@foreach($employees as $employee)
									<option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->surname }}</option>
								@endforeach
							</select>
                        </div>
                    </div>
					<div class="form-group">
                        <label for="hr_manager_id" class="col-sm-3 control-label">HR Manager</label>
                        <div class="col-sm-9">
                             <select id="hr_manager_id" name="hr_manager_id" class="form-control select2"  style="width: 100%;">
                                <option selected="selected" value="">*** Select HR Manager ***</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->surname }}</option>
                                    @endforeach
							</select>
                        </div>
                    </div>
					<div class="form-group">
                        <label for="payroll_officer" class="col-sm-3 control-label">Payroll Administrator</label>
                        <div class="col-sm-9">
                             <select id="payroll_officer" name="payroll_officer" class="form-control select2"  style="width: 100%;">
                                <option selected="selected" value="">*** Select Payroll Administrator ***</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->surname }}</option>
                                    @endforeach
							</select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="save_firstlevel" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
                </div>
            </form>

        </div>
         
    </div>
</div>