@extends('layouts.main_layout')

@section('page_dependencies') 
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-anchor pull-right"></i>
                     <h3 class="box-title">Help Desk Set up </h3>
                </div>
                <!-- <form name="leave-application-form" class="form-horizontal" method="POST" action=" " enctype="multipart/form-data"> -->
                <form class="form-horizontal" id="report_form" method="POST" action="{{ !empty($helpdeskSetup->id) ? '/help_desk/setup/'.$helpdeskSetup->id : '/help_desk/setup'}}">
                   <input type="hidden" name="helpdesk_id" id="helpdesk_id" value="{{ $serviceID }}"/>
				   {{ csrf_field() }}
                    <div class="box-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-ban"></i> Invalid Input Data!</h4>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif                       
                        <div class="box-body">
							<div class="form-group {{ $errors->has('system_name') ? ' has-error' : '' }}">
								<label for="system_name" class="col-sm-2 control-label">System</label>
								<div class="col-sm-8">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-assistive-listening-systems"></i>
										</div>
									<select class="form-control select2" style="width: 100%;" id="system_name" name="system_name"  disabled="false">
												
												<option value="{{ $serviceName }}" >{{ $serviceName }}</option>
											  
										</select>
									</div>
								</div>
							</div>                 
							<div class="form-group">
								<label for="employee_number" class="col-sm-2 control-label">System Description</label>
								<div class="col-sm-8">
									<div class="input-group">
										<div class="input-group-addon">
										   <i class="fa fa-sticky-note"></i>
										</div>
										<input type="text" class="form-control" id="description" name="description"
											   value="{{ $description }}" readonly>
									</div>
								</div>
							</div>
							<div class="form-group neg-field {{ $errors->has('ticket_name') ? ' has-error' : '' }}">
								<label for="ticket_name" class="col-sm-2 control-label">Ticket Name (i.e: Ticket, Task, Fault)</label>
								<div class="col-sm-8">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-ticket"></i>
										</div>
									   <input type="text" id ="negannual" class="form-control pull-left" name="val"  value="TICKET# _{{ $serviceID}}" disabled="false">
									</div>
								</div>
							</div>
							<div class="form-group {{ $errors->has('hr_person_id') ? ' has-error' : '' }}">
								<label for="hr_person_id" class="col-sm-2 control-label">Maximum Priority & Operator Level</label>
								<div class="col-sm-8">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-pinterest-p"></i>
										</div>
										<select name="maximum_priority" class="form-control">
											<option value="">*** Select Your Priority ***</option>
											<option value="1"{{ (isset($helpdeskSetup) && $helpdeskSetup->maximum_priority === 1) ? ' selected' : '' }} >Low</option>
											<option value="2" {{ (isset($helpdeskSetup) && $helpdeskSetup->maximum_priority === 2) ? ' selected' : '' }}>Medium</option>
											<option value="3" {{ (isset($helpdeskSetup) && $helpdeskSetup->maximum_priority === 3) ? ' selected' : '' }}>High</option>
										</select>
									</div>
								</div>
							</div>
						</div>                
						<!-- /.box-body -->
						<div class="box-footer">
							<input type="submit" id="load-allocation" name="load-allocation" class="btn btn-primary pull-right" value="Submit">
						</div>
						<!-- /.box-footer -->
					</div>
                </form>
            </div>
        </div>
		<div class="col-md-6">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-anchor pull-right"></i>
					<h3 class="box-title">Notifications Settings</h3>
                </div>
                <form class="form-horizontal" id="report_form" method="POST" action="{{!empty($helpdeskSetup->id) ? '/help_desk/notify_managers/'.$helpdeskSetup->id : '/help_desk/notify_managers'}}">
                    {{ csrf_field() }}
                    <div class="box-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-ban"></i> Invalid Input Data!</h4>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row emp-field" style="display: block;">
							<div class="col-xs-6">
								<div class="form-group from-field {{ $errors->has('time_from') ? ' has-error' : '' }}">
									<label for="time_from" class="col-sm-4 control-label">Time From</label>
									<div class="col-sm-8">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-clock-o"></i>
											</div>
											<input type="text" class="form-control" id="time_from" name="time_from" value="{{ !empty($helpdeskSetup->time_from) ? date('H:i:s', $helpdeskSetup->time_from) : old('time_from') }}" placeholder="Select Start time...">
										</div>
									</div>
								</div>
							</div>
							<div class="col-xs-6">
								<div class="form-group to-field {{ $errors->has('time_to') ? ' has-error' : '' }}">
									<label for="time_to" class="col-sm-3 control-label"> To</label>
									<div class="col-sm-9">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-clock-o"></i>
											</div>
											<input type="text" class="form-control" id="time_to" name="time_to" value="{{ !empty($helpdeskSetup->time_to) ? date('H:i:s', $helpdeskSetup->time_to) : old('time_to') }}" placeholder="Select End time...">
										</div>
									</div>
								</div>
							</div>
                        </div>
                        <input id="invisible_id" name="helpdesk_id" type="hidden" value="{{ $serviceID }}">   
						<table class="table table-bordered">
							<div class="form-group">
								<tr>
									<td>Notify HR with Application</td>
									<td style="text-align: center; vertical-align: middle;">
										<td>
											<!-- <div> <input type="hidden" class="checkbox selectall"  name="notify_hr_sms_sms" value="0"><input type="checkbox" name="notify_hr_email">Email</div><input type="hidden" class="checkbox selectall"  name="notify_hr_email" value="0"> -->
											<div class="sms"><input type="checkbox" name="notify_hr_email" value="1" {{ !empty($helpdeskSetup->notify_hr_email) && $helpdeskSetup->notify_hr_email == 1 ? "checked='checked'" : '' }}> Email</div>
											<div class="sms"><input type="checkbox" name="notify_hr_sms_sms" value="1" {{ !empty($helpdeskSetup->notify_hr_sms_sms) && $helpdeskSetup->notify_hr_sms_sms == 1 ? "checked='checked'" : '' }}> SMS</div>
										</td> 
									</td>
								</tr>
							</div>
							<!--  -->
							<div class="form-group">
								<tr>
									<td>Notify Managers of New Tickets (After Hours)</td>
									<td>
										<div class="sms"><input type="checkbox" name="notify_manager_email" value="1" {{ !empty($helpdeskSetup->notify_manager_email) && $helpdeskSetup->notify_manager_email == 1 ? "checked='checked'" : '' }}> Email</div>
										<div class="sms"><input type="checkbox" name="notify_manager_sms" value="1" {{ !empty($helpdeskSetup->notify_manager_sms) && $helpdeskSetup->notify_manager_sms == 1 ? "checked='checked'" : '' }}> SMS</div>
									</td>
								</tr>
							</div>
						</table>                       
						<!-- /.box-body -->
						<div class="box-footer">
							<input type="submit" id="" name="" class="btn btn-primary pull-right" value="Submit">
						</div>
						<!-- /.box-footer -->
					</div>
                </form>
            </div>
        </div>
    </div>
    <!--  -->
    <!--  -->
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-anchor pull-right"></i>
                    <h3 class="box-title">Auto-Escalations Settings</h3>
                </div>
                    <form class="form-horizontal" id="report_form" method="POST" action="{{!empty($autoEscalationSettings->id) ? '/help_desk/auto_escalations/'.$autoEscalationSettings->id : '/help_desk/auto_escalations'}}">
                     <input type="hidden" name="helpdesk_id" id="helpdesk_id" value="{{ $serviceID }}"/>
					{{ csrf_field() }}

                    <div class="box-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-ban"></i> Invalid Input Data!</h4>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                          <table class="table table-bordered">
                    <tr>
                        <th style="width: 10px"></th>
                        <th>Hours</th>
                        <th>Office Hours Only</th>
                         <th>Notify from Level</th>
                         <th>Office Hours</th>
                           <th>After Hours </th>
                        <th style="width: 40px"></th>
                    </tr>
                    <tr id="modules-list">
                        <td>Low </td>
                        <td><input type="text" size="2" name="auto_low" value="{{!empty($autoEscalationSettings->auto_low) ? $autoEscalationSettings->auto_low : '' }}"></td>
                        <!--  -->
                         <!-- <td style="text-align:center;"><input type="checkbox" name="office_hrs_low"></td> -->
                        <td style="text-align:center;"><div class="sms"><input type="checkbox" name="office_hrs_low" value="1" {{ !empty($autoEscalationSettings->office_hrs_low) && $autoEscalationSettings->office_hrs_low == 1 ? "checked='checked'" : '' }}></div></td>
                         <!--  -->
                        <td style="text-align:center;">
							<select name="notify_level_low">
								<option value="1" {{ !empty($autoEscalationSettings->notify_level_low) && $autoEscalationSettings->notify_level_low === 1 ? ' selected' : '' }} >low</option>
								<option value="2" {{ !empty($autoEscalationSettings->notify_level_low) && $autoEscalationSettings->notify_level_low === 2 ? ' selected' : '' }} >medium</option>
								<option value="3" {{ !empty($autoEscalationSettings->notify_level_low) && $autoEscalationSettings->notify_level_low === 3 ? ' selected' : '' }} >high</option>
							</select>
						</td>
						<td>
						 <!--  -->
							<input type="checkbox" name="office_hrs_low_email" value="1" {{ !empty($autoEscalationSettings->office_hrs_low_email) && $autoEscalationSettings->office_hrs_low_email == 1 ? "checked='checked'" : '' }}> Email
							<input type="checkbox" name="office_hrs_low_sms" value="1" {{ !empty($autoEscalationSettings->office_hrs_low_sms) && $autoEscalationSettings->office_hrs_low_sms == 1 ? "checked='checked'" : '' }}> SMS
						</td>
						<td>
						  <!--  -->
							<input type="checkbox" name="aftoffice_hrs_low_email" value="1" {{ !empty($autoEscalationSettings->aftoffice_hrs_low_email) && $autoEscalationSettings->aftoffice_hrs_low_email == 1 ? "checked='checked'" : '' }}> Email
							<input type="checkbox" name="aftoffice_hrs_low_sms" value="1" {{ !empty($autoEscalationSettings->aftoffice_hrs_low_sms) && $autoEscalationSettings->aftoffice_hrs_low_sms == 1 ? "checked='checked'" : '' }}> SMS
						</td>
                      
                    </tr>
                    <tr id="modules-list">
                        <td>Normal </td>
                        <td><input type="text" size="2" name="auto_mormal" value="{{!empty($autoEscalationSettings->auto_mormal) ? $autoEscalationSettings->auto_mormal : '' }}"></td>
                        <!--  -->
                        <td style="text-align:center;"><input type="checkbox" name="office_hrs_normal" value="1" {{ !empty($autoEscalationSettings->office_hrs_normal) && $autoEscalationSettings->office_hrs_normal == 1 ? "checked='checked'" : '' }}></td>
                        <td style="text-align:center;">
							<select name="notify_level_normal">
								<option value="1" {{ !empty($autoEscalationSettings->notify_level_normal) && $autoEscalationSettings->notify_level_normal === 1 ? ' selected' : '' }} >low</option>
								<option value="2" {{ !empty($autoEscalationSettings->notify_level_normal) && $autoEscalationSettings->notify_level_normal === 2 ? ' selected' : '' }} >medium</option>
								<option value="3" {{ !empty($autoEscalationSettings->notify_level_normal) && $autoEscalationSettings->notify_level_normal === 3 ? ' selected' : '' }} >high</option>
							</select>
						</td>
                        <td>
                            <input type="checkbox" name="office_hrs_normal_email" value="1" {{ !empty($autoEscalationSettings->office_hrs_normal_email) && $autoEscalationSettings->office_hrs_normal_email == 1 ? "checked='checked'" : '' }}> Email
                            <input type="checkbox" name="office_hrs_normal_sms" value="1" {{ !empty($autoEscalationSettings->office_hrs_normal_sms) && $autoEscalationSettings->office_hrs_normal_sms == 1 ? "checked='checked'" : '' }}> SMS
						</td>
						<td>
							<input type="checkbox" name="aftoffice_hrs_normal_email" value="1" {{ !empty($autoEscalationSettings->aftoffice_hrs_normal_email) && $autoEscalationSettings->aftoffice_hrs_normal_email == 1 ? "checked='checked'" : '' }}> Email
							<input type="checkbox" name="aftoffice_hrs_normal_sms" value="1" {{ !empty($autoEscalationSettings->aftoffice_hrs_normal_sms) && $autoEscalationSettings->aftoffice_hrs_normal_sms == 1 ? "checked='checked'" : '' }}> SMS
						</td>
                    </tr>
                    <tr id="modules-list">
                        <td>High </td>
                        <td><input type="text" size="2" name="auto_high" value="{{!empty($autoEscalationSettings->auto_high) ? $autoEscalationSettings->auto_high : '' }}"></td>
                        <td style="text-align:center;"><input type="checkbox" name="office_hrs_hihg" value="1" {{ !empty($autoEscalationSettings->office_hrs_hihg) && $autoEscalationSettings->office_hrs_hihg == 1 ? "checked='checked'" : '' }}></td>  
                        <td style="text-align:center;">
							<select name="notify_level_high">
								<option value="1" {{ !empty($autoEscalationSettings->notify_level_high) && $autoEscalationSettings->notify_level_high === 1 ? ' selected' : '' }} >low</option>
								<option value="2" {{ !empty($autoEscalationSettings->notify_level_high) && $autoEscalationSettings->notify_level_high === 2 ? ' selected' : '' }} >medium</option>
								<option value="3" {{ !empty($autoEscalationSettings->notify_level_high) && $autoEscalationSettings->notify_level_high === 3 ? ' selected' : '' }} >high</option>
							</select>
						</td>
                        <td>
                            <input type="checkbox" name="office_hrs_high_email" value="1" {{ !empty($autoEscalationSettings->office_hrs_high_email) && $autoEscalationSettings->office_hrs_high_email == 1 ? "checked='checked'" : '' }}> Email
							<input type="checkbox" name="office_hrs_high_sms" value="1" {{ !empty($autoEscalationSettings->office_hrs_high_sms) && $autoEscalationSettings->office_hrs_high_sms == 1 ? "checked='checked'" : '' }}> SMS
						</td>
                        <td>
                            <input type="checkbox" name="aftoffice_hrs_high_email" value="1" {{ !empty($autoEscalationSettings->aftoffice_hrs_high_email) && $autoEscalationSettings->aftoffice_hrs_high_email == 1 ? "checked='checked'" : '' }}> Email
							<input type="checkbox" name="aftoffice_hrs_high_sms" value="1" {{ !empty($autoEscalationSettings->aftoffice_hrs_high_sms) && $autoEscalationSettings->aftoffice_hrs_high_sms == 1 ? "checked='checked'" : '' }}> SMS
                        </td>
                    </tr>
                    <tr id="modules-list">
                        <td>Critical </td>
                        <td><input type="text" size="2" name="auto_critical" value="{{!empty($autoEscalationSettings->auto_critical) ? $autoEscalationSettings->auto_critical : '' }}"></td>
                        <td style="text-align:center;">
							<input type="checkbox" name="office_hrs_critical" value="1" {{ !empty($autoEscalationSettings->office_hrs_critical) && $autoEscalationSettings->office_hrs_critical == 1 ? "checked='checked'" : '' }}>
						</td> 
                        <td style="text-align:center;">
							<select name="notify_level_critical">
								<option value="1" {{ !empty($autoEscalationSettings->notify_level_critical) && $autoEscalationSettings->notify_level_critical === 1 ? ' selected' : '' }}>low</option>
								<option value="2" {{ !empty($autoEscalationSettings->notify_level_critical) && $autoEscalationSettings->notify_level_critical === 2 ? ' selected' : '' }}>medium</option>
								<option value="3" {{ !empty($autoEscalationSettings->notify_level_critical) && $autoEscalationSettings->notify_level_critical === 3 ? ' selected' : '' }}>high</option>
							</select>
						</td>
                        <td>   
                            <input type="checkbox" name="office_hrs_critical_email" value="1" {{ !empty($autoEscalationSettings->office_hrs_critical_email) && $autoEscalationSettings->office_hrs_critical_email == 1 ? "checked='checked'" : '' }}> Email
							<input type="checkbox" name="office_hrs_critical_sms" value="1" {{ !empty($autoEscalationSettings->office_hrs_critical_sms) && $autoEscalationSettings->office_hrs_critical_sms == 1 ? "checked='checked'" : '' }}> SMS
						</td>
                        <td>
						  <input type="checkbox" name="aftoffice_hrs_critical_email" value="1" {{ !empty($autoEscalationSettings->aftoffice_hrs_critical_email) && $autoEscalationSettings->aftoffice_hrs_critical_email == 1 ? "checked='checked'" : '' }}> Email
						  <input type="checkbox" name="aftoffice_hrs_critical_sms" value="1" {{ !empty($autoEscalationSettings->aftoffice_hrs_critical_sms) && $autoEscalationSettings->aftoffice_hrs_critical_sms == 1 ? "checked='checked'" : '' }}> SMS
                        </td>
                    </tr>
					</table>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <input type="submit" id="load-allocation" name="load-allocation" class="btn btn-primary pull-right" value="Submit">
                    </div>
                    <!-- /.box-footer -->
                 </div>
                </form>
            </div>
        </div>
		<div class="col-md-6">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-anchor pull-right"></i>
                       <h3 class="box-title">Notify Managers on unresolved tickets</h3>
                </div>
                <form class="form-horizontal" id="report_form" method="POST" action="{{!empty($unresolved_tickets_settings->id) ? '/help_desk/unresolved_tickets/'.$unresolved_tickets_settings->id : '/help_desk/unresolved_tickets'}}">
					<input type="hidden" name="helpdesk_id" id="helpdesk_id" value="{{ $serviceID }}"/>                   
					{{ csrf_field() }}
                    <div class="box-body">
						<table class="table table-bordered">
							<tr>
								<th style="width: 10px"></th>
								<th>Hours</th>
								<th>Office Hours Only</th>
								 <th>Office Hours</th>
								   <th>After Hours </th>
								<th style="width: 40px"></th>
							</tr>
							<tr id="modules-list">
								<td>Low </td>
								<td><input type="text" size="2" name="tickets_low" value="{{!empty($unresolved_tickets_settings->tickets_low) ? $unresolved_tickets_settings->tickets_low : '' }}"></td>
								<td style="text-align:center;"><input type="checkbox" name="low_ah" value="1" {{ !empty($unresolved_tickets_settings->low_ah) && $unresolved_tickets_settings->low_ah == 1 ? "checked='checked'" : '' }}></td>
								<td>
									<input type="checkbox" name="esc_low_email" value="1" {{ !empty($unresolved_tickets_settings->esc_low_email) && $unresolved_tickets_settings->esc_low_email == 1 ? "checked='checked'" : '' }}> Email
									<input type="checkbox" name="esc_low_sms" value="1" {{ !empty($unresolved_tickets_settings->esc_low_sms) && $unresolved_tickets_settings->esc_low_sms == 1 ? "checked='checked'" : '' }}> SMS
								</td>
								<td>
									<input type="checkbox" name="aftoffice_hrs_low_email" value="1" {{ !empty($unresolved_tickets_settings->aftoffice_hrs_low_email) && $unresolved_tickets_settings->aftoffice_hrs_low_email == 1 ? "checked='checked'" : '' }}> Email
									<input type="checkbox" name="aftoffice_hrs_low_sms" value="1" {{ !empty($unresolved_tickets_settings->aftoffice_hrs_low_sms) && $unresolved_tickets_settings->aftoffice_hrs_low_sms == 1 ? "checked='checked'" : '' }}> SMS
								</td>
							</tr>
							<tr id="modules-list">
								<td>Normal </td>
								<td><input type="text" size="2" name="tickets_normal" value="{{!empty($unresolved_tickets_settings->tickets_normal) ? $unresolved_tickets_settings->tickets_normal : '' }}"></td>
								<td style="text-align:center;"><input type="checkbox" name="normal_oficehrs" value="1" {{ !empty($unresolved_tickets_settings->normal_oficehrs) && $unresolved_tickets_settings->normal_oficehrs == 1 ? "checked='checked'" : '' }}></td>
								<td>       
									<input type="checkbox" name="office_hrs_normal_email" value="1" {{ !empty($unresolved_tickets_settings->office_hrs_normal_email) && $unresolved_tickets_settings->office_hrs_normal_email == 1 ? "checked='checked'" : '' }}> Email
									<input type="checkbox" name="office_hrs_normal_sms" value="1" {{ !empty($unresolved_tickets_settings->office_hrs_normal_sms) && $unresolved_tickets_settings->office_hrs_normal_sms == 1 ? "checked='checked'" : '' }}> SMS
								</td>
								<td>
									<input type="checkbox" name="aftoffice_hrs_nomal_email" value="1" {{ !empty($unresolved_tickets_settings->aftoffice_hrs_nomal_email) && $unresolved_tickets_settings->aftoffice_hrs_nomal_email == 1 ? "checked='checked'" : '' }}> Email
									<input type="checkbox" name="aftoffice_hrs_nomal_sms" value="1" {{ !empty($unresolved_tickets_settings->aftoffice_hrs_nomal_sms) && $unresolved_tickets_settings->aftoffice_hrs_nomal_sms == 1 ? "checked='checked'" : '' }}> SMS
								</td>
							</tr>
							<tr id="modules-list">
								<td>High </td>
								<td><input type="text" size="2" name="tickets_high" value="{{!empty($unresolved_tickets_settings->tickets_high) ? $unresolved_tickets_settings->tickets_high : '' }}"></td>
								<td style="text-align:center;"><input type="checkbox" name="high_oficehrs" value="1" {{ !empty($unresolved_tickets_settings->high_oficehrs) && $unresolved_tickets_settings->high_oficehrs == 1 ? "checked='checked'" : '' }}></td>
								<td>
									<input type="checkbox" name="office_hrs_high_email" value="1" {{ !empty($unresolved_tickets_settings->office_hrs_high_email) && $unresolved_tickets_settings->office_hrs_high_email == 1 ? "checked='checked'" : '' }}> Email
									<input type="checkbox" name="office_hrs_high_sms" value="1" {{ !empty($unresolved_tickets_settings->office_hrs_high_sms) && $unresolved_tickets_settings->office_hrs_high_sms == 1 ? "checked='checked'" : '' }}> SMS
								</td>
								<td>
									<input type="checkbox" name="aftoffice_hrs_high_email" value="1" {{ !empty($unresolved_tickets_settings->aftoffice_hrs_high_email) && $unresolved_tickets_settings->aftoffice_hrs_high_email == 1 ? "checked='checked'" : '' }}> Email
									<input type="checkbox" name="aftoffice_hrs_high_sms" value="1" {{ !empty($unresolved_tickets_settings->aftoffice_hrs_high_sms) && $unresolved_tickets_settings->aftoffice_hrs_high_sms == 1 ? "checked='checked'" : '' }}> SMS
								</td>
							</tr>
							<tr id="modules-list">
								<td>Critical </td>
								<td><input type="text" size="2" name="tickets_critical" value="{{!empty($unresolved_tickets_settings->tickets_critical) ? $unresolved_tickets_settings->tickets_critical : '' }}"></td>
								<td style="text-align:center;"><input type="checkbox" name="critical_oficehrs" value="1" {{ !empty($unresolved_tickets_settings->critical_oficehrs) && $unresolved_tickets_settings->critical_oficehrs == 1 ? "checked='checked'" : '' }}></td>
								<td>
									<input type="checkbox" name="office_hrs_critical_email" value="1" {{ !empty($unresolved_tickets_settings->office_hrs_critical_email) && $unresolved_tickets_settings->office_hrs_critical_email == 1 ? "checked='checked'" : '' }}> Email
									<input type="checkbox" name="office_hrs_critical_sms" value="1" {{ !empty($unresolved_tickets_settings->office_hrs_critical_sms) && $unresolved_tickets_settings->office_hrs_critical_sms == 1 ? "checked='checked'" : '' }}> SMS

								</td>
								<td>
									<input type="checkbox" name="aftoffice_hrs_critical_email" value="1" {{ !empty($unresolved_tickets_settings->aftoffice_hrs_critical_email) && $unresolved_tickets_settings->aftoffice_hrs_critical_email == 1 ? "checked='checked'" : '' }}> Email
									<input type="checkbox" name="aftoffice_hrs_critical_sms" value="1" {{ !empty($unresolved_tickets_settings->aftoffice_hrs_critical_sms) && $unresolved_tickets_settings->aftoffice_hrs_critical_sms == 1 ? "checked='checked'" : '' }}> SMS
								</td>
							</tr>
						</table>
						<!-- /.box-body -->
						<div class="box-footer">
							<input type="submit" id="load-allocation" name="load-allocation" class="btn btn-primary pull-right" value="Submit">
						</div>
					</div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-anchor pull-right"></i>
                    <h3 class="box-title">Auto-responder messages</h3>
                </div>
                <form class="form-horizontal" id="report_form" method="POST" action="{{!empty($autoRensponder->id) ? '/help_desk/auto_responder_messages/'.$autoRensponder->id : '/help_desk/auto_responder_messages'}}">
                    {{ csrf_field() }}
                    <div class="box-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-ban"></i> Invalid Input Data!</h4>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="form-group notes-field{{ $errors->has('responder_messages') ? ' has-error' : '' }}">
                           <label for="days" class="col-sm-2 control-label">Auto-responder messages</label>
                            <div class="col-sm-10">
                               <div class="input-group">
                                    <div class="input-group-addon">
                                      <i class="fa fa-ticket"></i>
                                    </div>
                                   
                                    <textarea class="form-control" id="responder_messages" name="responder_messages" placeholder="Message sent when ticket completion has been requested:" rows="4">{{ !empty($autoRensponder->responder_messages) ? $autoRensponder->responder_messages: '' }}</textarea>
                                </div>
                            </div>
                        </div>
						<div class="form-group notes-field{{ $errors->has('response_emails') ? ' has-error' : '' }}">
							<label for="days" class="col-sm-2 control-label">Header for response emails sent from the helpdesk:</label>
                            <div class="col-sm-10">
                               <div class="input-group">
                                    <div class="input-group-addon">
                                      <i class="fa fa-ticket"></i>
                                    </div>
                                    <textarea class="form-control" id="response_emails" name="response_emails" placeholder="Message sent when ticket completion has been requested:" rows="4">{{ !empty($autoRensponder->response_emails) ? $autoRensponder->response_emails : '' }} </textarea>
                                </div>
                            </div>
						</div>
                        <div class="form-group notes-field{{ $errors->has('ticket_completion_req') ? ' has-error' : '' }}">
							<label for="days" class="col-sm-2 control-label">Message sent when ticket completion has been requested:</label>
							<div class="col-sm-10">
                               <div class="input-group">
                                    <div class="input-group-addon">
                                       <i class="fa fa-ticket"></i>
                                    </div>
                                     <textarea class="form-control" id="ticket_completion_req" name="ticket_completion_req" placeholder="Message sent when ticket completion has been requested:" rows="4">{{ !empty($autoRensponder->ticket_completion_req) ? $autoRensponder->ticket_completion_req : '' }}</textarea>
                                </div>
                            </div>
                        </div>
						<div class="form-group notes-field{{ $errors->has('ticket_completed') ? ' has-error' : '' }}">
							<label for="days" class="col-sm-2 control-label">Message sent when ticket has been completed:</label>
							<div class="col-sm-10">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-ticket"></i>
									</div>
									<textarea class="form-control" id="ticket_completed" name="ticket_completed" placeholder="Message sent when ticket completion has been requested:" rows="4">{{ !empty($autoRensponder->ticket_completed) ? $autoRensponder->ticket_completed : '' }}</textarea>
								</div>
							</div>
						</div>
						<input id="invisible_id" name="helpdesk_id" type="hidden" value="{{ $serviceID }}">
						<!-- /.box-body -->
						<div class="box-footer">
							<input type="submit" id="load-allocation"  class="btn btn-primary pull-right" value="Submit">
						</div>
						<!-- /.box-footer -->
					</div>
                </form>
            </div>
        </div>
		<div class="col-md-6">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-anchor pull-right"></i>
                    <h3 class="box-title"> System Email Setup</h3>
                </div>
				<form class="form-horizontal" id="report_form" method="POST" action="{{!empty($emailSettings->id) ? '/help_desk/email_setup/'.$emailSettings->id : '/help_desk/email_setup'}}">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <td>Auto-process Emails:</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <td>
                                     <div class="sms"><input type="checkbox" name="auto_processemails" value="1"  {{ !empty($emailSettings->auto_processemails) && $emailSettings->auto_processemails == 1 ? "checked='checked'" : '' }}> </div>
                                      
                                    </td> 
                                </td>
                            </tr>
                            <tr>
                                <td>Only process replies:</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <td>
                                     <div class="sms"><input type="checkbox" name="anly_processreplies" value="1" {{ !empty($emailSettings->anly_processreplies) && $emailSettings->anly_processreplies == 1 ? "checked='checked'" : '' }}> </div>

                                    </td> 
                                </td>
                            </tr>
                            <tr>
                                <td>Email address:</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <td>
                                       <input type="email" id ="email_address" class="form-control pull-left" name="email_address" value="{{!empty($emailSettings->email_address) ? $emailSettings->email_address : ' '}}" >
                                    </td> 
                                </td>
                            </tr>
							<tr>
								<td>Server Name:</td>
								<td style="text-align: center; vertical-align: middle;">
									<td>
									   <input type="text" id ="server_name" class="form-control pull-left" name="server_name" value="{{!empty($emailSettings->server_name) ? $emailSettings->server_name : ' '}}" >
									</td> 
								</td>
							</tr>
							<tr>
								<td>Server Type:</td>
								<td>
									<div class="radio">
										<label><input type="radio" name="preferred_communication_method" id="IMAP" value="1"  {{ !empty($emailSettings->preferred_communication_method) && $emailSettings->preferred_communication_method == 1 ? "checked='checked'" : '' }}>IMAP/Exchange</label>
										<br>
										<label><input type="radio" name="preferred_communication_method" id="POP3" value="2"  {{ !empty($emailSettings->preferred_communication_method) && $emailSettings->preferred_communication_method == 2 ? "checked='checked'" : '' }}>POP3</label>
									</div>
								</td>
							</tr>
							<tr>
								<td>Server Port:</td>
								<td style="text-align: center; vertical-align: middle;">
									<td>
									   <input type="number" id ="server_port" class="form-control pull-left" name="server_port" value="{{!empty($emailSettings->server_port) ? $emailSettings->server_port : ''}}" placeholder="Default - POP3: 110; IMAP/Exchange: 143">
									</td> 
								</td>
							</tr>
							<tr>
								<td>Username:</td>
								<td style="text-align: center; vertical-align: middle;">
									<td>
									   <input type="text" id ="username" class="form-control pull-left" name="username" value="{{!empty($emailSettings->username) ? $emailSettings->username : ' '}}" placeholder="Default - POP3: 110; IMAP/Exchange: 143">
									</td> 
								</td>
							</tr>
							<tr>
								<td>Password:</td>
								<td style="text-align: center; vertical-align: middle;">
									<td>
									   <input type="text" id ="password" class="form-control pull-left" name="password" value="{{!empty($emailSettings->password) ? $emailSettings->password : ' '}}" placeholder="Default - POP3: 110; IMAP/Exchange: 143">
									</td> 
								</td>
							</tr>
							<tr>
								<td>Signature Start String:</td>
								<td style="text-align: center; vertical-align: middle;">
									<td>
									   <input type="text" id ="Signature_start" class="form-control pull-left" name="Signature_start" value="{{!empty($emailSettings->Signature_start) ? $emailSettings->Signature_start : ' '}}" placeholder="Everything below & including this string will be removed.">
									</td> 
								</td>
							</tr>
								<!--  <input type="hidden" class="checkbox selectall"  name="helpdesk_id" value="{{ $serviceID }}"> -->
							<input id="invisible_id" name="helpdesk_id" type="hidden" value="{{ $serviceID }}">
						</table>                         
						<!-- /.box-body -->
						<div class="box-footer">
							<input type="submit" id="load-allocation"  class="btn btn-primary pull-right"  >
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
    <!--  -->
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"> Operators for {{ $serviceName }}</h3>
                </div>
                 {{ csrf_field() }}
                 {{ method_field('PATCH') }}
                <!-- /.box-header -->
                <div class="box-body">
					<table class="table table-bordered">
						<tr><th style="width: 10px">#</th>
							<th>Helpdesk</th>
							<th>Operator</th>
							<th></th>
							<th style="width: 40px"></th>
						</tr>
						@if (count($operators) > 0)
							@foreach($operators as $operator)
								<tr id="jobtitles-list">
									<td nowrap>
									<button type="button" id="edit_job_title" class="btn btn-primary  btn-xs" data-toggle="modal" 
									data-target="#edit-operators-modal" data-id="{{ $operator->id }}" data-operator_id="{{ $operator->operator_id }}""><i class="fa fa-pencil-square-o"></i> Edit</button>
									</td>
									<td>{{ $serviceName }} </td>
									<td>{{ ( $operator->firstname . ' ' . $operator->surname) }} </td>
									<td nowrap>
										<button type="button" id="view_job_title" class="btn {{ (!empty($operator->status) && $operator->status == 1) ? "btn-danger" : "btn-success" }} btn-xs" onclick="postData({{$operator->id}}, 'OPeratoractdeac');"><i class="fa {{ (!empty($operator->status) && $operator->status == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($operator->status) && $operator->status == 1) ? "De-Activate" : "Activate"}}</button>
									</td>
								</tr>
							@endforeach
						@else
							<tr id="jobtitles-list">
								<td colspan="6">
									<div class="alert alert-danger alert-dismissable">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
										No operator to display, please start by adding a new operator.
									</div>
								</td>
							</tr>
						@endif
                    </table>
                </div>
                <div class="box-footer">
                    <button type="button" id="add-operators" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-operators-modal">Add Operator</button>
                </div>
            </div>
             <!-- Include add new prime rate modal -->
        @include('help_desk.partials.add_operators')
        @include('help_desk.partials.edit_operators')
        </div>
		<div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"> Add Help Desk Admin</h3>
                </div>
                 {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                <!-- /.box-header -->
                <div class="box-body">
					<table class="table table-bordered">
						 <tr>
							<th style="width: 10px">#</th>
							 <th>Helpdesk</th>
							 <th>Administrator</th>
							 <th></th>
							 <th style="width: 40px"></th>
						 </tr>
						@if (count($HelpdeskAdmin) > 0)
							@foreach($HelpdeskAdmin as $admin)
								<tr id="jobtitles-list">
									<td nowrap>
										<button type="button" id="edit_job_title" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-administrator-modal" data-id="{{ $admin->id }}" data-admin_id="{{ $admin->admin_id }}"><i class="fa fa-pencil-square-o"></i> Edit</button>
									</td>
									<td>{{ $serviceName }} </td>
									<td>{{ ( $admin->firstname . ' ' . $admin->surname) }} </td>
									<td nowrap>
										<button type="button" id="view_job_title" class="btn {{ (!empty($admin->status) && $admin->status == 1) ? "btn-danger" : "btn-success" }} btn-xs" onclick="postData({{$admin->id}}, 'actdeac');"><i class="fa {{ (!empty($admin->status) && $admin->status == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($admin->status) && $admin->status == 1) ? "De-Activate" : "Activate"}}</button>
									</td>
								</tr>
							@endforeach
						@else
							<tr id="jobtitles-list">
								<td colspan="6">
									<div class="alert alert-danger alert-dismissable">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
										No administrator to display, please start by adding a new administrator.
									</div>
								</td>
							</tr>
						@endif
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
					<button type="button" id="back" class="btn btn-primary"><i class="fa fa-arrow-left"></i>
						Back
					</button>
                     <button type="button" id="adminas" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-administrator-modal">Add Administrator</button>
                </div>
            </div>
        @include('help_desk.partials.add_admin')
        @include('help_desk.partials.edit_admin')
        </div>
    </div>
    @endsection
    @section('page_script')
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script> 
    <script>

        function postData(id , data ){   
            if(data == 'OPeratoractdeac') location.href = "/helpdesk/operatorAct/" + id;
            else if(data == 'actdeac') location.href = "/helpdesk/help_deskAdmin/" + id; 
           
        }

        $(function () {
            //Cancel button click event
            document.getElementById("back").onclick = function () {
                location.href = "/helpdesk/setup";
            };

            //Tooltip
            $('[data-toggle="tooltip"]').tooltip();

            //Vertically center modals on page
            function reposition() {
                var modal = $(this),
                        dialog = modal.find('.modal-dialog');
                modal.css('display', 'block');

                // Dividing by two centers the modal exactly, but dividing by three
                // or four works better for larger screens.
                dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 2));
            }
            // Reposition when a modal is shown
            $('.modal').on('show.bs.modal', reposition);
            // Reposition when the window is resized
            $(window).on('resize', function() {
                $('.modal:visible').each(reposition);
            });

            //Show success action modal
            $('#success-action-modal').modal('show');


                $('#time_from').datetimepicker({
                    format: 'HH:mm:ss'
                });
                $('#time_to').datetimepicker({
                    format: 'HH:mm:ss'
                 });

                  //Post module form to server using ajax (ADD)
            $('#add_operator').on('click', function() {
                //console.log('strUrl');
                var strUrl = '/help_desk/operator/add/{{ $serviceID }}';
                var modalID = 'add-operators-modal';
                var objData = {
                    operator_id: $('#'+modalID).find('#operator_id').val(),
                    // description: $('#'+modalID).find('#description').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'add-operators';
                var redirectUrl = '/help_desk/service/{{ $serviceID }}';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The Operator has been Added successfully.';
                //var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });


                  //Post module form to server using ajax (ADD)
            $('#add_admin').on('click', function() {
                //console.log('strUrl');
                 var strUrl = '/help_desk/admin/add/{{ $serviceID }}';
                var modalID = 'add-administrator-modal';
                var objData = {
                    admin_id: $('#'+modalID).find('#admin_id').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'adminas';
               var redirectUrl = '/help_desk/service/{{ $serviceID }}';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The service has been Added successfully.';
                //var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });


              var serviceID;
            $('#edit-service-modal').on('show.bs.modal', function (e) {
                    //console.log('kjhsjs');
                var btnEdit = $(e.relatedTarget);
                serviceID = btnEdit.data('id');
                var name = btnEdit.data('name');
                var description = btnEdit.data('description');
                //var employeeName = btnEdit.data('employeename');
                var modal = $(this);
                modal.find('#name').val(name);
                modal.find('#description').val(description);

            });
			$('#edit-operators-modal').on('show.bs.modal', function (e) {
                //console.log('kjhsjs');
                var btnEdit = $(e.relatedTarget);
                serviceID = btnEdit.data('id');
                var operatorID = btnEdit.data('operator_id');
                //var employeeName = btnEdit.data('employeename');
                var modal = $(this);
				modal.find('select#operator_id').val(operatorID);

            });
			$('#edit-administrator-modal').on('show.bs.modal', function (e) {
                //console.log('kjhsjs');
                var btnEdit = $(e.relatedTarget);
                serviceID = btnEdit.data('id');
                var adminID = btnEdit.data('admin_id');
                //var employeeName = btnEdit.data('employeename');
                var modal = $(this);
				modal.find('select#admin_id').val(adminID);

            });
            $('#update-service').on('click', function () {
                var strUrl = '/help_desk/system/adit/' + serviceID;
                var modalID = 'edit-service-modal';
                var objData = {
                    name: $('#'+modalID).find('#name').val(),
                    description: $('#'+modalID).find('#description').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'edit_job_title';
                var redirectUrl = '/helpdesk/setup';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The service has been updated successfully.';
                var Method = 'PATCH';
				modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
            });
			
			$('#update-operator').on('click', function () {
                var strUrl = '/help_desk/operator/edit/' + serviceID;
                var modalID = 'edit-operators-modal';
                var objData = {
                    operator_id: $('#'+modalID).find('#operator_id').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'update-operator';
                var redirectUrl = '/help_desk/service/' + {{$serviceID}};
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'Operator has been updated successfully.';
                var Method = 'PATCH';
				modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
            });
			
			$('#update-admin').on('click', function () {
                var strUrl = '/help_desk/admin/edit/' + serviceID;
                var modalID = 'edit-administrator-modal';
                var objData = {
                    admin_id: $('#'+modalID).find('#admin_id').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'update-admin';
                var redirectUrl = '/help_desk/service/' + {{$serviceID}};
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'Aperator has been updated successfully.';
                var Method = 'PATCH';
				modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
            });
        });
    </script>
@endsection
