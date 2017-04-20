@extends('layouts.main_layout')

@section('content')
	<div class="row">
		<div class="col-md-12">
			<!-- Employee Monthly performance Widget-->
			<div class="box">
				<div class="box-header with-border">
					<h3 class="box-title">Employee Monthly Appraisal</h3>

					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
						</button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
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
			<!-- company performance Widget -->
			<div class="box">
				<div class="box-header with-border">
					<h3 class="box-title">Company Appraisal</h3>

					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
						</button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
					</div>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="row">
						<!-- Chart col -->
						<div class="col-md-8">
							<p class="text-center">
								<strong>{{ $topGroupLvl->plural_name }} Performance For {{ date('Y') }}</strong>
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
			</div>
			<!-- /.box company performance Widget -->
			<!-- Include division performance modal -->
			@include('dashboard.partials.division_4_performance_modal')
			@include('dashboard.partials.division_3_performance_modal')
			@include('dashboard.partials.division_2_performance_modal')
			@include('dashboard.partials.division_1_performance_modal')
			<!-- Include emp list performance modal -->
			@include('dashboard.partials.emp_list_performance_modal')
			<!-- Include emp list performance modal -->
			@include('dashboard.partials.emp_year_performance_modal')
		</div>
		<!-- /.col -->
	</div>
	<div class="row">
		<div class="col-sm-6">
			<!-- Available Perks Widgets -->
			<div class="box box-danger">
				<div class="box-header with-border">
					<h3 class="box-title">Available Perks</h3>

					<div class="box-tools pull-right">
						<!-- <span class="label label-danger">8 New Members</span> -->
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
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
				<!--<div class="box-footer text-center">
					<a href="#" class="uppercase">View All Perks</a>
				</div>-->
				<!-- /.box-footer -->
			</div>
			<!-- /.Available Perks Widgets -->
		</div>
	</div>
@endsection

@section('page_script')
	<!-- ChartJS 1.0.1 -->
	<script src="/bower_components/AdminLTE/plugins/chartjs/Chart.min.js"></script>
	<!-- Admin dashboard charts ChartsJS -->
	<script src="/custom_components/js/admindbcharts.js"></script>

	<script>
		$(function () {
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

			//Draw employee performance graph
			var empID = parseInt('{{ $user->person->id }}');
			var empChartCanvas = $('#empMonthlyPerformanceChart');
			loadEmpMonthlyPerformance(empChartCanvas, empID);

			//Draw divisions performance graph
			var divLevel = parseInt('{{ $topGroupLvl->id }}');
			var rankingList = $('#ranking-list');
			var divChartCanvas = $('#divisionsPerformanceChart');
			loadDivPerformance(divChartCanvas, rankingList, divLevel);

			//Load show available perks on the perks widget
			var perksWidgetList = $('#perks-widget-list');
			loadAvailablePerks(perksWidgetList);

			//show performance of sub division levels on modals
			var i = 1;
			for (i; i <= 4; i++) {
				$('#sub-division-performance-modal-'+i).on('show.bs.modal', function (e) {
					var linkDiv = $(e.relatedTarget);
					var modalWin = $(this);
					subDivOnShow(linkDiv, modalWin);
				});
			}

			//show performance of employees on modals
			$('#emp-list-performance-modal').on('show.bs.modal', function (e) {
				var linkDiv = $(e.relatedTarget);
				var modalWin = $(this);
				empPerOnShow(linkDiv, modalWin);
			});

			//show employee monthly performance on modal
			$('#emp-year-performance-modal').on('show.bs.modal', function (e) {
				var linkDiv = $(e.relatedTarget);
				var empID = parseInt(linkDiv.data('emp_id'));
				var empName = linkDiv.data('emp_name');
				var empChartCanvas = $('#empMonthlyPerformanceModalChart');
				var modalWin = $(this);
				modalWin.find('#emp-year-modal-title').html(empName + '  - Appraisal');
				loadEmpMonthlyPerformance(empChartCanvas, empID);
			});
		});
	</script>
@endsection
