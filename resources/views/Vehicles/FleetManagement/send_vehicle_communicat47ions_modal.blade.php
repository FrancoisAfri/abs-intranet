<div id="add-vehicle-communication-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-vehicle-communication-form">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"> Send Communication</h4>
                </div>
                <div class="modal-body">
					<div class="form-group{{ $errors->has('message_type') ? ' has-error' : '' }}">
						<label for="message_type" class="col-sm-2 control-label">Send To</label>
						<div class="col-sm-10">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-check-square-o"></i>
								</div>
								<label class="radio-inline"><input type="radio" id="rdo_clients" name="message_type" value="1" checked> Client(s</label>
								<label class="radio-inline"><input type="radio" id="rdo_employees" name="message_type" value="2"> Employees</label>
							</div>
						</div>
					</div>
                    <div class="form-group send-clients {{ $errors->has('clients') ? ' has-error' : '' }}">
						<label for="clients" class="col-sm-2 control-label">Client(s)</label>
						<div class="col-sm-10">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-users"></i>
								</div>
								<select name="clients[]" id="clients" class="form-control select2" multiple  style="width: 100%;">
									@foreach($contactPersons as $contactPerson)
										<option value="{{ $contactPerson->id}}">{{$contactPerson->comp_name."|**|". $contactPerson->first_name." ".$contactPerson->surname }}</option>
									@endforeach
								</select>
							</div>
						</div>
                    </div>
					<div class="form-group send-employee {{ $errors->has('employees') ? ' has-error' : '' }}">
                        <label for="employees" class="col-sm-2 control-label">Employees </label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <select class="form-control select2" multiple style="width: 100%;" id="employees" name="employees[]">
                                    <option value="0">*** Select Employee ***</option>
                                    @foreach($hrDetails as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->surname }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('message_type') ? ' has-error' : '' }}">
							<label for="message_type" class="col-sm-2 control-label">Type</label>
							<div class="col-sm-10">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-check-square-o"></i>
									</div>
									<label class="radio-inline"><input type="radio" id="rdo_email" name="message_type" value="1" checked> Email</label>
									<label class="radio-inline"><input type="radio" id="rdo_sms" name="message_type" value="2"> SMS</label>
								</div>
                            </div>
					</div>
					<div class="form-group sms-field {{ $errors->has('sms_content') ? ' has-error' : '' }}">
						<label for="sms_content" class="col-sm-2 control-label">SMS</label>

						<div class="col-sm-10">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-comments-o"></i>
								</div>
								<textarea name="sms_content" id="sms_content" class="form-control" placeholder="Message" rows="3" maxlength="180">{{ old('sms_content') }}</textarea>
							</div>
						</div>
					</div>
					<div class="form-group email-field {{ $errors->has('email_content') ? ' has-error' : '' }}">
						<label for="email_content" class="col-sm-2 control-label">Email</label>

						<div class="col-sm-10">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-envelope-o"></i>
								</div>
								<textarea name="email_content" id="email_content" class="form-control" placeholder="Message" rows="6" maxlength="1500">{{ old('email_content') }}</textarea>
							</div>
						</div>
					</div>
					<div class="form-group {{ $errors->has('email_content') ? ' has-error' : '' }}">
						<label for="email_content" class="col-sm-2 control-label">Send Vehicle Deatails</label>

						<div class="col-sm-10">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-check"></i>
								</div>
									<input class="rdo-iCheck" type="checkbox" id="send_fleet_details" name="send_fleet_details" value="1">
							</div>
						</div>
					</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="send_message" class="btn btn-warning"><i class="fa fa-cloud-upload"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>