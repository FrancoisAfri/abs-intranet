@extends('layouts.main_layout')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Leave Types Set Up</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
				<table class="table table-striped">
									<tr>
                                        <th style="width: 10px"></th>
                                        <th>Type</th>
                                        <th>5-Day Employees</th>
                                        <th>5-Day Employee Max</th>
                                        <th>6-Day Employees</th>
                                        <th>6-Day Employee Max</th>
                                        <th>Shift Employees</th>
                                        <th>Shift Employee Max</th>
                                        <th style="width: 40px"></th>
                                    </tr>
                    @if (count($leaveTypes) > 0)
                    @foreach($leaveTypes as $leaveType)
                    <tr id="modules-list">
                        <td nowrap>
						<button type="button" id="edit_leave" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-leave_taken-modal" data-id="{{ $leaveType->id }}" data-name="{{ $leaveType->name }}" data-day5min="{{ ($profile = $leaveType->leave_profle->where('id', 2)->first()) ? $profile->pivot->min : '' }}"  data-day5max="{{ ($profile = $leaveType->leave_profle->where('id', 2)->first()) ? $profile->pivot->max : '' }}" data-day6min="{{ ($profile = $leaveType->leave_profle->where('id', 3)->first()) ? $profile->pivot->min : '' }}" data-day6max="{{ ($profile = $leaveType->leave_profle->where('id', 3)->first()) ? $profile->pivot->max : '' }}" data-shiftmin="{{ ($profile = $leaveType->leave_profle->where('id', 4)->first()) ? $profile->pivot->min : '' }}" data-shiftmax="{{ ($profile = $leaveType->leave_profle->where('id', 4)->first()) ? $profile->pivot->max : '' }}"> <i class="fa fa-pencil-square-o"></i> Edit</button>
                        </td>
                        <td align="center">{{ $leaveType->name}}</td>
                        <td align="center"> {{ ($profile = $leaveType->leave_profle->where('id', 2)->first()) ? $profile->pivot->min : '' }} </td>
                        <td align="center"> {{ ($profile = $leaveType->leave_profle->where('id', 2)->first()) ? $profile->pivot->max : '' }} </td>
                        <td align="center"> {{ ($profile = $leaveType->leave_profle->where('id', 3)->first()) ? $profile->pivot->min : '' }} </td>
                        <td align="center"> {{ ($profile = $leaveType->leave_profle->where('id', 3)->first()) ? $profile->pivot->max : '' }} </td>
                        <td align="center"> {{ ($profile = $leaveType->leave_profle->where('id', 4)->first()) ? $profile->pivot->min : '' }} </td>
                        <td align="center"> {{ ($profile = $leaveType->leave_profle->where('id', 4)->first()) ? $profile->pivot->max : '' }} </td>
                    </tr> 
                    @endforeach 
                    @else
                    <tr id="modules-list">
                        <td colspan="5">
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No leave types to display, please start by adding a new leave type. </div>
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
            <!-- /.box-body -->
            <div class="modal-footer"> </div>
        </div>
    </div>
    <!-- Include add new prime rate modal -->
    @include('leave.partials.edit_leave_type_days')
    @include('leave.partials.edit_leavetype') 
</div>
        <!-- <!-- Leave CreditSettings -->
