@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- iCheck -->
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/green.css">
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
                <form class="form-horizontal"  id="report_form" method="POST" action="/task/normal_report">
                    {{ csrf_field() }}
                    <div class="box-body">
						<div class="form-group{{ $errors->has('meeting_type') ? ' has-error' : '' }}">
							<label for="meeting_type" class="col-sm-3 control-label">Report Type</label>
							<div class="col-sm-9">
								<div class="input-group">
									<select class="form-control" name="report_type" id="report_type" placeholder="Select Report Type"  onchange="changetype(this.value)" required>
										<option value="" selected>*** Select a Report Type ***</option>
										<option value="1">Induction Tasks</option>
										<option value="2">Meeting Tasks</option>
										<option value="3">Normal Tasks</option>
										<!--<option value="4">Helpdesk Tasks</option>-->
									</select>
								</div>
							</div>
                        </div>
						<div class="form-group inductionTasks" id="clientdiv">
                            <label for="company_id" class="col-sm-3 control-label">Client</label>
                            <div class="col-sm-9">
                                <div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-user"></i>
									</div>
									<select class="form-control select2" id="company_id" name="company_id">
										<option selected="selected" value="0">*** Select a Client ***</option>
										@foreach($companies as $company)
											<option value="{{ $company->id }}">{{ $company->name}}</option>
										@endforeach
									</select> 
								</div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="induction_date" class="col-sm-3 control-label">Due Date</label>
                            <div class="col-sm-9">
                                <div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-user"></i>
								</div>
								<input type="text" class="form-control daterangepicker" id="creation_date" name="creation_date" value="" placeholder="Select Due Date...">
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="induction_date" class="col-sm-3 control-label">Completion Date</label>
                            <div class="col-sm-9">
                                <div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-user"></i>
								</div>
								<input type="text" class="form-control daterangepicker" id="completion_date" name="completion_date" value="" placeholder="Select Completion Date...">
                                </div>
                            </div>
                        </div>
						<div class="form-group inductionTasks">
                            <label for="induction_title" class="col-sm-3 control-label">Induction Title</label>
                            <div class="col-sm-9">
                                <div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-user"></i>
									</div>
									<input type="text" class="form-control" id="induction_title" name="induction_title" placeholder="Enter an Title...">
								</div>
                            </div>
                        </div>
						<div class="form-group meetingTasks">
                            <label for="meeting_name" class="col-sm-3 control-label">Meeting Title</label>
                            <div class="col-sm-9">
                                <div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-user"></i>
									</div>
									<input type="text" class="form-control" id="meeting_name" name="meeting_name" placeholder="Enter an Title...">
								</div>
                            </div>
                        </div>
						<div class="form-group groups">
                            <label for="status" class="col-sm-3 control-label">Status</label>
                            <div class="col-sm-9">
                                <div class="input-group">
									<div class="input-group-addon">
                              			<i class="fa fa-user"></i>
                            		</div>
									<select class="form-control select2" style="width: 100%;" id="status" name="status">
                                        <option selected="selected" value="0">*** Select a Status ***</option>
                                        <option value="1">Not Started</option>
                                        <option value="2">In Progress</option>
                                        <option value="3">Paused</option>
                                        <option value="4">Completed</option>
                                    </select>
                                </div>
                            </div>
                        </div>
						<div class="form-group groups">
                            <label for="employee_id" class="col-sm-3 control-label">Employees</label>
                            <div class="col-sm-9">
                                <div class="input-group">
									<div class="input-group-addon">
                              			<i class="fa fa-user"></i>
                            		</div>
									<select class="form-control select2" style="width: 100%;" id="employee_id" name="employee_id">
                                        <option selected="selected" value="0">*** Select an Employee ***</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->first_name.' '.$user->surname}}</option>
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
    <!-- iCheck -->
	<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>	
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
			//Initialize iCheck/iRadio Elements
			$('input').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green',
				increaseArea: '20%' // optional
			});
			$('#report_form').attr('action', '/task/indtuction_report');
			$('.meetingTasks').hide();
        });
        //Phone mask
        $("[data-mask]").inputmask();
		function changetype(type)
		{
			if (type == 1)
			{
				$('.inductionTasks').show();
				$('.meetingTasks').hide();
				$('#report_form').attr('action', '/task/indtuction_report');
			}
			else if (type == 2)
			{
				$('.inductionTasks').hide();
				$('.meetingTasks').show();
				$('#report_form').attr('action', '/task/meeting_report');
			}
			else if (type == 3)
			{
				$('.inductionTasks').hide();
				$('.meetingTasks').hide();
				$('#clientdiv').show();
				$('#report_form').attr('action', '/task/normal_report');
			}
			/*else if (type == 4)
			{
				$('.programmes').hide();
				$('.projects').hide();
				$('.activities').show();
				$('#report_form').attr('action', '/task/helpesk');
			}	*/
		}
    </script>
@endsection