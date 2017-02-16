@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-8 col-md-offset-2">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user pull-right"></i>
                    <h3 class="box-title">Reports Search criteria</h3>
                    <p>Enter search details:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" id="report_form" method="POST">
                    {{ csrf_field() }}

                    <div class="box-body">
						<div class="form-group">
                            <label for="search_type" class="col-sm-3 control-label">Report Type</label>
                            <div class="col-sm-9">
                                <div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-user"></i>
								</div>
								<select class="form-control" name="search_type" id="search_type" placeholder="Select Report Type"  onchange="changetype(this.value)"  required>
									<option value="1">Programme</option>
									<option value="2">Project</option>
									<option value="3">Activity</option>
								</select>
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="search_type" class="col-sm-3 control-label">Start Date</label>
                            <div class="col-sm-9">
                                <div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-user"></i>
								</div>
								<input type="text" class="form-control daterangepicker" id="start_date" name="start_date" value="" placeholder="Select Start Date...">
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="search_type" class="col-sm-3 control-label">End Date</label>
                            <div class="col-sm-9">
                                <div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-user"></i>
								</div>
								<input type="text" class="form-control daterangepicker" id="end_date" name="end_date" value="" placeholder="Select End Date...">
                                </div>
                            </div>
                        </div>
						<div class="form-group programmes">
                            <label for="search_type" class="col-sm-3 control-label">Programme</label>
                            <div class="col-sm-9">
                                <div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-user"></i>
									</div>
									<select class="form-control select2" style="width: 100%;" id="programme_id" name="programme_id">
                                        <option selected="selected" value="0">*** Select a Programme ***</option>
									   @foreach($programme as $programme)
                                            <option value="{{ $programme->id }}">{{ $programme->name }}</option>
                                        @endforeach
                                    </select>
								</div>
                            </div>
                        </div>
						<div class="form-group projects">
                            <label for="search_type" class="col-sm-3 control-label">Programme</label>
                            <div class="col-sm-9">
                                <div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-user"></i>
									</div>
									<select class="form-control select2" style="width: 100%;" id="proj_programme_id" name="programme_id">
                                        <option selected="selected" value="0">*** Select a Programme ***</option>
									   @foreach($programmes as $programme)
                                            <option value="{{ $programme->id }}">{{ $programme->name }}</option>
                                        @endforeach
                                    </select>
								</div>
                            </div>
                        </div>
						<div class="form-group projects">
                            <label for="search_type" class="col-sm-3 control-label">Project</label>
                            <div class="col-sm-9">
                                <div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-user"></i>
									</div>
									<select class="form-control select2" style="width: 100%;" id="proj_project_id" name="project_id">
                                        <option selected="selected" value="0">*** Select a Project ***</option>
                                        @foreach($project as $project)
                                            <option value="{{ $project->id }}">{{ $project->name}}</option>
                                        @endforeach
                                    </select>
								</div>
                            </div>
                        </div>
						<div class="form-group activities">
                            <label for="search_type" class="col-sm-3 control-label">Project</label>
                            <div class="col-sm-9">
                                <div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-user"></i>
									</div>
									<select class="form-control select2" style="width: 100%;" id="project_id" name="project_id">
                                        <option selected="selected" value="0">*** Select a Project ***</option>
                                        @foreach($projects as $projects)
                                            <option value="{{ $projects->id }}">{{ $projects->name}}</option>
                                        @endforeach
                                    </select>
								</div>
                            </div>
                        </div>
						 <div class="form-group activities">
                            <label for="search_type" class="col-sm-3 control-label">Activity</label>
                            <div class="col-sm-9">
                                <div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-user"></i>
									</div>
									<select class="form-control select2" style="width: 100%;" id="project_id" name="project_id">
                                        <option selected="selected" value="0">*** Select an Activity ***</option>
                                        @foreach($activities as $activities)
                                            <option value="{{ $activities->id }}">{{ $activities->name}}</option>
                                        @endforeach
                                    </select>
								</div>
                            </div>
                        </div>
                     </div>   
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-user-plus"></i> Generate</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- End new User Form-->
    </div>
    @endsection

    @section('page_script')
	<!-- Select 2-->
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
            <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <!-- Bootstrap date picker -->
	<script src="/bower_components/AdminLTE/plugins/daterangepicker/moment.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Start Bootstrap File input -->
    <!-- canvas-to-blob.min.js is only needed if you wish to resize images before upload. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/canvas-to-blob.min.js" type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
    <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
    <!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
    <script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
    <!-- optionally if you need translation for your language then include locale file as mentioned below -->
    <!--<script src="/bower_components/bootstrap_fileinput/js/locales/<lang>.js"></script>-->
    <!-- 		//Date picker
		$('.datepicker').datepicker({
			format: 'dd/mm/yyyy',
			endDate: '-1d',
			autoclose: true
            }); -->
    <!-- End Bootstrap File input -->

    <script type="text/javascript">
        //Cancel button click event
        /*document.getElementById("cancel").onclick = function () {
            location.href = "/contacts";
        };*/
		 $(function () {
			//Initialize Select2 Elements
            $(".select2").select2();
		//Date Range picker
		$('.daterangepicker').daterangepicker({
			format: 'dd/mm/yyyy',
			endDate: '-1d',
			autoclose: true
		});
		$('.programmes').show();
		$('.projects').hide();
		$('.activities').hide();
		$('#report_form').attr('action', '/reports/programme/finance');
        });
		function changetype(type)
		{
			if (type == 1)
			{
				$('.programmes').show();
				$('.projects').hide();
				$('.activities').hide();
				$('#report_form').attr('action', '/reports/programme/finance');
			}
			else if (type == 2)
			{
				$('.programmes').hide();
				$('.projects').show();
				$('.activities').hide();
				$('#report_form').attr('action', '/reports/project/finance');
			}
			else if (type == 3)
			{
				$('.programmes').hide();
				$('.projects').hide();
				$('.activities').show();
				$('#report_form').attr('action', '/reports/activity/finance');
			}
				
		}
        //Phone mask
        $("[data-mask]").inputmask();
    </script>
@endsection