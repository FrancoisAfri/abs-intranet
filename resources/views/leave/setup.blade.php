@extends('layouts.main_layout')

@section('page_dependencies')
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="{{ asset('bower_components/AdminLTE/plugins/iCheck/square/blue.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css"/>

@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Leave Types Set Up</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
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
                                        <button type="button" id="edit_leave" class="btn btn-primary  btn-xs"
                                                data-toggle="modal" data-target="#edit-leave_taken-modal"
                                                data-id="{{ $leaveType->id }}" data-name="{{ $leaveType->name }}"
                                                data-day5min="{{ ($profile = $leaveType->leave_profle->where('id', 2)->first()) ? $profile->pivot->min : '' }}"
                                                data-day5max="{{ ($profile = $leaveType->leave_profle->where('id', 2)->first()) ? $profile->pivot->max : '' }}"
                                                data-day6min="{{ ($profile = $leaveType->leave_profle->where('id', 3)->first()) ? $profile->pivot->min : '' }}"
                                                data-day6max="{{ ($profile = $leaveType->leave_profle->where('id', 3)->first()) ? $profile->pivot->max : '' }}"
                                                data-shiftmin="{{ ($profile = $leaveType->leave_profle->where('id', 4)->first()) ? $profile->pivot->min : '' }}"
                                                data-shiftmax="{{ ($profile = $leaveType->leave_profle->where('id', 4)->first()) ? $profile->pivot->max : '' }}">
                                            <i class="fa fa-pencil-square-o"></i> Edit
                                        </button>
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
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        No leave types to display, please start by adding a new leave type.
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="modal-footer"></div>
            </div>
        </div>
        <!-- Include add new prime rate modal -->
        @include('leave.partials.edit_leave_type_days')
        @include('leave.partials.edit_leavetype')
        @include('leave.partials.edit_annual_days')
        @include('leave.partials.edit_sick_days')
    </div>
	<div class="col-md-12">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Users to receive leave notifications</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div style="overflow-X:auto;">
                    <table id=" " class="display table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th style="width: 10px; text-align: center;">#</th>
                            <th style="width: 10px; text-align: center;">Name</th>
                            <th style="width: 5px; text-align: right;">Action</th>

                            {{--                                <th style="width: 5px; text-align: center;">.</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($leaveNotificationsUsers) > 0)
                            <ul class="products-list product-list-in-box">
                                @foreach ($leaveNotificationsUsers as $key => $user)
                                    <tr id="categories-list">
                                        <td style="width: 5px; text-align: center;">{{ $loop->iteration }}</td>
                                        <td style="width: 5px; text-align: center;">{{ $user->first_name . ' ' . $user->surname ?? ''}} </td>
                                        <td style="width: 5px; text-align: right;">
                                            <form action="{{ route('leave_user_notifications.destroy', $user->userID) }}"
                                                  method="POST"
                                                  style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                                <button type="submit"
                                                        class="btn btn-xs btn-danger btn-flat delete_confirm"
                                                        data-toggle="tooltip" title='Delete'>
                                                    <i class="fa fa-trash"> Delete </i>

                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>

                    <div class="box-footer">
                        <button type="button" id="cat_module" class="btn btn-default pull-right" data-toggle="modal"
                                data-target="#add-leave-notification-modal">Add Users
                        </button>
                    </div>
                </div>

            </div>
            <!-- /.box-body -->
        </div>
        @include('leave.partials.settings.add_leave_notifications_users')
    </div>
    <div class="col-md-12">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Users to receive Absent user report</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div style="overflow-X:auto;">
                    <table id=" " class="display table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th style="width: 10px; text-align: center;">#</th>
                            <th style="width: 10px; text-align: center;">Name</th>
                            <th style="width: 5px; text-align: right;">Action</th>

                            {{--                                <th style="width: 5px; text-align: center;">.</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($managerList) > 0)
                            <ul class="products-list product-list-in-box">
                                @foreach ($managerList as $key => $manager)
                                    <tr id="categories-list">
                                        <td style="width: 5px; text-align: center;">{{ $loop->iteration }}</td>
                                        <td style="width: 5px; text-align: center;">{{ $manager->first_name . ' ' . $manager->surname ?? ''}} </td>
                                        <td style="width: 5px; text-align: right;">
                                            <form action="{{ route('manager.destroy', $manager->managerID) }}"
                                                  method="POST"
                                                  style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                                <button type="submit"
                                                        class="btn btn-xs btn-danger btn-flat delete_confirm"
                                                        data-toggle="tooltip" title='Delete'>
                                                    <i class="fa fa-trash"> Delete </i>

                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                            @endforeach

                        @endif
                        </tbody>
                    </table>

                    <div class="box-footer">
                        <button type="button" id="cat_module" class="btn btn-default pull-right" data-toggle="modal"
                                data-target="#add-managers-modal">Add Managers
                        </button>
                    </div>
                </div>

            </div>
            <!-- /.box-body -->
        </div>
        @include('leave.partials.settings.add_managers_report')
    </div>

    <!-- Include add new prime rate modal -->

    <div class="col-md-12">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Users Exempted</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div style="overflow-X:auto;">
                    <table id=" " class="display table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th style="width: 10px; text-align: center;">#</th>
                            <th style="width: 10px; text-align: center;">Name</th>
                            <th style="width: 5px; text-align: center;">Action</th>

                            {{--                                <th style="width: 5px; text-align: center;">.</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($exemptedUsers) > 0)
                            <ul class="products-list product-list-in-box">
                                @foreach ($exemptedUsers as $key => $exempted)
                                    <tr id="categories-list">
                                        <td></td>
                                        <td style="width: 5px; text-align: left;">{{ $exempted->first_name . ' ' . $exempted->surname ?? ''}} </td>
                                        <td style="width: 5px; text-align: right;">
                                            <form action="{{ route('exempted.destroy', $exempted->exemp_id) }}"
                                                  method="POST"
                                                  style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                                <button type="submit"
                                                        class="btn btn-xs btn-danger btn-flat delete_confirm"
                                                        data-toggle="tooltip" title='Delete'>
                                                    <i class="fa fa-trash"> Delete </i>

                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                            @endforeach

                        @endif
                        </tbody>
                    </table>

                    <div class="box-footer">
                        <button type="button" id="cat_module" class="btn btn-default pull-right" data-toggle="modal"
                                data-target="#add-exempted-modal">Add Exempted Users
                        </button>
                    </div>
                </div>

            </div>
            <!-- /.box-body -->
        </div>
        @include('leave.partials.settings.add_exempted_users')
    </div>



    <div class="col-sm-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Leave Credit</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                class="fa fa-remove"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <form class="form-horizontal" method="post" action="/leave/setup/{{ $leave_configuration->id }}">
                {{ csrf_field() }}
                <div class="box-body">
                    <table class="table table-bordered">
                        <div class="form-group">
                            <tr>
                                <td style="width: 10px"></td>
                                <td>Allow Annual Leave Credit</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <input type="hidden" name="allow_annualLeave_credit" value="0">
                                    <input type="checkbox" name="allow_annualLeave_credit"
                                           value="1" {{ $leave_configuration->allow_annualLeave_credit === 1 ?  'checked ="checked"' : 0 }}>
                                </td>
                            </tr>
                        </div>
                        <div class="form-group">
                            <tr>
                                <td style="width: 10px"></td>
                                <td>Allow Sick Leave Credit</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <input type="hidden" name="allow_sickLeave_credit" value="0">
                                    <input type="checkbox" name="allow_sickLeave_credit"
                                           value="1" {{ $leave_configuration->allow_sickLeave_credit === 1 ? 'checked ="checked"' : 0 }}>
                                </td>
                            </tr>
                        </div>
                        <div class="form-group">
                            <tr>
                                <td style="width: 10px"></td>
                                <td>Show non-employees in Leave Module</td>

                                <td style="text-align: center; vertical-align: middle;">
                                    <input type="hidden" name="show_non_employees_in_leave_Module" value="0">
                                    <input type="checkbox" name="show_non_employees_in_leave_Module"
                                           value="1" {{ $leave_configuration->show_non_employees_in_leave_Module === 1 ? 'checked ="checked"' : 0 }}>
                                </td>
                            </tr>
                        </div>
						<div class="form-group">
                            <tr>
                                <td style="width: 10px"></td>
                                <td>Apply for  unpaid leave when annual is depleted</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <input type="hidden" name="allow_unpaid_leave_when_annual_done" value="0">
                                    <input type="checkbox" name="allow_unpaid_leave_when_annual_done"
                                           value="1" {{ $leave_configuration->allow_unpaid_leave_when_annual_done === 1 ?  'checked ="checked"' : 0 }}>
                                </td>
                            </tr>
                        </div>
                        <div class="form-group">
                            <tr>
                                <td style="width: 10px"></td>
                                <td>Number of Sick negative leave Days</td>
                                <td>
                                    <label for="path" class="control-label"></label>
                                    <input type="number" class="form-control" id="allow_sick_negative_days"
                                           name="allow_sick_negative_days"
                                           value="{{ !empty($leave_configuration->allow_sick_negative_days) ? $leave_configuration->allow_sick_negative_days : '' }}"
                                           placeholder="Enter days" required>
                                </td>
                            </tr>
                        </div>
                        <div class="form-group">
                            <tr>
                                <td style="width: 10px"></td>
                                <td>Number of Annual negative leave Days</td>
                                <td>
                                    <label for="path" class="control-label"></label>
                                    <input type="number" class="form-control" id="allow_annual_negative_days"
                                           name="allow_annual_negative_days"
                                           value="{{ !empty($leave_configuration->allow_annual_negative_days) ? $leave_configuration->allow_annual_negative_days : '' }}"
                                           placeholder="Enter days" required>
                                </td>
                            </tr>
                        </div>
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-database"></i> save leave credit
                        settings
                    </button>
                </div>
            </form>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Notification Settings</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                class="fa fa-remove"></i></button>
                </div>
            </div>
            <form class="form-horizontal" method="post" action="/leave/setup/{{ $leave_configuration->id }}">
                {{ csrf_field() }}
                <div class="box-body">
                    <table class="table table-bordered">
                        <div class="form-group">

                            <tr>
                                <td>Person to receive the Report</td>
                                <td>
                                    <div class="form-group {{ $errors->has('hr_person_id') ? ' has-error' : '' }}">

                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user-circle"></i>
                                                </div>
                                                <select class="form-control select2" style="width: 100%;"
                                                        id="hr_person_id" name="hr_person_id">
                                                    <option value="">*** Select an Employee ***</option>
                                                    @foreach($users as $employee)
                                                        <option value="{{ $employee->id }}" {{ ($employee->id == $leave_configuration->hr_person_id) ? ' selected' : '' }}>{{ $employee->first_name . ' ' . $employee->surname }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>

                            <tr>
                                <td>Notify HR with Application</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <input type="hidden" name="notify_hr_with_application" value="0">
                                    <input type="checkbox" name="notify_hr_with_application"
                                           value="1" {{ $leave_configuration->notify_hr_with_application === 1 ? 'checked ="checked"' : 0 }}>
                                </td>
                            </tr>
                        </div>

                        <div class="form-group">
                            <tr>
                                <td>Preferred Communication Method</td>
                                <td>
                                    <div class="radio">
                                        <label><input type="radio" name="preferred_communication_method" id="Email"
                                                      value="1" checked> Email</label>
                                        <br>
                                        <br>
                                        <label><input type="radio" name="preferred_communication_method" id="SMS"
                                                      value="2" checked> SMS</label>
                                        <br>
                                        <br>
                                        <label><input type="radio" name="preferred_communication_method" id="3"
                                                      value="3" checked> Based on Employee</label>
                                    </div>
                                </td>
                            </tr>
                        </div>
                    </table>
                </div>
                <!-- Include add expenditure and add income modals -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-database"></i> save notifications
                        settings
                    </button>
                </div>
            </form>
        </div>
    </div>


    {{--Approval Settings--}}

    <div class="col-sm-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Approval Settings</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                class="fa fa-remove"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <form class="form-horizontal" method="post" action="/leave/setup/{{ $leave_configuration->id }}">
                {{ csrf_field() }}
                <div class="box-body">
                    <table class="table table-bordered">
                        <div class="form-group">
                            <tr>
                                <td>Require Manager's approval</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <input type="hidden" name="require_managers_approval" value="0">
                                    <input type="checkbox" name="require_managers_approval"
                                           value="1" {{ $leave_configuration->require_managers_approval === 1 ? 'checked ="checked"' : 0 }}>
                                </td>
                            </tr>
                        </div>
                        <div class="form-group">
                            <tr>
                                <td>Require Department Head approval</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <input type="hidden" name="require_department_head_approval" value="0">
                                    <input type="checkbox" name="require_department_head_approval"
                                           value="1" {{ $leave_configuration->require_department_head_approval === 1 ? 'checked ="checked"' : 0 }}>
                                </td>
                            </tr>
                        </div>
                        <div class="form-group">
                            <tr>
                                <td>Require HR approval</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <input type="hidden" name="require_hr_approval" value="0">
                                    <input type="checkbox" name="require_hr_approval"
                                           value="1" {{ $leave_configuration->require_hr_approval === 1 ? 'checked ="checked"' : 0 }}>
                                </td>
                            </tr>
                        </div>
                        <div class="form-group">
                            <tr>
                                <td>Require Payroll approval</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <input type="hidden" name="require_payroll_approval" value="0">
                                    <input type="checkbox" name="require_payroll_approval"
                                           value="1" {{ $leave_configuration->require_payroll_approval === 1 ? 'checked ="checked"' : 0 }}>
                                </td>
                            </tr>
                        </div>
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="modal-footer">

                    <button type="submit" class="btn btn-primary"><i class="fa fa-database"></i> save approval
                        settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">General Settings</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                class="fa fa-remove"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <form class="form-horizontal" method="post" action="/leave/setup/{{ $leave_configuration->id }}">
                {{ csrf_field() }}
                <div class="box-body">
                    <table class="table table-bordered">

                        <div class="form-group">
                            <tr>
                                <td>Ers Token</td>
                                <td>
                                    <label for="path" class="control-label"></label>
                                    <div>
                                        <i class="bi bi-eye-slash" id="togglePassword">View Token</i>
                                        <br><br>
                                        <input type="password" class="form-control" id="ers_token_number"
                                               name="ers_token_number"
                                               value="{{ !empty($leave_configuration->ers_token_number) ?
                                            $leave_configuration->ers_token_number : '' }}"
                                               placeholder="Ers Token">

                                    </div>

                                </td>
                            </tr>
                        </div>

                        <div class="form-group">
                            <tr>
                                <td>Number of Days before Automate Leave Application</td>
                                <td>
                                    <label for="path" class="control-label"></label>
                                    <input type="number" class="form-control"
                                           id="number_of_days_before_automate_application"
                                           name="number_of_days_before_automate_application"
                                           value="{{ !empty($leave_configuration->number_of_days_before_automate_application) ?
                                                $leave_configuration->number_of_days_before_automate_application : '' }}"
                                           placeholder="Enter  days">
                                </td>
                            </tr>
                        </div>

                        <div class="form-group">
                            <tr>
                                <td>Number of Days to Remind Manager</td>
                                <td>
                                    <label for="path" class="control-label"></label>
                                    <input type="number" class="form-control" id="number_of_days_to_remind_manager"
                                           name="number_of_days_to_remind_manager"
                                           value="{{ !empty($leave_configuration->number_of_days_to_remind_manager) ? $leave_configuration->number_of_days_to_remind_manager : '' }}"
                                           placeholder="Enter  days">
                                </td>
                            </tr>
                        </div>

                        <div class="form-group">
                            <tr>
                                <td>Number of Days Until Escalation</td>
                                <td>
                                    <label for="path" class="control-label"></label>
                                    <input type="number" class="form-control" id="mumber_of_days_until_escalation"
                                           name="mumber_of_days_until_escalation"
                                           value="{{ !empty($leave_configuration->mumber_of_days_until_escalation) ? $leave_configuration->mumber_of_days_until_escalation : '' }}"
                                           placeholder="Enter  days">
                                </td>
                            </tr>
                        </div>

                        <div class="form-group">
                            <tr>
                                <td>Document compulsory on Study leave application</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <input type="hidden" name="document_compulsory_on_Study_leave_application"
                                           value="0">
                                    <input type="checkbox" name="document_compulsory_on_Study_leave_application"
                                           value="1" {{ $leave_configuration->document_compulsory_on_Study_leave_application === 1 ? 'checked ="checked"' : 0 }}>
                                </td>
                            </tr>
                        </div>

                        <div class="form-group">
                            <tr>
                                <td>Document compulsory when two sick leave within 8_weeks</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <input type="hidden" name="document_compulsory_when_two_sick_leave_8_weeks"
                                           value="0">
                                    <input type="checkbox" name="document_compulsory_when_two_sick_leave_8_weeks"
                                           value="1" {{ $leave_configuration->document_compulsory_when_two_sick_leave_8_weeks === 1 ? 'checked ="checked"' : 0 }}>
                                </td>
                            </tr>
                        </div>
						<div class="form-group">
                            <tr>
                                <td>Update Transaction Password</td>
                                <td>
                                    <label for="path" class="control-label"></label>
                                    <div>
                                        <input type="password" class="form-control" id="password_update"
                                               name="password_update"
                                               value="{{ !empty($leave_configuration->password_update) ?
                                            $leave_configuration->password_update : '' }}"
                                               placeholder="Password">

                                    </div>

                                </td>
                            </tr>
                        </div>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-database"></i> save notifications
                        settings
                    </button>
                </div>
            </form>
        </div>

    </div>

