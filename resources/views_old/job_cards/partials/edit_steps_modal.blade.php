<div id="edit-step-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-step-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Process Flow </h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                    <div class="form-group">
                        <label for="step_number" class="col-sm-2 control-label"> Step number</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="step_number" name="step_number" value=""
                                   placeholder="" readonly="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="step_name" class="col-sm-2 control-label">Step Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="step_name" name="step_name" value=""
                                   placeholder="Enter step name" required>
						</div>
                    </div>
                    <div class="form-group">
                        <label for="job_title" class="col-sm-2 control-label">Job Title</label>
                        <div class="col-sm-8">
                            <select id="job_title" name="job_title" class="form-control select2" style="width: 100%;">
                                <option value="">*** Select Role ***</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->description }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="edit_step" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
        
           