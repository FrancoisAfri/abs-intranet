@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@endsection
@section('content')
	<div class="row">
		@if($activeModules->where('code_name', 'hr')->first())
			<div class="col-md-6 box box-default collapsed-box"  id="empPerformanceRankingWidgetBox">
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
											onchange="divDDEmpPWOnChange(this, $('#emp-top-ten-list'), $('#loading_overlay_emp_performance_ranking'))"
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
				<!-- Loading wheel overlay 
				<div class="overlay" id="loading_overlay_emp_performance_ranking">
					<i class="fa fa-refresh fa-spin"></i>
				</div>-->
				<!-- /.Employees Performance Ranking Widget -->
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
            $(".select2").select2();

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
        });
    </script>
@endsection