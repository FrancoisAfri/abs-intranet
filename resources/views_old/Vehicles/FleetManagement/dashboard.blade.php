@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@endsection
@section('content')
	<div class="row">
		<div class="col-md-6">
			<div class="box box-default">
				<div class="box-header with-border">
				  <h3 class="box-title">Vehicles Status</h3>
				  <div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				  </div>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
				  <div class="row">
					<div class="col-md-8">
					  <div class="chart-responsive">
						<canvas id="pieChart" height="150"></canvas>
					  </div>
					  <!-- ./chart-responsive -->
					</div>
					<!-- /.col -->
					<div class="col-md-4">
					  <ul class="chart-legend clearfix">
						<li><i class="fa fa-circle-o text-green"></i> Active</li>
						<li><i class="fa fa-circle-o text-aqua"></i> Inactive</li>
						<li><i class="fa fa-circle-o text-yellow"></i> Require Approval</li>
						<li><i class="fa fa-circle-o text-red"></i> Rejected</li>
					  </ul>
					</div>
					<!-- /.col -->
				  </div>
				  <!-- /.row -->
				</div>
				<!-- /.box-body -->
				<div class="box-footer no-padding">
				  <ul class="nav nav-pills nav-stacked">
					<li><a href="">Active
					  <span class="pull-right text-red"><i class="fa fa-angle-down"></i> {{$activeVehicles}}</span></a></li>
					<li><a href="">Inactive <span class="pull-right text-green"><i class="fa fa-angle-up"></i> {{$inactivevehicles}}</span></a>
					</li>
					<li><a href="">Require Approval
					  <span class="pull-right text-yellow"><i class="fa fa-angle-left"></i> {{$requiereApprovalVehicles}}</span></a></li>
					  <li><a href="">Rejected
					  <span class="pull-right text-yellow"><i class="fa fa-angle-left"></i> {{$rejectedVehicles}}</span></a></li>
				  </ul>
				</div>
				<!-- /.footer -->
			</div>
		</div>
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
    <script>
        $(function () {
            // hide end button when page load
            // $("#end-button").show();
            //Initialize Select2 Elements
            $(".select2").select2();

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
			// Get context with jQuery - using jQuery's .get() method.
			var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
			var pieChart = new Chart(pieChartCanvas);
			//Get data with ajax
			$.get("/api/vehiclestatusgraphdata",
			function(data) {
				var PieData = vehicleChartData(data['activeVehicles'], data['inactivevehicles'], data['requiereApprovalVehicles'], data['rejectedVehicles']);

				//Create the line chart
				pieChart.Doughnut(PieData, pieOptions);
			});
        });
    </script>
@endsection