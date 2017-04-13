@extends('layouts.main_layout')

@section('content')
	<div class="row">
		<div class="col-md-12">
			<!-- Employee Monthly performance -->
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
			<!-- /.box -->
		</div>
		<!-- /.col -->
	</div>
	<div class="row">
		<div class="col-md-12">
			<!-- company performance -->
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
						<div class="col-md-12">
							<p class="text-center">
								<strong>{{ $topGroupLvl->plural_name }} Performance For {{ date('Y') }}</strong>
							</p>

							<div class="chart">
								<!-- Sales Chart Canvas-->
								<canvas id="divisionsPerformanceChart" style="height: 220px;"></canvas>
							</div>
							<!-- /.chart-responsive -->
						</div>
					</div>
					<!-- /.row -->
				</div>
			</div>
			<!-- /.box -->
		</div>
		<!-- /.col -->
	</div>
@endsection

@section('page_script')
	<!-- ChartJS 1.0.1 -->
	<script src="/bower_components/AdminLTE/plugins/chartjs/Chart.min.js"></script>
	<!-- Admin dashboard charts ChartsJS -->
	<script src="/custom_components/js/admindbcharts.js"></script>

	<script>
		$(function () {
			//Draw employee performance graph
			var empID = parseInt('{{ $user->person->id }}');
			var monthLabels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
			$.get("/api/emp/" + empID + "/monthly-performance",
					function(data) {
						var chartData = perfChartData(data, monthLabels);

						//Create the bar chart
						empPerfChart.Bar(chartData, chartOptions);
					});

			//Draw divisions performance graph
			var divLvl = parseInt('{{ $topGroupLvl->id }}');
			$.get("/api/divlevel/" + divLvl + "/group-performance",
					function(data) {
						//var lavels = ['test1', 'test2'];
						//var results = [60, 85];
						//var chartData = perfChartData(lavels, results);
						var chartData = perfChartData(data['results'], data['labels']);

						//Create the bar chart
						divPerfChart.Bar(chartData, chartOptions);
					});
		});
	</script>
@endsection

						