@endsection
<!-- Ajax form submit -->
@section('page_script')
    <script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>

    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <script src="{{ asset('bower_components/AdminLTE/plugins/iCheck/icheck.min.js')}}"></script>
    <script src="/custom_components/js/deleteAlert.js"></script>
        <script src="/custom_components/js/dataTable.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <!-- InputMask -->

    <script>

        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#ers_token_number");

        togglePassword.addEventListener("click", function () {
            // toggle the type attribute
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);

            // toggle the icon
            this.classList.toggle("bi-eye");
        });

        $('.delete_confirm').click(function (event) {

            let form = $(this).closest("form");

            let name = $(this).data("name");

            event.preventDefault();

            swal({

                title: `Are you sure you want to delete this record?`,
                text: "If you delete this, it will be gone forever.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                        swal("Poof! Your Record has been deleted!", {
                            icon: "success",
                        });
                    }

                });

        });

        // prevent form submit
        const form = document.querySelector("form");
        form.addEventListener('submit', function (e) {
            e.preventDefault();
        });

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

            //Initialize Select2 Elements
            $(".select2").select2();

            let moduleId;
            //Tooltip
            $('[data-toggle="tooltip"]').tooltip();

            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '10%' // optional
            });


            // Reposition when a modal is shown
            $('.modal').on('show.bs.modal', reposition);
            // Reposition when the window is resized


            let leavesetupId;
            $('#edit-leave_taken-modal').on('show.bs.modal', function (e) {
                //console.log('kjhsjs');
                let btnEdit = $(e.relatedTarget);
                leavesetupId = btnEdit.data('id');
                console.log('leavesetupID: ' + leavesetupId);
                let name = btnEdit.data('name');
                let day5min = btnEdit.data('day5min');
                let day5max = btnEdit.data('day5max');
                let day6min = btnEdit.data('day6min');
                let day6max = btnEdit.data('day6max');
                let shiftmin = btnEdit.data('shiftmin');
                let shiftmax = btnEdit.data('shiftmax');

                // var moduleFontAwesome = btnEdit.data('font_awesome');
                let modal = $(this);
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
                let modalID = 'edit-leave_taken-modal';
                let submitBtnID = 'update-leave_taken';
                let redirectUrl = '/leave/setup';
                let successMsgTitle = 'Changes Saved!';
                let successMsg = 'Leave days has been successfully added.';
                // var method = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });                        // ----edit setup leave days ------
        });

        //#leave cresdit settings
        $('#save_leave_credit').on('click', function () {
            let strUrl = '/leave/custom/add_leave';
            let objData = {
                hr_id: $('#add-custom-leave-modal').find('#hr_id').val()
                , number_of_days: $('#add-custom-leave-modal').find('#number_of_days').val()
                , _token: $('#add-custom-leave-modal').find('input[name=_token]').val()
            };
            let modalID = 'add-custom-leave-modal';
            let submitBtnID = 'add_custom_leave';
            let redirectUrl = '/leave/types';
            let successMsgTitle = 'Changes Saved!';
            let successMsg = 'Leave has been successfully added.';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });


        /**
         * Add Exempted users
         */
        $('#add-exempted').on('click', function () {

            let strUrl = '{{ route('exempted_users') }}';
            let modalID = 'add-exempted-modal';
            let formName = 'add-exempted-form';

            let submitBtnID = 'add-exempted';
            let redirectUrl = '/leave/setup';
            let successMsgTitle = 'User Added to List!';
            let successMsg = 'Record has been updated successfully.';

            modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });

        /**
         * Add managers
         */

        $('#add-manager').on('click', function () {

            let strUrl = '{{ route('manager_report') }}';
            let modalID = 'add-managers-modal';
            let formName = 'add-manager_module-form';

            let submitBtnID = 'add-manager';
            let redirectUrl = '/leave/setup';
            let successMsgTitle = 'Manager Added to List!';
            let successMsg = 'Record has been updated successfully.';

            modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });

		/**
         * Add leave notification users
         */

        $('#add-leave-users').on('click', function () {

            let strUrl = '{{ route('leave_not_user') }}';
            let modalID = 'add-leave-notification-modal';
            let formName = 'add-leave-notification-form';

            let submitBtnID = 'add-leave-users';
            let redirectUrl = '/leave/setup';
            let successMsgTitle = 'User Added to List!';
            let successMsg = 'Record has been updated successfully.';

            modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });
        //UPDATE

        let updateNegativeID;
        $('#edit-annual-modal').on('show.bs.modal', function (e) {
            let btnEdit = $(e.relatedTarget);

            updateNegativeID = btnEdit.data('id');
            let number_of_days_annual = btnEdit.data('number_of_days_annual');
            //console.log(number_of_days_annual);
            let modal = $(this);
            modal.find('#number_of_days_annual').val(number_of_days_annual);

        });

        let updateSickID;
        $('#edit-sick-modal').on('show.bs.modal', function (e) {
            let btnEdit = $(e.relatedTarget);

            updateSickID = btnEdit.data('id');
            let number_of_days_sick = btnEdit.data('number_of_days_sick');
            // console.log(number_of_days_sick);
            let modal = $(this);
            modal.find('#number_of_days_sick').val(number_of_days_sick);

        });

        //SAVE

        $('#update_annual').on('click', function () {
            let strUrl = '/leave/setup/' + '1';
            let objData = {
                number_of_days_annual: $('#edit-annual-modal').find('#number_of_days_annual').val()
                , _token: $('#edit-annual-modal').find('input[name=_token]').val()
            };
            let modalID = 'edit-annual-modal';
            let submitBtnID = 'edit_annual';
            let redirectUrl = '/leave/setup';
            let successMsgTitle = 'Changes Saved!';
            let successMsg = 'Leave has been successfully added.';
            let formMethod = 'PATCH';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, formMethod);
        });

        $('#update-sick').on('click', function () {
            let strUrl = '/leave/setup/' + '1/' + 'sick';
            let objData = {
                number_of_days_sick: $('#edit-sick-modal').find('#number_of_days_sick').val()
                , _token: $('#edit-sick-modal').find('input[name=_token]').val()
            };
            let modalID = 'edit-sick-modal';
            let submitBtnID = 'edit_sick';
            let redirectUrl = '/leave/setup';
            let successMsgTitle = 'Changes Saved!';
            let successMsg = 'Leave has been successfully added.';
            let formMethod = 'PATCH';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, formMethod);
        });


    </script>

@endsection
