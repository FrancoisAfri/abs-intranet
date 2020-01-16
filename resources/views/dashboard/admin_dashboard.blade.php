@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@endsection
@section('content')
	<div class="row">
		@if($activeModules->where('code_name', 'cms')->first())
            <div class="col-md-6">
                <div class="box box-muted same-height-widget">
                    <div class="box-header with-border">
                        <i class="fa fa-comments-o"></i>
                        <h3 class="box-title"> News Flash</h3>
                    </div>
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="5"></li>
                        </ol>
                        <div class="carousel-inner">
                            @if (!empty($news))
                                @foreach($news as $key => $Cmsnews)
                                    <div class="item{{ $key == 0 ? ' active' : '' }}"> <!-- item 1 -->
                                        <a href="{{ '/view/' . $Cmsnews->id }}" id="edit_compan"
                                           class="btn btn-default  btn-xs" target="_blank"><i class=""></i> Read more
                                        </a>
										<b>{{$Cmsnews->description}}</b>
                                        <img class="img-responsive pad" src="{{ Storage::disk('local')->url("CMS/images/$Cmsnews->image") }}" width="400" height="400">

                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button"
                           data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button"
                           data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
                @include('dashboard.partials.view_news_modal')
            </div>
            <div class="col-md-6">
				<div class="box box-widget">
					<div class="box-header with-border">
						<div class="user-block">
							<span class="username">CEO Communication</span>
							<span class="description">Posted - {{!empty($ceonews->date) ? date(' d M Y', $ceonews->date) : ''}}</span>
						</div>
						<div class="box-tools">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
						</div>
					</div>
					@if (!empty($ceonews))
						<div class="box-body" style="max-height: 400px; overflow-y: scroll;">
							<img class="img-responsive pad" style="padding:0 15 px;  float: left" width="250" height="220" alt="{{!empty($ceonews->name) ? $ceonews->name : ''}}" src="{{ Storage::disk('local')->url("CMS/images/$ceonews->image") }}">
							<p><font size="3">{!!$ceonews->summary!!}</font></p>
						</div>
					@endif
				</div>
			</div>
		@endif
		@if($activeModules->where('code_name', 'appraisal')->first())
            <div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header">
						<h3 class="box-title"><i class="fa fa-hourglass"></i> Employee Monthly Appraisal</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div>
					<!-- Employee Monthly performance Widget-->
					<!-- /.box-header -->
					<div class="box-body">
						<div class="row">
							<div class="col-md-8">
								<p class="text-center">
									<strong>My Performance For {{ date('Y') }}</strong>
								</p>
								<div class="chart">
									<!-- Sales Chart Canvas-->
									<canvas id="empMonthlyPerformanceChart" style="height: 220px;"></canvas>
								</div>
								<!-- /.chart-responsive -->
							</div>
							<!-- Appraised months list col -->
							<div class="col-md-4">
								<p class="text-center">
									<strong>Appraised Months List</strong>
								</p>
								<div class="no-padding" style="max-height: 220px; overflow-y: scroll;">
									<ul class="nav nav-pills nav-stacked" id="emp-appraised-month-list"></ul>
								</div>
							</div>
						</div>
						<!-- /.row -->
					</div>
				<!-- Loading wheel overlay -->
				<div class="overlay" id="loading_overlay_emp_monthly_appraisal">
					<i class="fa fa-refresh fa-spin"></i>
				</div>
				</div>
                <!-- /.box Employee Monthly performance Widget -->
            </div>
		@endif
		@if($activeModules->where('code_name', 'appraisal')->first())
            @if($canViewCPWidget)
            <div class="col-md-12 box box-default">
				<div class="box-header">
					<h3 class="box-title"><i class="fa fa-hourglass"></i> Company Appraisal</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
					</div>
				</div>
                <!-- company performance Widget -->
				<!-- /.box-header -->
				<div class="box-body">
					<div class="row" id="myStaffPerformanceRankingRow" hidden>
						<div class="col-md-12">
							<p class="text-center"><strong>My Staff Performance Ranking
									For {{ date('Y') }}</strong></p>
							<div class="no-padding" style="max-height: 420px; overflow-y: scroll;">
								<ul class="nav nav-pills nav-stacked products-list product-list-in-box"
									id="my-staff-ranking-list">
								</ul>
							</div>
						</div>
					</div>
					<div class="row" id="topLvlDivGraphAndRankingRow">
						<!-- Chart col -->
						<div class="col-md-8">
							<p class="text-center">
								<strong>
									@if($isSuperuser)
										{{ $topGroupLvl->plural_name }}
									@elseif($isDivHead)
										{{ $managedDivsLevel->plural_name }}
									@endif
									Performance For {{ date('Y') }}
								</strong>
							</p>
							<div class="chart">
								<!-- Sales Chart Canvas-->
								<canvas id="divisionsPerformanceChart" style="height: 220px;"></canvas>
							</div>
							<!-- /.chart-responsive -->
						</div>
						<!-- Ranking col -->
						<div class="col-md-4">
							<p class="text-center">
								<strong>Ranking</strong>
							</p>
							<div class="no-padding" style="max-height: 220px; overflow-y: scroll;">
								<ul class="nav nav-pills nav-stacked" id="ranking-list">
								</ul>
							</div>
						</div>
					</div>
					<!-- /.row -->
				</div>
				<!-- Loading wheel overlay -->
				<div class="overlay" id="lo_company_appraisal">
					<i class="fa fa-refresh fa-spin"></i>
				</div>
				<!-- Include division performance modal -->
				@include('dashboard.partials.division_4_performance_modal')
				@include('dashboard.partials.division_3_performance_modal')
				@include('dashboard.partials.division_2_performance_modal')
				@include('dashboard.partials.division_1_performance_modal')
				<!-- Include emp list performance modal -->
				@include('dashboard.partials.emp_list_performance_modal')
				<!-- Include emp year performance modal -->
				@include('dashboard.partials.emp_year_performance_modal')
            </div>
			<!-- /.box company performance Widget -->
            @endif
		@endif
		@if($activeModules->where('code_name', 'leave')->first())
            <div class="col-md-12 box box-default collapsed-box">
				<div class="box-header">
					<h3 class="box-title"><i class="fa fa-hourglass"></i> Leave Balance</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
					</div>
				</div>
				<!-- /.box-header -->
				<div class="box-body" style="max-height: 274px; overflow-y: scroll;">
					<div class="table-responsive">
						<table class="table no-margin">
							<thead>
								<tr>
									<th>Leave Type</th>
									<th style="text-align: right;"><i class="material-icons">account_balance_wallet</i>Leave
										Balance
									</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
							@if (!empty($balances))
								@foreach($balances as $balance)
									<tr>
										<td>{{ (!empty($balance->leavetype)) ?  $balance->leavetype : ''}}</td>
										<td style="text-align: right;">{{ (!empty($balance->leave_balance)) ?  $balance->leave_balance / 8: 0}}</td>
									</tr>
								@endforeach
							@endif
							</tbody>
						</table>
						<div class="box-footer">
							@if (!empty($surbs))
								<button type="button" id="leave-balance" class="btn btn-primary pull-left"
								data-toggle="modal" data-target="#leave-balance-modal"
										>Subordinates Balances</button>
							@endif
							<button id="Apply" class="btn btn-primary pull-right"><i
										class="fa fa-cloud-download"></i> Apply For Leave
							</button>
						</div>
					</div>
					@if (!empty($surbs))
						@include('dashboard.partials.widgets.leave_balance')
					@endif
				</div>
				<!-- /.box-body -->
				<div class="box-footer clearfix">
				</div>
            </div>
			<div class="col-md-12 box box-default collapsed-box">
				<div class="box-header">
					<h3 class="box-title"><i class="fa fa-hourglass"></i> People On Leave This Month</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
					</div>
				</div>
				<!-- /.box-header -->
				<div class="box-body no-padding" style="max-height: 180px; overflow-y: scroll;">
					<table class="table table-striped table-hover">
						<thead>
						<tr>
							<th style="width: 10px">#</th>
							<th>Employee</th>
							<th class="text-center">From</th>
							<th class="text-center">To</th>
						</tr>
						</thead>
						<tbody>
						@foreach($onLeaveThisMonth as $employee)
							<tr>
								<td style="vertical-align: middle;"
									class="{{ ($employee->is_on_leave_today) ? 'bg-primary' : '' }}"
									nowrap>{{ $loop->iteration }}.
								</td>
								<td style="vertical-align: middle;"
									class="{{ ($employee->is_on_leave_today) ? 'bg-primary' : '' }}">
									<img src="{{ $employee->profile_pic_url }}" class="img-circle"
										 alt="Employee's Photo"
										 style="width: 25px; height: 25px; border-radius: 50%; margin-right: 10px; margin-top: -2px;">
									<span>{{ $employee->full_name }}</span>
								</td>
								<td style="vertical-align: middle;"
									class="text-center {{ ($employee->is_on_leave_today) ? 'bg-primary' : '' }}">{{ ($employee->start_time) ? date('d M Y H:i', $employee->start_time) : (($employee->start_date) ? date('d M Y', $employee->start_date) : '') }}</td>
								<td style="vertical-align: middle;"
									class="text-center {{ ($employee->is_on_leave_today) ? 'bg-primary' : '' }}">{{ ($employee->end_time) ? date('d M Y H:i', $employee->end_time) : (($employee->end_date) ? date('d M Y', $employee->end_date) : '') }}</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
				<!-- /.box-body -->
				<div class="box-footer clearfix">
				</div>
            </div> 
			<div class="col-md-12 box box-default collapsed-box">
				<div class="box-header">
					<h3 class="box-title"><i class="fa fa-hourglass"></i> My Leave Applications</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
					</div>
				</div>
                <!-- /.box-header -->
				<div class="box-body" style="max-height: 274px; overflow-y: scroll;">
					<div class="table-responsive">
						<table class="table no-margin">
							<thead>
								<tr>
									<th><i class="material-icons">shop_two</i> Leave Type</th>
									<th><i class="fa fa-calendar-o"></i> Date From</th>
									<th><i class="fa fa-calendar-o"></i> Date To</th>
									<th style="text-align: right;"><i class="fa fa-info-circle"></i> Status</th>
									<th style="text-align: right;"><i class="fa fa-info-circle"></i> Rejection/Cancellation Reason
									</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								@if (!empty($application))
									@foreach($application as $app)
										<tr>
											<td style="vertical-align: middle;">{{ (!empty($app->leavetype)) ?  $app->leavetype : ''}}</td>
											<td style="vertical-align: middle;">
												{{ !empty($app->start_date) ? date('d M Y ', $app->start_date) : '' }}
											</td>
											<td style="vertical-align: middle;">{{ !empty($app->end_date) ? date('d M Y ', $app->end_date) : '' }}</td>
											<td style="text-align: right; vertical-align: middle;">
												{{ (!empty($app->status) && $app->status > 0) ? $leaveStatusNames[$app->status]." ".$app->reject_reason  : ''}}
											</td>
											<td style="text-align: right; vertical-align: middle;">
												@if ($app->status == 10)
													{{ !empty($app->cancellation_reason) ? $app->cancellation_reason  : ''}}
												@else
													{{ !empty($app->reject_reason) ? $app->reject_reason  : ''}}
												@endif
											</td>
											<td class="text-right" style="vertical-align: middle;">
												@if(in_array($app->status, [2, 3, 4, 5]))
													<button class="btn btn-xs btn-warning"
															title="Cancel Leave Application" data-toggle="modal"
															data-target="#cancel-leave-application-modal"
															data-leave_application_id="{{ $app->id }}"><i
																class="fa fa-times"></i></button>
												@endif
											</td>
										</tr>
									@endforeach
								@endif
							</tbody>
						</table>
					</div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer clearfix">
				</div>
				<!-- Include cancellation reason modal -->
				@include('dashboard.partials.cancel_leave_application_modal')
            </div>
		@endif
		@if($activeModules->whereIn('code_name', ['induction', 'tasks', 'meeting'])->first())
            <div class="col-md-12 box box-default collapsed-box">
                <!-- Include tasks widget -->
                @include('dashboard.partials.widgets.tasks_widget')
            </div>
			@if(Session('error_starting'))
				@include('tasks.partials.error_tasks', ['modal_title' => "Task Error!", 'modal_content' => session('error_starting')])
			@endif
			@include('tasks.partials.end_task')
		@endif
		@if($activeModules->whereIn('code_name', 'security')->first())
            <div class="col-md-12 box box-default collapsed-box">
				<div class="box-header">
					<h3 class="box-title"><i class="fa fa-hourglass"></i> Staff Anniversary</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
					</div>
				</div>
				<!-- /.box-header -->
				<div class="box-body" style="max-height: 274px; overflow-y: scroll;">
					<div class="table-responsive">
						<table class="table no-margin">
							<thead>
								<tr>
									<th>Employee</th>
									<th style="text-align: right;"><i class="material-icons">account_balance_wallet</i>Leave
										Anniversary Date
									</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								@foreach($staffAnniversaries as $staff)
									<tr>									
										<td style="vertical-align: middle;"
											class="{{ !empty($staff['is_birthday_today']) ? 'bg-primary' : '' }}">
											<img src="{{ !empty($staff['profile_pic_ur']) ? $staff['profile_pic_ur'] : '' }}" class="img-circle"
											 alt="Employee's Photo"
											 style="width: 25px; height: 25px; border-radius: 50%; margin-right: 10px; margin-top: -2px;">
											<span>{{ $staff['names'] }}</span>
										</td>
										<td style="vertical-align: middle;"
											class="text-center {{ !empty($staff['is_birthday_today']) ? 'bg-primary' : '' }}">{{ !empty($staff['birthday_month']) ? $staff['birthday_month'] : ''}}</td>
									</tr>
							@endforeach 
							</tbody>
						</table>
					</div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer clearfix">
				</div>
				<!-- /.box-footer -->
                <!-- /Tasks List End -->
            </div>
			<div class="col-md-12 box box-default collapsed-box">
				<div class="box-header">
					<h3 class="box-title"><i class="fa fa-hourglass"></i> Birthdays This Month</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
					</div>
				</div>
				<!-- /.box-header -->
				<div class="box-body no-padding" style="max-height: 180px; overflow-y: scroll;">
					<div class="table-responsive">
						<table class="table table-striped table-hover">
							<thead>
								<tr>
									<th>Employee</th>
									<th class="text-center">Date Of Birth</th>
								</tr>
							</thead>
							<tbody>
							@foreach($birthdays as $birthday)
								<tr>									
									<td style="vertical-align: middle;"
										class="{{ !empty($birthday['is_birthday_today']) ? 'bg-primary' : '' }}">
										<img src="{{ !empty($birthday['profile_pic_ur']) ? $birthday['profile_pic_ur'] : '' }}" class="img-circle"
										 alt="Employee's Photo"
										 style="width: 25px; height: 25px; border-radius: 50%; margin-right: 10px; margin-top: -2px;">
										<span>{{ $birthday['names'] }}</span>
									</td>
									<td style="vertical-align: middle;"
										class="text-center {{ !empty($birthday['is_birthday_today']) ? 'bg-primary' : '' }}">{{ !empty($birthday['birthday_month']) ? $birthday['birthday_month'] : ''}}</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
				<div class="box-footer clearfix">
				</div>
				<!-- /.box-body -->
            </div>
		@endif
		@if($activeModules->where('code_name', 'appraisal')->first())
			@if($canViewEmpRankWidget)
				<div class="col-md-12 box box-default collapsed-box"  id="empPerformanceRankingWidgetBox">
					<div class="box-header">
						<h3 class="box-title"><i class="fa fa-hourglass"></i> Employees Ranking</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div>
					<!-- Employees Performance Ranking Widget -->
					<!-- /.box-header -->
					<div class="box-body no-padding">
						<!-- Emp Group Filters (divisions) -->
						<div class="col-sm-4 border-right">
							<p class="text-center">
								<strong>Filters</strong>
							</p>
							<form>
								@foreach($divisionLevels as $divisionLevel)
									<div class="form-group">
										<label for="{{ 'division_level_' . $divisionLevel->level }}"
											   class="control-label">{{ $divisionLevel->name }}</label>

										<select id="{{ 'division_level_' . $divisionLevel->level }}"
												name="{{ 'division_level_' . $divisionLevel->level }}"
												class="form-control input-sm select2"
												onchange="divDDEmpPWOnChange(this, $('#emp-top-ten-list'), $('#emp-bottom-ten-list'), parseInt('{{ $totNumEmp }}'), $('#loading_overlay_emp_performance_ranking'))"
												style="width: 100%;">
										</select>
									</div>
								@endforeach
							</form>
						</div>
						<!-- /.Emp Group Filters (divisions) -->
						<!-- Top ten -->
						<div class="col-sm-4 border-right">
							<p class="text-center">
								<strong class="label label-success"><i class="fa fa-level-up"></i> Top 10 Employees</strong>
							</p>
							<div class="no-padding" style="max-height: 274px; overflow-y: scroll;">
								<ul class="nav nav-pills nav-stacked products-list product-list-in-box"
									id="emp-top-ten-list">
								</ul>
							</div>
						</div>
						<!-- Bottom ten -->
						<div class="col-sm-4">
							<p class="text-center">
								<strong class="label label-danger"><i class="fa fa-level-down"></i> Bottom 10
									Employees</strong>
							</p>
							<div class="no-padding" style="max-height: 274px; overflow-y: scroll;">
								<ul class="nav nav-pills nav-stacked products-list product-list-in-box"
									id="emp-bottom-ten-list">
								</ul>
							</div>
						</div>
					</div>
					<!-- /.box-body -->
					<!-- Loading wheel overlay -->
					<div class="overlay" id="loading_overlay_emp_performance_ranking">
						<i class="fa fa-refresh fa-spin"></i>
					</div>
					<!-- /.Employees Performance Ranking Widget -->
				</div>
			@endif
		@endif
		@if($activeModules->where('code_name', 'appraisal')->first())
            <div class="col-md-12 box box-default collapsed-box">
				<div class="box-header">
					<h3 class="box-title"><i class="fa fa-hourglass"></i> Available Perks</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
					</div>
				</div>
				<!-- Available Perks Widgets -->
				<!-- /.box-header -->
				<div class="box-body no-padding">
					<ul class="users-list clearfix" id="perks-widget-list">
					</ul>
					<!-- /.users-list -->
				</div>
				<!-- /.box-body -->
				<div class="box-footer clearfix">
				</div>
				<!-- include perk details modal -->
				@include('appraisals.partials.edit_perk', ['isReaOnly' => true])
                <!-- /.Available Perks Widgets -->
            </div>
		@endif
		@if($activeModules->where('code_name', 'induction')->first())
            <div class="col-md-12 box box-default collapsed-box">
				<div class="box-header">
					<h3 class="box-title"><i class="fa fa-hourglass"></i> Induction</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
					</div>
				</div>
               <!-- /.box-header -->
				<div class="box-body" style="max-height: 274px; overflow-y: scroll;">
                    <table class="table table-striped table-bordered">
                        <tr>
							<th>Induction Name</th>
                            <th>KAM </th>
                            <th>Client</th>
                            <th style="text-align: center;"><i class="fa fa-info-circle"></i> Status</th>
                        </tr>
						@if (!empty($ClientInduction))
							@foreach($ClientInduction as $Induction)
                            <tr>
                               <!--  <td>{{ $Induction->completed_task }}</td> -->
                                <td>{{ (!empty($Induction->induction_title)) ?  $Induction->induction_title : ''}}</td>
                                <td>{{ !empty($Induction->firstname) && !empty($Induction->surname) ? $Induction->firstname.' '.$Induction->surname : '' }}</td>
                                 <!-- <td>{{ (!empty($Induction->create_by)) ?  $Induction->create_by : ''}}</td> -->
								<td>{{ (!empty($Induction->company_name)) ?  $Induction->company_name : ''}} </td>
								<td>
									<div class="progress xs">
									<div class="progress-bar progress-bar-warning  progress-bar-striped" role="progressbar"
									aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:{{ $Induction->completed_task == 0 ? 0 : ($Induction->completed_task/$Induction->total_task * 100)  }}%"></div></div>
									{{  (round($Induction->completed_task == 0 ? 0 : ($Induction->completed_task/$Induction->total_task * 100)))}}% 
								</td>
                            </tr>
                           @endforeach
						@endif
                    </table>
                </div>
			</div>
		@endif
   </div>
