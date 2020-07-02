<div id="add-steps-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-steps-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Process Flow </h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label"> Step number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="step_number" name="step_number"
                                   value="{{$newstep}}"
                                   placeholder="" readonly="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Step Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="step_name" name="step_name" value=""
                                   placeholder="Enter step name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="leave_type" class="col-sm-2 control-label">Job Title</label>
                        <div class="col-sm-8">
                            <select id="job_title" name="job_title" class="form-control">
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
                    <button type="button" id="add-steps" class="btn btn-warning"><i class="fa fa-cloud-upload"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
           