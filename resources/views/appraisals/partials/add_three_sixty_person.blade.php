<div id="add-three-sixty-person-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-three-sixty-person-form">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add People to Your Three-Sixty</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group">
                        <label for="emp_id" class="col-sm-2 control-label">Employees</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <select class="form-control select2" id="emp_id" name="emp_id[]" style="width: 100%;" multiple required>
                                    <!--<option value="">*** Select At Least One Employee ***</option>-->
                                    @foreach($threeSixtyDDEmps as $threeSixtyDDEmp)
                                        <option value="{{ $threeSixtyDDEmp->id }}">{{ $threeSixtyDDEmp->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Close</button>
                    <button type="button" id="add-three-sixty-person-btn" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Add</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>