@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- Employee Monthly performance Widget-->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Employee Monthly Appraisal</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-center">
                                <strong>My Performance For {{ date('Y') }}</strong>
                            </p>

                            <div class="chart">
                                <!-- Sales Chart Canvas-->
                                <canvas id="empMonthlyPerformanceChart" style="height: 220px;"></canvas>
                            </div>
                            <!-- /.chart-responsive -->
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
        <!-- /.col -->
    </div>
    <div class="row">
        <div class="col-md-12">
            @if($canViewCPWidget)
            <!-- company performance Widget -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Company Appraisal</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row" id="myStaffPerformanceRankingRow" hidden>
                        <div class="col-md-12">
                            <p class="text-center"><strong>My Staff Performance Ranking For {{ date('Y') }}</strong></p>
                            <div class="no-padding" style="max-height: 420px; overflow-y: scroll;">
                                <ul class="nav nav-pills nav-stacked products-list product-list-in-box" id="my-staff-ranking-list">
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
        </div>
        <!-- /.col -->
    </div>
@if($canViewEmpRankWidget)
    <div class="row">
        <div class="col-md-12">
            <!-- Employees Performance Ranking Widget -->
            <div class="box box-success same-height-widget">
                <div class="box-header with-border">
                    <h3 class="box-title">Employees Ranking</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
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
                                    <label for="{{ 'division_level_' . $divisionLevel->level }}" class="control-label">{{ $divisionLevel->name }}</label>

                                    <select id="{{ 'division_level_' . $divisionLevel->level }}" name="{{ 'division_level_' . $divisionLevel->level }}" class="form-control input-sm select2" onchange="divDDEmpPWOnChange(this, $('#emp-top-ten-list'), $('#emp-bottom-ten-list'), parseInt('{{ $totNumEmp }}'))" style="width: 100%;">
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
                            <ul class="nav nav-pills nav-stacked products-list product-list-in-box" id="emp-top-ten-list">
                            </ul>
                        </div>
                    </div>

                    <!-- Bottom ten -->
                    <div class="col-sm-4">
                        <p class="text-center">
                            <strong class="label label-danger"><i class="fa fa-level-down"></i> Bottom 10 Employees</strong>
                        </p>
                        <div class="no-padding" style="max-height: 274px; overflow-y: scroll;">
                            <ul class="nav nav-pills nav-stacked products-list product-list-in-box"
                                id="emp-bottom-ten-list">
                            </ul>
                        </div>
                    </div>

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.Employees Performance Ranking Widget -->
        </div>
        <!--
        <div class="col-md-6">
            <!-- Bottom Ten Employees Performance Ranking Widgets --
            <div class="box box-danger same-height-widget">
                <div class="box-header with-border">
                    <h3 class="box-title">Employees Ranking</h3>

                    <div class="box-tools pull-right">
                        <span class="label label-danger"><i class="fa fa-level-down"></i> Bottom 10 Employees</span>
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header --
                <div class="box-body no-padding">
                        <div class="no-padding" style="max-height: 274px; overflow-y: scroll;">
                            <ul class="nav nav-pills nav-stacked products-list product-list-in-box"
                                id="emp-bottom-ten-list">
                            </ul>
                        </div>
                        <!-- /.users-list --
                    </div>
                <!-- /.box-body --
            </div>
            <!-- /.Bottom Ten Employees Performance Ranking Widgets --
        </div>
        -->
    </div>
