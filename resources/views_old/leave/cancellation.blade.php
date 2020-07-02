@extends('layouts.main_layout')
@section('page_dependencies')
<!-- Include Date Range Picker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<!-- iCheck -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
<!-- bootstrap file input -->
<link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
<!--Time Charger-->
@endsection
@section('content')
<div class="row">
    <!-- New User Form -->
    <div class="col-md-12">
        <!-- Horizontal Form -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <i class="fa fa-anchor pull-right"></i>
                <h3 class="box-title">Leave Cancellation</h3>
                <p id="box-subtitle">Cancellation Form</p>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <!--                    <form name="leave-alloccation-form" class="form-horizontal" method="POST" action="" enctype="multipart/form-data">-->
            <form name="leave-application-form" class="form-horizontal" method="POST" action="/leave/admin-cancellation" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="box-body">
                    @if (count($errors) > 0)
                    <div class="alert alert-danger alert-dismissible fade in">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-ban"></i> Invalid Input Data!</h4>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="form-group {{ $errors->has('hr_person_id') ? ' has-error' : '' }}">
                        <label for="hr_person_id" class="col-sm-2 control-label">Employees</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user-circle"></i>
                                </div>
                                <select class="form-control select2" style="width: 100%;" id="hr_person_id" name="hr_person_id">
                                    <option value="">*** Select an Employee ***</option>
                                    @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->surname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('leave_type') ? ' has-error' : '' }}">
                        <label for="leave_type" class="col-sm-2 control-label">Leave Types</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                     <i class="fa fa-black-tie"></i>
                                </div>
                                <select id="leave_type" name="leave_type" onChange= "changetextbox();" class="form-control">
                                    <option value="">*** Select leave Type ***</option>
                                    @foreach($leaveTypes as $leaveType)
                                    <option value="{{ $leaveType->id }}">{{ $leaveType->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
					<div class="form-group {{ $errors->has('manager_id') ? ' has-error' : '' }}">
                        <label for="manager_id" class="col-sm-2 control-label">Manager</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user-circle"></i>
                                </div>
                                <select class="form-control select2" style="width: 100%;" id="manager_id" name="manager_id">
                                    <option value="">*** Select a Manager ***</option>
                                    @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->surname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
					<div class="form-group {{ $errors->has('manager_id') ? ' has-error' : '' }}">
                        <label for="manager_id" class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user-circle"></i>
                                </div>
								<select class="form-control select2" style="width: 100%;" id="status" name="status">
                                    <option value="">*** Select a Status ***</option>
                                    <option value="1">Approved</option>
                                    <option value="2">Require managers approval</option>
                                    <option value="3">Require department head approval</option>
                                    <option value="4">Require hr approval</option>
                                    <option value="6">rejected</option>
                                    <option value="10">Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>
					<div class="form-group">
                            <label for="date_applied" class="col-sm-2 control-label">Date Applied</label>
                            <div class="col-sm-10">
                                <div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-user"></i>
								</div>
								<input type="text" class="form-control daterangepicker" id="date_applied" name="date_applied" value="" placeholder="Select Date Applied...">
                                </div>
                            </div>
                        </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                         <input type="submit" id="load-allocation" name="load-allocation" class="btn btn-primary pull-right" value="Search">
                    </div>
                    <!-- /.box-footer -->
                </div>
            </form>
        </div>
        <!-- /.box -->
    </div>
</div>
@endsection
@section('page_script')
<!-- Select2 -->
<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
<!-- bootstrap datepicker -->
<script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- InputMask -->
<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<script src="/bower_components/bootstrap_fileinput/js/plugins/canvas-to-blob.min.js" type="text/javascript"></script>
<!-- the main fileinput plugin file -->
<!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
<script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
<!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
<script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
<!-- the main fileinput plugin file -->
<script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
<!-- Date rane picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.js"></script>
<!-- iCheck -->
<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
<!-- Ajax dropdown options load -->
<script src="/custom_components/js/load_dropdown_options.js"></script>
<!-- Date picker
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
<!-- Ajax form submit -->
<script src="/custom_components/js/modal_ajax_submit.js"></script>
<script type="text/javascript">
	$(function() {

		//Initialize Select2 Elements
		$(".select2").select2();
		//Phone mask
		$("[data-mask]").inputmask();
				//Date Range picker
		$('.daterangepicker').daterangepicker({
			format: 'dd/mm/yyyy',
			endDate: '-1d',
			autoclose: true
		});
	});
</script>
@endsection