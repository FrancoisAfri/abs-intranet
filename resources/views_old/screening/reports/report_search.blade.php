@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/green.css">
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-8 col-md-offset-2">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user pull-right"></i>
                    <h3 class="box-title">Search criteria</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form name="screening-report-form" class="form-horizontal" id="report_form" method="POST">
                    {{ csrf_field() }}
                    <div class="box-body">
						<div class="form-group{{ $errors->has('report_type') ? ' has-error' : '' }}">
							<label for="report_type" class="col-sm-3 control-label"> Type</label>
							<div class="col-sm-9">
								<label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="report_emp" name="report_type" value="1" checked> Employee </label>
								<label class="radio-inline"><input type="radio" id="report_vis" name="report_type" value="2"> Visitors</label>  
							</div>
                        </div>
						<div class="form-group">
                            <label for="division_level_5" class="col-sm-3 control-label">{{$levelsFive->plural_name}}</label>
                            <div class="col-sm-9">
                                <div class="input-group">
									<select class="form-control select2" style="width: 100%;" id="division_level_5" name="division_level_5" required>
                                        <option selected="selected" value="">*** Select a {{$levelsFive->name}} ***</option>
                                            @foreach($companyFive as $five)
                                                <option value="{{ $five->id }}">{{ $five->name }}</option>
                                            @endforeach
                                    </select>
								</div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="division_level_4" class="col-sm-3 control-label">{{$levelsFour->plural_name}}</label>
                            <div class="col-sm-9">
                                <div class="input-group">
									<select class="form-control select2" style="width: 100%;" id="division_level_4" name="division_level_4">
                                        <option selected="selected" value="">*** Select a {{$levelsFour->name}} ***</option>
                                            @foreach($companyFour as $four)
                                                <option value="{{ $four->id }}">{{ $four->name }}</option>
                                            @endforeach
                                    </select>
								</div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="date_captured" class="col-sm-3 control-label">Date Captured</label>
                            <div class="col-sm-9">
                                <div class="input-group">
									<input type="text" class="form-control daterangepicker" id="date_captured" name="date_captured" value="" placeholder="Select Action Date...">
                                </div>
                            </div>
                        </div>
						<div class="form-group employees">
                            <label for="user_id" class="col-sm-3 control-label">Employees</label>
                            <div class="col-sm-9">
                                <div class="input-group">
									<select class="form-control select2" style="width: 100%;" id="user_id" name="user_id">
                                        <option selected="selected" value="0">*** Select an Employee ***</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->user_id }}">{{ $user->first_name.' '.$user->surname}}</option>
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
<!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
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
			$('.rdo-iCheck').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
			$('input').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green',
				increaseArea: '20%' // optional
			});
			hideFields();
			//Date Range picker
            //show/hide fields on radio button toggles (depending on registration type)

            $('#report_emp, #report_vis').on('ifChecked', function(){      
                var allType = hideFields();
               
            });
        });
		//function to hide/show fields depending on the allocation  type
        function hideFields() {
            var allType = $("input[name='report_type']:checked").val();
            if (allType == 1) { //adjsut leave
                 $('.employees').show();
                 $('form[name="screening-report-form"]').attr('action', '/screening/reports/employees');
                 $('#gen-report').val("Submit");        
            }
            else if (allType == 2) { //resert leave
                 $('.employees').hide();
                 $('form[name="screening-report-form"]').attr('action', '/screening/reports/visitors');
                 $('#gen-report').val("Submit"); 
            }
            return allType;      
        }
        //Phone mask
        $("[data-mask]").inputmask();
    </script>
@endsection