@endif
<!-- /Check if induction is active before showing this  And Meeting-->
	<div class="row">
        <div class="col-md-6">
		 <!-- /Tasks List -->
		  <div class="box box-info">
            <div class="box-header with-border">
			 <i class="ion ion-clipboard"></i>
              <h3 class="box-title">Tasks List</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="max-height: 274px; overflow-y: scroll;">
              <div class="table-responsive">
                <table class="table no-margin">
					<thead>
						<tr>
							<th>Order #</th>
							<th>Description</th>
							<th>Due Date</th>
							<th>Client Name</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					@if (!empty($tasks))
						@foreach($tasks as $task)
						  <tr>
							<td>{{ (!empty($task->order_no)) ?  $task->order_no : ''}}</td>
							<td>{{ (!empty($task->description)) ?  $task->description : ''}}</td>
							<td>{{ (!empty($task->due_date)) ?  date('Y-m-d',$task->due_date) : ''}}</td>
							<td>{{ (!empty($task->client_name)) ?  $task->client_name : ''}}</td>
							<td>
							<!-- @if($isSuperuser)
							{{ $topGroupLvl->plural_name }}
							@elseif($isDivHead)
							{{ $managedDivsLevel->plural_name }}
							@endif -->
							@if(!empty($task->status) && ($task->status == 1 || $task->status == 3))
							  <button type="button" id="start-task" class="btn btn-sm btn-default btn-flat pull-right" onclick="postData({{$task->task_id}}, 'start');">Start</button>
							@elseif(!empty($task->status) && $task->status == 2)                     
							  <button type="button" id="end-task-button" class="btn btn-sm btn-default btn-flat pull-right" data-toggle="modal" data-target="#end-task-modal"
							  data-task_id="{{ $task->task_id }}" data-employee_id="{{ $task->employee_id }}" 
							  data-upload_required="{{ $task->upload_required }}" >End</button>
							  <button type="button" id="pause-task" class="btn btn-sm btn-default btn-flat pull-right" onclick="postData({{$task->task_id}}, 'pause');">Pause</button>
							@endif
							</td>
						  </tr>
						@endforeach
					@endif
                  </tbody>
                </table>
              </div>
				@if(Session('error_starting'))
					@include('tasks.partials.error_tasks', ['modal_title' => "Task Error!", 'modal_content' => session('error_starting')])
				@endif
				@include('tasks.partials.end_task')
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
            </div>
            <!-- /.box-footer -->
          </div>
		  <!-- /Tasks List End -->
        </div>
		<div class="col-md-6">
		 <!-- /Tasks List -->
		  <div class="box box-info">
            <div class="box-header with-border">
			 <i class="ion ion-clipboard"></i>
              <h3 class="box-title">Tasks To Check</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="max-height: 274px; overflow-y: scroll;">
              <div class="table-responsive">
                <table class="table no-margin">
					<thead>
						<tr>
							<th>Employee</th>
							<th>Description</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
					@if (!empty($checkTasks))
						@foreach($checkTasks as $checkTask)
						  <tr>
							<td>{{ (!empty($checkTask->description)) ?  $checkTask->firstname." ".$checkTask->surname : ''}}</td>
							<td>{{ (!empty($checkTask->description)) ?  $checkTask->description : ''}}</td>
							<td>{{ (!empty($checkTask->status)) ?  $taskStatus[$checkTask->status] : ''}}</td>
							<td>
							@if(!empty($checkTask->status) && ($checkTask->status == 1 || $checkTask->status == 3))                    
							  <button type="button" id="close-task-button" class="btn btn-sm btn-default btn-flat pull-right" data-toggle="modal" data-target="#close-task-modal"
							  data-task_id="{{ $checkTask->task_id }}"">Close</button>
							@endif
							</td>
						  </tr>
						@endforeach
					@endif
                  </tbody>
                </table>
              </div>
				@if(Session('error_starting'))
					@include('tasks.partials.error_tasks', ['modal_title' => "Task Error!", 'modal_content' => session('error_starting')])
				@endif
				@include('tasks.partials.end_task')
				@include('tasks.partials.check_task')
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
            </div>
            <!-- /.box-footer -->
          </div>
		  <!-- /Tasks List End -->
        </div>
    </div>
	<div class="row">
        <div class="col-md-6">
            <!-- Available Perks Widgets -->
            <div class="box box-warning same-height-widget">
                <div class="box-header with-border">
                    <h3 class="box-title">Available Perks</h3>

                    <div class="box-tools pull-right">
                        <!-- <span class="label label-warning">8 New Members</span> -->
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <ul class="users-list clearfix" id="perks-widget-list">
                    </ul>
                    <!-- /.users-list -->
                </div>
                <!-- /.box-body -->
                <!-- include perk details modal -->
                @include('appraisals.partials.edit_perk', ['isReaOnly' => true])
            </div>
            <!-- /.Available Perks Widgets -->
        </div>
    </div>
    <!--  -->
     <div class="row">
        <div class="col-md-6">
         <!-- /Tasks List -->
          <div class="box box-info">
            <div class="box-header with-border">
             <i class="fa fa-hourglass"></i>
              <h3 class="box-title">Leave Balance</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="max-height: 274px; overflow-y: scroll;">
              <div class="table-responsive">
                <table class="table no-margin">
                    <thead>
                        <tr>
                            <th>Leave Type</th>

                            <th style="text-align: right;">Leave Balance</th>
                          <!--   <th>Due Date</th>
                            <th>Client Name</th> -->
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                    @if (!empty($balance))
                        @foreach($balance as $task)
                          <tr>
                        <td>{{ (!empty($task->leavetype)) ?  $task->leavetype : ''}}</td>
            <!-- <td style="text-align: right;"><span class="label {{ $statusLabels[$task->leave_balance] }} pull-right"> -->
                <td style="text-align: right;">{{ (!empty($task->leave_balance)) ?  $task->leave_balance : ''}}</td>
                            
                          
                          </tr>
                        @endforeach
                    @endif
                  </tbody>
                </table>
                <div class="box-footer">
                   <!--  <button id="back_to_user_search" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back to search</button> -->
                     <button id="Apply"class="btn btn-primary pull-right"><i class="fa fa-cloud-download"></i> Apply For Leave</button>
                </div>
              </div>
                @if(Session('error_starting'))
                    @include('tasks.partials.error_tasks', ['modal_title' => "Task Error!", 'modal_content' => session('error_starting')])
                @endif
                @include('tasks.partials.end_task')
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /Tasks List End -->
        </div>
        <div class="col-md-6">
         <!-- /Tasks List -->
          <div class="box box-info">
            <div class="box-header with-border">
             <i class="fa fa-hourglass"></i>
              <h3 class="box-title">Leave Applied For Status</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="max-height: 274px; overflow-y: scroll;">
              <div class="table-responsive">
                <table class="table no-margin">
                    <thead>
                        <tr>
                            <th>Leave Type</th>
                            <th>Date From </th>
                             <th>Date To </th>
                            <th style="text-align: right;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if (!empty($application))
                        @foreach($application as $checkTask)
                          <tr>
                            <td>{{ (!empty($checkTask->leavetype)) ?  $checkTask->leavetype : ''}}</td>
                           <!--  <td>{{ (!empty($checkTask->start_date)) ?  $checkTask->start_date : ''}}</td> -->
                             <td>{{ !empty($checkTask->start_date) ? date('d M Y ', $checkTask->start_date) : '' }}</td>
                             <td>{{ !empty($checkTask->end_date) ? date('d M Y ', $checkTask->end_date) : '' }}</td>
                           <td style="text-align: right;">{{ (!empty($checkTask->leaveStatus)) ?  $checkTask->leaveStatus : ''}}</td>
                            <!-- <td>{{ (!empty($checkTask->status)) ?  $taskStatus[$checkTask->status] : ''}}</td> -->
                            <td>
                           
                            </td>
                          </tr>
                        @endforeach
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
     </div>
    <!--  -->
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
    <script>
		function postData(id, data)
		{
			if (data == 'start')
				location.href = "/task/start/" + id;
			else if (data == 'pause')
				location.href = "/task/pause/" + id;
			else if (data == 'end')
				location.href = "/task/end/" + id;
		}
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();

             $('#Apply').click(function () {
                location.href = '/leave/application';
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

            //widgets permissions
            var isSuperuser = parseInt({{ (int) $isSuperuser }}),
                isDivHead = parseInt({{ (int) $isDivHead }}),
                isSupervisor = parseInt({{ (int) $isSupervisor }}),
                canViewCPWidget = parseInt({{ (int) $canViewCPWidget }}),
                canViewEmpRankWidget = parseInt({{ (int) $canViewEmpRankWidget }});

            // Reposition when a modal is shown
            $('.modal').on('show.bs.modal', reposition);
            // Reposition when the window is resized
            $(window).on('resize', function () {
                $('.modal:visible').each(reposition);
            });

            //Employees ranking widget
            if (canViewEmpRankWidget == 1) {
                //Load divisions drop down
                var parentDDID = '';
                var loadAllDivs = 1;
                var firstDivDDID = null;
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
                loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo, selectFirstDiv, divHeadSpecific);
                firstDivDDID = ddID;
                @else
                    loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo);
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

            //Draw employee performance graph
            var empID = parseInt('{{ $user->person->id }}');
            var empChartCanvas = $('#empMonthlyPerformanceChart');
            var loadingWheel = $('#loading_overlay_emp_monthly_appraisal');
            loadEmpMonthlyPerformance(empChartCanvas, empID, loadingWheel);

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
                    var modalWin = $(this);
                    modalWin.find('#emp-year-modal-title').html(empName + '  - Appraisal');
                    loadEmpMonthlyPerformance(empChartCanvas, empID, loadingWheel);
                });
                $('#emp-year-performance-modal').on('hidden.bs.modal', function (e) {
                    $('#lo-emp-year-performance-modal').show();
                });
            }

            //Show available perks on the perks widget
            var perksWidgetList = $('#perks-widget-list');
            loadAvailablePerks(perksWidgetList);

            //leave status (widget)
            var LeaveStatus = $('#leave-status-list');
            //loadLeaveStatus();

            //Show perk details
            $('#edit-perk-modal').on('show.bs.modal', function (e) {
                var perkLink = $(e.relatedTarget);
                var modal = $(this);
                perkDetailsOnShow(perkLink, modal);
            });
			//Show success action modal
            $('#success-action-modal').modal('show');
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
			
            $('#end-task').on('click', function() {
                var strUrl = '/task/end';
                var formName = 'end-task-form';
                var modalID = 'end-task-modal';
                var submitBtnID = 'end-task';
                var redirectUrl = '/';
                var successMsgTitle = 'Task Ended!';
                var successMsg = 'Task has been Successfully ended!';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
			$('#close-task-modal').on('show.bs.modal', function (e) {
                var btnEnd = $(e.relatedTarget);
                taskID = btnEnd.data('task_id');
                var modal = $(this);
                modal.find('#task_id').val(taskID);
            });
			
            $('#close-task').on('click', function() {
                var strUrl = '/task/check';
                var formName = 'close-task-form';
                var modalID = 'close-task-modal';
                var submitBtnID = 'close-task';
                var redirectUrl = '/';
                var successMsgTitle = 'Task Checked!';
                var successMsg = 'Task has been Successfully checked!';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
        });
    </script>
@endsection