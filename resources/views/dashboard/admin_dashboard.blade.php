@extends('layouts.main_layout')

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header with-border">
					<h3 class="box-title">Monthly Appraisal</h3>

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
								<strong>My performance for: {{ date('Y') }}</strong>
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

						//Create the line chart
						empPerfChart.Bar(chartData, chartOptions);
					});
		});
	</script>
@endsection

						