@endsection
@section('page_script')
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- ChartJS 1.0.1 -->
    <script src="/bower_components/AdminLTE/plugins/chartjs/Chart.min.js"></script>
    <!-- Admin dashboard charts ChartsJS -->
    <script src="/custom_components/js/admindbcharts.js"></script>
    <!-- matchHeight.js
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.0/jquery.matchHeight-min.js"></script>-->
    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
    <!-- Task timer -->
    <script src="/custom_components/js/tasktimer.js"></script>
    <!-- CK Editor -->
    <script src="https://cdn.ckeditor.com/4.7.1/standard/ckeditor.js"></script>

    {{--<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>--}}
    <script>
        function postData(id, data) {
            if (data == 'start')
                location.href = "/task/start/" + id;
            else if (data == 'pause')
                location.href = "/task/pause/" + id;
            else if (data == 'end')
                location.href = "/task/end/" + id;
        }

        $(function () {
            // hide end button when page load
            //$("#end-button").show();
            //Initialize Select2 Elements
            $(".select2").select2();

            $('#Apply').click(function () {
                location.href = '/leave/application';
            });

            $('#ticket').click(function () {
                location.href = '/helpdesk/ticket';
            });


            //initialise matchHeight on widgets
            //$('.same-height-widget').matchHeight();

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
            $(window).on('resize', function () {
                $('.modal:visible').each(reposition);
            });

            $(function () {
                $('img').on('click', function () {
                    $('.enlargeImageModalSource').attr('src', $(this).attr('src'));
                    $('#enlargeImageModal').modal('show');
                });
            });
//            CKEDITOR.replace('summary');
            //widgets permissions
            var isSuperuser = parseInt({{ (int) $isSuperuser }}),
                isDivHead = parseInt({{ (int) $isDivHead }}),
                isSupervisor = parseInt({{ (int) $isSupervisor }}),
                canViewCPWidget = parseInt({{ (int) $canViewCPWidget }}),
                canViewTaskWidget = parseInt({{ (int) $canViewTaskWidget }}),
                canViewEmpRankWidget = parseInt({{ (int) $canViewEmpRankWidget }});

            @if($activeModules->where('code_name', 'appraisal')->first())
            //Employees ranking widget
            if (canViewEmpRankWidget == 1) {
                //Load divisions drop down
                var parentDDID = '';
                var loadAllDivs = 1;
                var firstDivDDID = null;
                var parentContainer = $('#empPerformanceRankingWidgetBox');
                @foreach($divisionLevels as $divisionLevel)
                //Populate drop down on page load
                var ddID = '{{ 'division_level_' . $divisionLevel->level }}';
                var postTo = '{!! route('divisionsdropdown') !!}';
                var selectedOption = '';
                //var divLevel = parseInt('{{ $divisionLevel->level }}');
                var incInactive = -1;
                var loadAll = loadAllDivs;
                        @if($loop->first)
                var selectFirstDiv = 1;
                var divHeadSpecific;
                if (isSuperuser) divHeadSpecific = 0;
                else if (isDivHead) divHeadSpecific = 1;
                loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo, selectFirstDiv, divHeadSpecific, parentContainer);
                //firstDivDDID = ddID;
                @else
                loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo, null, null, parentContainer);
                @endif
                //parentDDID
                parentDDID = ddID;
                loadAllDivs = -1;
                @endforeach

                //Load top ten performing employees (widget)
                //var topTenList = $('#emp-top-ten-list');
                //loadEmpListPerformance(topTenList, 0, 0, true);

                //Load Bottom ten performing employees (widget)
                //var bottomTenList = $('#emp-bottom-ten-list');
                //var totNumEmp = parseInt('{{ $totNumEmp }}');
                //loadEmpListPerformance(bottomTenList, 0, 0, false, true, totNumEmp);
            }

            if (canViewTaskWidget == 1) {
                //Load divisions drop down
                var parentDDID = '';
                var loadAllDivs = 1;
                var firstDivDDID = null;
                var parentContainer = $('#emptasksWidgetBox');
                @foreach($divisionLevels as $divisionLevel)
                //Populate drop down on page load
                var ddID = '{{ 'division_level_' . $divisionLevel->level }}';
                var postTo = '{!! route('divisionsdropdown') !!}';
                var selectedOption = '';
                //var divLevel = parseInt('{{ $divisionLevel->level }}');
                var incInactive = -1;
                var loadAll = loadAllDivs;
                        @if($loop->first)
                var selectFirstDiv = 1;
                var divHeadSpecific = 1;
                if (isSuperuser) divHeadSpecific = 0;
                else if (isDivHead) divHeadSpecific = 1;
                loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo, selectFirstDiv, divHeadSpecific, parentContainer);
                firstDivDDID = ddID;
                @else
                loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo, null, null, parentContainer);
                @endif
                //parentDDID
                parentDDID = ddID;
                loadAllDivs = -1;
                @endforeach
            }

            //Draw employee performance graph
            var empID = parseInt('{{ $user->person->id }}');
            var empChartCanvas = $('#empMonthlyPerformanceChart');
            var loadingWheel = $('#loading_overlay_emp_monthly_appraisal');
            var empAppraisedMonthList = $('#emp-appraised-month-list');
            loadEmpMonthlyPerformance(empChartCanvas, empID, loadingWheel, empAppraisedMonthList);

            //Company appraisal
            if (canViewCPWidget == 1) {
                //Draw divisions performance graph [Comp Appraisal Widget]
                var rankingList = $('#ranking-list');
                var divChartCanvas = $('#divisionsPerformanceChart');
                var loadingWheelCompApr = $('#lo_company_appraisal');
                var managerID = parseInt({{ $user->person->id }});
                if (isSuperuser == 1) {
                    var divLevel = parseInt('{{ $topGroupLvl->id }}');
                    loadDivPerformance(divChartCanvas, rankingList, divLevel, null, null, loadingWheelCompApr);
                }
                else if (isDivHead == 1) {
                    var divLevel = parseInt({{ $managedDivsLevel->level }});
                    loadDivPerformance(divChartCanvas, rankingList, divLevel, null, managerID, loadingWheelCompApr);
                }
                else if (isSupervisor) {
                    $('#topLvlDivGraphAndRankingRow').hide();
                    var staffPerfRow = $('#myStaffPerformanceRankingRow');
                    staffPerfRow.show();
                    rankingList = staffPerfRow.find('#my-staff-ranking-list');
                    loadEmpListPerformance(rankingList, 0, 0, false, false, null, managerID, loadingWheelCompApr);
                }

                //show performance of sub division levels on modals (modal show) [Comp Appraisal Widget]
                var i = 1;
                for (i; i <= 4; i++) {
                    $('#sub-division-performance-modal-' + i).on('show.bs.modal', function (e) {
                        var linkDiv = $(e.relatedTarget);
                        var modalWin = $(this);
                        subDivOnShow(linkDiv, modalWin);
                    });
                    $('#sub-division-performance-modal-' + i).on('hidden.bs.modal', function (e) {
                        $('#lo-sub-division-performance-modal-' + i).show();
                    });
                }

                //show performance of employees on modals [Comp Appraisal Widget]
                $('#emp-list-performance-modal').on('show.bs.modal', function (e) {
                    var linkDiv = $(e.relatedTarget);
                    var modalWin = $(this);
                    var loadingWheelEmpList = $('#lo-emp-list-performance-modal');
                    empPerOnShow(linkDiv, modalWin);
                });
                $('#emp-list-performance-modal').on('hidden.bs.modal', function (e) {
                    $('#lo-emp-list-performance-modal').show();
                });

                //show employee monthly performance on modal [Comp Appraisal Widget]
                $('#emp-year-performance-modal').on('show.bs.modal', function (e) {
                    var linkDiv = $(e.relatedTarget);
                    var empID = parseInt(linkDiv.data('emp_id'));
                    var empName = linkDiv.data('emp_name');
                    var empChartCanvas = $('#empMonthlyPerformanceModalChart');
                    var loadingWheel = $('#lo-emp-year-performance-modal');
                    var empAppraisedMonthList = $('#emp-appraised-month-modal-list');
                    var modalWin = $(this);
                    modalWin.find('#emp-year-modal-title').html(empName + '  - Appraisal');
                    loadEmpMonthlyPerformance(empChartCanvas, empID, loadingWheel, empAppraisedMonthList);
                });
                $('#emp-year-performance-modal').on('hidden.bs.modal', function (e) {
                    $('#lo-emp-year-performance-modal').show();
                });
            }

            var newsID;
            $('#View-news-modal').on('show.bs.modal', function (e) {
                //console.log('kjhsjs');
                var btnEdit = $(e.relatedTarget);
                newsID = btnEdit.data('id');
                var name = btnEdit.data('name');
                var description = btnEdit.data('description');
                var summary = $('<textarea />').html(btnEdit.data('summary')).text();
                var modal = $(this);
                modal.find('#name').val(name);
                modal.find('#description').val(description);
                modal.find('#summary').val(summary);
            });
            //Show available perks on the perks widget
            var perksWidgetList = $('#perks-widget-list');
            loadAvailablePerks(perksWidgetList);

            //Show perk details
            $('#edit-perk-modal').on('show.bs.modal', function (e) {
                var perkLink = $(e.relatedTarget);
                var modal = $(this);
                perkDetailsOnShow(perkLink, modal);
            });
            @endif

            @if($activeModules->where('code_name', 'leave')->first())
            //leave status (widget)
            var LeaveStatus = $('#leave-status-list');
            //loadLeaveStatus();

            //leave cancellation reason form on show
            var cancelApplicationModal = $('#cancel-leave-application-modal');
            var leaveApplicationID;
            cancelApplicationModal.on('show.bs.modal', function (e) {
                //console.log('gets here');
                var btnCancel = $(e.relatedTarget);
                leaveApplicationID = btnCancel.data('leave_application_id');
                //var modal = $(this);
                //modal.find('#task_id').val(taskID);
            });

            //perform leave application cancellation
            cancelApplicationModal.find('#cancel-leave-application').on('click', function () {
                var strUrl = '/leave/application/' + leaveApplicationID + '/cancel';
                var formName = 'cancel-leave-application-form';
                var modalID = 'cancel-leave-application-modal';
                var submitBtnID = 'cancel-leave-application';
                var redirectUrl = '/';
                var successMsgTitle = 'Leave Application Cancelled!';
                var successMsg = 'Your leave application has been cancelled!';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
            @endif

            @if($activeModules->whereIn('code_name', ['induction', 'tasks', 'meeting'])->first())
            document.getElementById("notes").placeholder = "Enter Task Note or Summary";
            //Post end task form to server using ajax (add)
            var taskID;
            var employeeID;
            var uploadRequired;
            $('#end-task-modal').on('show.bs.modal', function (e) {
                var btnEnd = $(e.relatedTarget);
                taskID = btnEnd.data('task_id');
                employeeID = btnEnd.data('employee_id');
                uploadRequired = btnEnd.data('upload_required');
                var modal = $(this);
                modal.find('#task_id').val(taskID);
                modal.find('#employee_id').val(employeeID);
                modal.find('#upload_required').val(uploadRequired);
            });

            $('#end-task').on('click', function () {
                endTask(taskID);
                /*
                var strUrl = '/task/end';
                var formName = 'end-task-form';
                var modalID = 'end-task-modal';
                var submitBtnID = 'end-task';
                var redirectUrl = '/';
                var successMsgTitle = 'Task Ended!';
                var successMsg = 'Task has been Successfully ended!';

                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                */
            });

            $('#close-task-modal').on('show.bs.modal', function (e) {
                var btnEnd = $(e.relatedTarget);
                taskID = btnEnd.data('task_id');
                var modal = $(this);
                modal.find('#task_id').val(taskID);
            });

            $('#close-task').on('click', function () {
                var strUrl = '/task/check';
                var formName = 'close-task-form';
                var modalID = 'close-task-modal';
                var submitBtnID = 'close-task';
                var redirectUrl = '/';
                var successMsgTitle = 'Task Checked!';
                var successMsg = 'Task has been Successfully checked!';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            //Launch counter for running tasks
            @foreach($tasks as $task)
            increment({{ $task->task_id }});
            @endforeach
            @endif

            //Show success action modal
            //$('#success-action-modal').modal('show');

            $(window).load(function () {
                $('#myCarousel').carousel({
                    interval: 5000
                })
            });
        });
    </script>
@endsection