<div class="row">
    <form class="form-horizontal" method="post" action="/leave/setup/{{ $leave_configuration->id }}">
            {{ csrf_field() }}
		<div class="col-sm-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Leave Credit</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
					</div>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
				   <table class="table table-bordered">
						<div class="form-group">
							<tr>
							  <td style="width: 10px"></td>
								<td >Allow Annual Leave Credit </td>
								<td style="text-align: center; vertical-align: middle;">
									<input type="hidden" name="allow_annualLeave_credit" value="0">
									<input type="checkbox" name="allow_annualLeave_credit" value="1" {{ $leave_configuration->allow_annualLeave_credit === 1 ?  'checked ="checked"' : 0 }}>
								</td>
							</tr>
						</div>
						<div class="form-group">
							<tr>
							   <td style="width: 10px"></td>
								<td>Allow Sick Leave Credit</td>
								<td style="text-align: center; vertical-align: middle;">
									<input type="hidden" name="allow_sickLeave_credit" value="0">
									<input   type="checkbox" name="allow_sickLeave_credit" value="1" {{ $leave_configuration->allow_sickLeave_credit === 1 ? 'checked ="checked"' : 0 }}>
								</td>
							</tr>
						</div>
						<div class="form-group">
							<tr>
							<td style="width: 10px"></td>
								<td>Show non-employees in Leave Module</td>
								
								<td style="text-align: center; vertical-align: middle;">
									<input type="hidden" name="show_non_employees_in_leave_Module" value="0">
									<input   type="checkbox" name="show_non_employees_in_leave_Module" value="1" {{ $leave_configuration->show_non_employees_in_leave_Module === 1 ? 'checked ="checked"' : 0 }}>
								</td>
							</tr>
						</div>
					   <div class="form-group">
							<tr>
							<td style="width: 10px"></td>
							   <td>Numbe of Sick negative leave Days</td>
								<td >
									 <label for="path" class="control-label"></label>
									<input type="text" class="form-control" id="allow_sick_negative_days" name="allow_sick_negative_days" value="{{ !empty($leave_configuration->allow_sick_negative_days) ? $leave_configuration->allow_sick_negative_days : '' }}" placeholder="Enter days"required >
								</td>
							</tr>
						</div> 
						<div class="form-group">
							<tr>
								<td style="width: 10px"></td>
								<td>Numbe of Annual negative leave Days</td>
								<td>
									<label for="path" class="control-label"></label>
									<input type="text" class="form-control" id="allow_annual_negative_days" name="allow_annual_negative_days" value="{{ !empty($leave_configuration->allow_annual_negative_days) ? $leave_configuration->allow_annual_negative_days : '' }}" placeholder="Enter days"required >
								</td>
							</tr>
						</div>
					</table>
				</div>
				<!-- /.box-body -->
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary"><i class="fa fa-database"></i> save leave credit settings</button> 
				</div>
			</div>
		</div>
	</form>
@include('leave.partials.edit_annual_days')
@include('leave.partials.edit_sick_days') 
{{--Approval Settings--}}
<form class="form-horizontal" method="post" action="/leave/setup/{{ $leave_configuration->id }}"> 
 {{ csrf_field() }}
    <div class="col-sm-6">
        <div class="box box-primary">
			<div class="box-header with-border">
					<h3 class="box-title">Approval Settings</h3>
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
				</div>
			</div>
            <!-- /.box-header -->
            <div class="box-body">
                <table class="table table-bordered">
					<div class="form-group">
						<tr>
							<td>Require Manager's approval</td>
							<td style="text-align: center; vertical-align: middle;">
								<input type="hidden" name="require_managers_approval" value="0">
								<input   type="checkbox" name="require_managers_approval" value="1" {{ $leave_configuration->require_managers_approval === 1 ? 'checked ="checked"' : 0 }}>
							</td>
						</tr>
					</div>
					<div class="form-group">
						<tr>
						   <td>Require Department Head approval</td>
							<td style="text-align: center; vertical-align: middle;">
								<input type="hidden" name="require_department_head_approval" value="0">
								<input   type="checkbox" name="require_department_head_approval" value="1" {{ $leave_configuration->require_department_head_approval === 1 ? 'checked ="checked"' : 0 }}>
							</td>
						</tr>
					</div>
					<div class="form-group">
						<tr>
						<td>Require HR approval</td>
							<td style="text-align: center; vertical-align: middle;">
								<input type="hidden" name="require_hr_approval" value="0">
								<input  type="checkbox" name="require_hr_approval" value="1" {{ $leave_configuration->require_hr_approval === 1 ? 'checked ="checked"' : 0 }}>
							</td>
						</tr>
					</div>
					<div class="form-group">
						<tr>
						<td>Require Payroll approval</td>
							 <td style="text-align: center; vertical-align: middle;">
								 <input type="hidden" name="require_payroll_approval" value="0">
								<input  type="checkbox" name="require_payroll_approval" value="1" {{ $leave_configuration->require_payroll_approval === 1 ? 'checked ="checked"' : 0 }}>
							</td>
						</tr>
					</div>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="modal-footer">

                <button type="submit" class="btn btn-primary"><i class="fa fa-database"></i> save approval settings</button>
            </div>

        </div>
    </div>
</form>
</div>
    {{--Notification Settings--}}
<div class="row">
 <form class="form-horizontal" method="post" action="/leave/setup/{{ $leave_configuration->id }}">
    {{ csrf_field() }}
	<div class="col-sm-6">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Notification Settings</h3>

				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
				</div>
			</div>
			<div class="box-body">
				<table class="table table-bordered">
					 <div class="form-group">
					<tr>
						<td>Notify HR with Application</td>
						<td style="text-align: center; vertical-align: middle;">
							<input type="hidden" name="notify_hr_with_application" value="0">
							<input  type="checkbox" name="notify_hr_with_application" value="1" {{ $leave_configuration->notify_hr_with_application === 1 ? 'checked ="checked"' : 0 }}>
						</td>
					  </tr>
					 </div>
					 
				<div class="form-group">
					<tr>
						<td>Preferred Communication Method</td>
						<td>
							<div class="radio">
								<label><input type="radio" name="preferred_communication_method" id="Email" value="1" checked>Email</label>
								<br>
								<label><input type="radio" name="preferred_communication_method" id="SMS" value="2" checked>SMS</label>
								<br>
								<label><input type="radio" name="preferred_communication_method" id="3" value="3" checked>Based on Employee</label>
							</div>
						</td>
						</tr>
					</div>
				</table>
			</div>
            <!-- Include add expenditure and add income modals -->
            <div class="modal-footer">
				<button type="submit" class="btn btn-primary"><i class="fa fa-database"></i> save notifications settings</button>  
            </div>
        </div>
    </div>
    
