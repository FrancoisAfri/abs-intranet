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
    <div class="row">
        <div class="col-md-4">
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
        <div class="col-md-4">
            <!-- Top Ten Employees Performance Ranking Widget -->
            <div class="box box-success same-height-widget">
                <div class="box-header with-border">
                    <h3 class="box-title">Employees Ranking</h3>

                    <div class="box-tools pull-right">
                        <span class="label label-success"><i class="fa fa-level-up"></i> Top 10 Employees</span>
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <div class="no-padding" style="max-height: 274px; overflow-y: scroll;">
                        <ul class="nav nav-pills nav-stacked products-list product-list-in-box" id="emp-top-ten-list">
                        </ul>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.Top Ten Employees Performance Ranking Widgets -->
        </div>
        <div class="col-md-4">
            <!-- Bottom Ten Employees Performance Ranking Widgets -->
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
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <div class="no-padding" style="max-height: 274px; overflow-y: scroll;">
                        <ul class="nav nav-pills nav-stacked products-list product-list-in-box"
                            id="emp-bottom-ten-list">
                        </ul>
                    </div>
                    <!-- /.users-list -->
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.Bottom Ten Employees Performance Ranking Widgets -->
        </div>
    </div>
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
            <div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin">
					<thead>
						<tr>
							<th>Order #</th>
							<th>Description</th>
							<th>Due Date</th>
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
              <!-- /.table-responsive  onclick="postData({{$task->task_id}}, 'end');"-->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
            </div>
            <!-- /.box-footer -->
          </div>
		  <!-- /Tasks List End -->
        </div>
    </div>
    @endsection

    @section('page_script')
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

            //Draw employee performance graph
            var empID = parseInt('{{ $user->person->id }}');
            var empChartCanvas = $('#empMonthlyPerformanceChart');
            loadEmpMonthlyPerformance(empChartCanvas, empID);

            //Company appraisal
            var isSuperuser = parseInt({{ (int) $isSuperuser }}),
                    isDivHead = parseInt({{ (int) $isDivHead }}),
                    isSupervisor = parseInt({{ (int) $isSupervisor }}),
                    canViewCPWidget = parseInt({{ (int) $canViewCPWidget }});
            if (canViewCPWidget == 1) {
                //Draw divisions performance graph [Comp Appraisal Widget]
                var rankingList = $('#ranking-list');
                var divChartCanvas = $('#divisionsPerformanceChart');
                var managerID = parseInt({{ $user->person->id }});
                if (isSuperuser == 1) {
                    var divLevel = parseInt('{{ $topGroupLvl->id }}');
                    loadDivPerformance(divChartCanvas, rankingList, divLevel);
                }
                else if (isDivHead == 1) {
                    var divLevel = parseInt({{ $managedDivsLevel->level }});
                    loadDivPerformance(divChartCanvas, rankingList, divLevel, null, managerID);
                }
                else if (isSupervisor) {
                    $('#topLvlDivGraphAndRankingRow').hide();
                    var staffPerfRow = $('#myStaffPerformanceRankingRow');
                    staffPerfRow.show();
                    rankingList = staffPerfRow.find('#my-staff-ranking-list');
                    loadEmpListPerformance(rankingList, 0, 0, false, false, null, managerID);
                }

                //show performance of sub division levels on modals (modal show) [Comp Appraisal Widget]
                var i = 1;
                for (i; i <= 4; i++) {
                    $('#sub-division-performance-modal-' + i).on('show.bs.modal', function (e) {
                        var linkDiv = $(e.relatedTarget);
                        var modalWin = $(this);
                        subDivOnShow(linkDiv, modalWin);
                    });
                }

                //show performance of employees on modals [Comp Appraisal Widget]
                $('#emp-list-performance-modal').on('show.bs.modal', function (e) {
                    var linkDiv = $(e.relatedTarget);
                    var modalWin = $(this);
                    empPerOnShow(linkDiv, modalWin);
                });

                //show employee monthly performance on modal [Comp Appraisal Widget]
                $('#emp-year-performance-modal').on('show.bs.modal', function (e) {
                    var linkDiv = $(e.relatedTarget);
                    var empID = parseInt(linkDiv.data('emp_id'));
                    var empName = linkDiv.data('emp_name');
                    var empChartCanvas = $('#empMonthlyPerformanceModalChart');
                    var modalWin = $(this);
                    modalWin.find('#emp-year-modal-title').html(empName + '  - Appraisal');
                    loadEmpMonthlyPerformance(empChartCanvas, empID);
                });
            }

            //Show available perks on the perks widget
            var perksWidgetList = $('#perks-widget-list');
            loadAvailablePerks(perksWidgetList);

            //Load top ten performing employees (widget)
            var topTenList = $('#emp-top-ten-list');
            loadEmpListPerformance(topTenList, 0, 0, true);

            //Load Bottom ten performing employees (widget)
            var bottomTenList = $('#emp-bottom-ten-list');
            var totnumEmp = parseInt('{{ $totNumEmp }}');
            loadEmpListPerformance(bottomTenList, 0, 0, false, true, totnumEmp);

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
        });
    </script>
@endsection