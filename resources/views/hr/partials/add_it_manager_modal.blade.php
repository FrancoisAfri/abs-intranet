<div id="add-it-manager" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-manager-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add It Manager</h4>
                </div>
                <div class="modal-body">
                    <div id="category-invalid-input-alert"></div>
                    <div id="category-success-alert"></div>

                    <div class="form-group {{ $errors->has('hr_person_id') ? ' has-error' : '' }}">
                        <label for="hr_person_id" class="col-sm-2 control-label">Users</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user-circle"></i>
                                </div>
                                <select class="form-control select2"  style="width: 100%;"
                                        id="user_id" name="user_id">
                                    <option value="">*** Select Employee ***</option>
                                    @foreach($people as $employee)
                                        <option value="{{ $employee->id }} ">{{$employee->first_name . ' ' . $employee->surname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add-manager" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>