</form>
<!-- General Settings -->
     <form class="form-horizontal" method="post" action="/leave/setup/{{ $leave_configuration->id }}"> 
  
         {{ csrf_field() }}
            <div class="col-sm-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">General Settings</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                   
                         <div class="box-body">
                         <table class="table table-bordered">
                             
                            <div class="form-group">
                                <tr>
                                <td>Number of Days Until Escalation</td>
                                    <td>
                                        <label for="path" class="control-label"></label>
                                <input type="text" class="form-control" id="mumber_of_days_until_escalation" name="mumber_of_days_until_escalation" value="{{ !empty($leave_configuration->mumber_of_days_until_escalation) ? $leave_configuration->mumber_of_days_until_escalation : '' }}"placeholder="Enter  days"  >
                                    </td>
                              </tr> 
                            </div>
                                
                            <div class="form-group">
                              <tr>
                                <td>Document compulsory on Study leave application</td>
                                  <td style="text-align: center; vertical-align: middle;">
                                      <input type="hidden" name="document_compulsory_on_Study_leave_application" value="0">
                                      <input  type="checkbox" name="document_compulsory_on_Study_leave_application" value="1" {{ $leave_configuration->document_compulsory_on_Study_leave_application === 1 ? 'checked ="checked"' : 0 }}>
                                  </td>
                              </tr>
                            </div>
                                
                            <div class="form-group">
                                <tr>
                                    <td>Document compulsory when two sick leave within 8_weeks</td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            <input type="hidden" name="document_compulsory_when_two_sick_leave_8_weeks" value="0">
                                             <input   type="checkbox" name="document_compulsory_when_two_sick_leave_8_weeks" value="1" {{ $leave_configuration->document_compulsory_when_two_sick_leave_8_weeks === 1 ? 'checked ="checked"' : 0 }}>
                                        </td>
                                            </tr>
                                        </div>

                                     </div>
                                </table>
                                </div>
                         <div class="modal-footer">
                 <button type="submit" class="btn btn-primary"><i class="fa fa-database"></i> save notifications settings</button>  
                    </div>
            </div>
        </div>
    </form>
</div>




     @endsection
