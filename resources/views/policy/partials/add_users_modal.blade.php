<div id="add-user-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-user-form">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add More Users </h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <hr class="hr-text" data-content="Add Policy Users">
                    @foreach($division_levels as $division_level)
                        <div class="form-group manual-field{{ $errors->has('division_level_' . $division_level->level) ? ' has-error' : '' }}">
                            <label for="{{ 'division_level_' . $division_level->level }}"
                                   class="col-sm-2 control-label">{{ $division_level->name }}</label>
                            <div class="col-sm-8">
                                    <select id="{{ 'division_level_' . $division_level->level }}"
                                            name="{{ 'division_level_' . $division_level->level }}"
                                            class="form-control"
                                            onchange="divDDOnChange(this, null, 'add-user-modal')">
                                    </select>
                            </div>
                        </div>
                    @endforeach
                    <div class="form-group {{ $errors->has('hr_person_id') ? ' has-error' : '' }}">
                        <label for="hr_person_id" class="col-sm-2 control-label">Employees</label>
                        <div class="col-sm-8">
                                <select class="form-control select2" multiple="multiple" style="width: 100%;"
                                        id="hr_person_id" name="hr_person_id[]">
                                    {{--<option value="">*** Select an Employee ***</option>--}}
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->surname }}</option>
                                    @endforeach
                                </select>
                        </div>
                    </div>
                    <input type="hidden" id="policyID" name="policyID"
                           value="{{ !empty($policyID) ? $policyID : ''}}">


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add-user" class="btn btn-warning"><i class="fa fa-cloud-upload"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div> 