<!-- Ajax form submit -->
@section('page_script')
<script src="/custom_components/js/modal_ajax_submit.js"></script>
<script>
    function postData(id, data) {
        //if (data == 'actdeac') location.href = "/leave/types/activate" + id;
        if (data == 'ribbons') location.href = "/leave/ribbons/" + id;
        else if (data == 'edit') location.href = "/leave/leave_edit/" + id;
        else if (data == 'actdeac') location.href = "/leave/setup/" + id; //leave_type_edit
        //  else if (data == 'cu_actdeac') location.href = "/leave/custom/leave_type_edit/" + id;
        //		 	else if (data == 'access')
        //		 		location.href = "/leave/module_access/" + id;
    }
    $(function () {
        var moduleId;
        //Tooltip
        $('[data-toggle="tooltip"]').tooltip();
        //Vertically center modals on page
        function reposition() {
            var modal = $(this)
                , dialog = modal.find('.modal-dialog');
            modal.css('display', 'block');
            // Dividing by two centers the modal exactly, but dividing by three
            // or four works better for larger screens.
            dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 2));
        }
        // Reposition when a modal is shown
        $('.modal').on('show.bs.modal', reposition);
        // Reposition when the window is resized
        $(window).on('resize', function () {
            $('.modal:visible').each(reposition);
        });
     
        var leavesetupId;
        $('#edit-leave_taken-modal').on('show.bs.modal', function (e) {
            //console.log('kjhsjs');
            var btnEdit = $(e.relatedTarget);
            leavesetupId = btnEdit.data('id');
            console.log('leavesetupID: ' + leavesetupId);
            var name = btnEdit.data('name');
            var day5min = btnEdit.data('day5min');
            var day5max = btnEdit.data('day5max');
            var day6min = btnEdit.data('day6min');
            var day6max = btnEdit.data('day6max');
            var shiftmin = btnEdit.data('shiftmin');
            var shiftmax = btnEdit.data('shiftmax');
    
            // var moduleFontAwesome = btnEdit.data('font_awesome');
            var modal = $(this);
            modal.find('#name').val(name);
            modal.find('#day5min').val(day5min);
            modal.find('#day5max').val(day5max);
            modal.find('#day6min').val(day6min);
            modal.find('#day6max').val(day6max);
            modal.find('#shiftmin').val(shiftmin);
            modal.find('#shiftmax').val(shiftmax);
            //if(primeRate != null && primeRate != '' && primeRate > 0) {
            //    modal.find('#prime_rate').val(primeRate.toFixed(2));
            //}
        });
        // pass module data to the custom leave  -edit module modal
        //****leave type post
        $('#update-leave_taken').on('click', function () {
            var strUrl = '/leave/setup/leave_type_edit/' + leavesetupId;
            var objData = {
                  day5min: $('#edit-leave_taken-modal').find('#day5min').val()
                , day5max: $('#edit-leave_taken-modal').find('#day5max').val()
                , day6min: $('#edit-leave_taken-modal').find('#day6min').val()
                , day6max: $('#edit-leave_taken-modal').find('#day6max').val()
                , shiftmin: $('#edit-leave_taken-modal').find('#shiftmin').val()
                , shiftmax: $('#edit-leave_taken-modal').find('#shiftmax').val()
                , _token: $('#edit-leave_taken-modal').find('input[name=_token]').val()
            };
            //console.log('gets here ' + JSON.stringify(objData));
            var modalID = 'edit-leave_taken-modal';
            var submitBtnID = 'update-leave_taken';
            var redirectUrl = '/leave/setup';
            var successMsgTitle = 'Changes Saved!';
            var successMsg = 'Leave days has been successfully added.';
             // var method = 'PATCH';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });                        // ----edit setup leave days ------
    });

//#leave cresdit settings 
 $('#save_leave_credit').on('click', function () {
            var strUrl = '/leave/custom/add_leave';
            var objData = {
                  hr_id: $('#add-custom-leave-modal').find('#hr_id').val()
                , number_of_days: $('#add-custom-leave-modal').find('#number_of_days').val()
                , _token: $('#add-custom-leave-modal').find('input[name=_token]').val()
            };
            var modalID = 'add-custom-leave-modal';
            var submitBtnID = 'add_custom_leave';
            var redirectUrl = '/leave/types';
            var successMsgTitle = 'Changes Saved!';
            var successMsg = 'Leave has been successfully added.';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });

                //UPDATE

      var updateNegativeID;
        $('#edit-annual-modal').on('show.bs.modal', function (e) {
            var btnEdit = $(e.relatedTarget);

            updateNegativeID = btnEdit.data('id');
            var number_of_days_annual = btnEdit.data('number_of_days_annual');
            //console.log(number_of_days_annual);
             var modal = $(this);
             modal.find('#number_of_days_annual').val(number_of_days_annual);

        });

         var updateSickID;
        $('#edit-sick-modal').on('show.bs.modal', function (e) {
            var btnEdit = $(e.relatedTarget);

            updateSickID = btnEdit.data('id');
            var number_of_days_sick = btnEdit.data('number_of_days_sick');
           // console.log(number_of_days_sick);
             var modal = $(this);
             modal.find('#number_of_days_sick').val(number_of_days_sick);

        });
  
            //SAVE

                 $('#update_annual').on('click', function () {
            var strUrl = '/leave/setup/' + '1';
            var objData = {
                  number_of_days_annual: $('#edit-annual-modal').find('#number_of_days_annual').val()
                , _token: $('#edit-annual-modal').find('input[name=_token]').val()
            };
            var modalID = 'edit-annual-modal';
            var submitBtnID = 'edit_annual';
            var redirectUrl = '/leave/setup';
            var successMsgTitle = 'Changes Saved!';
            var successMsg = 'Leave has been successfully added.';
            var formMethod = 'PATCH';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, formMethod);
        });

          $('#update-sick').on('click', function () {
            var strUrl = '/leave/setup/' + '1/' + 'sick';
            var objData = {
                  number_of_days_sick: $('#edit-sick-modal').find('#number_of_days_sick').val()
                , _token: $('#edit-sick-modal').find('input[name=_token]').val()
            };
            var modalID = 'edit-sick-modal';
            var submitBtnID = 'edit_sick';
            var redirectUrl = '/leave/setup';
            var successMsgTitle = 'Changes Saved!';
            var successMsg = 'Leave has been successfully added.';
            var formMethod = 'PATCH';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, formMethod);
        });

         
       

</script>

@endsection
