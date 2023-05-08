@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- Include Date Range Picker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">

    <link rel="stylesheet" href="../../plugins/timepicker/bootstrap-timepicker.min.css">

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
                    <h3 class="box-title">Leave Application</h3>
                    <p id="box-subtitle">Delete leave credit for a specific day</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <!--                    <form name="leave-alloccation-form" class="form-horizontal" method="POST" action="" enctype="multipart/form-data">-->
                <form name="leave-application-form" class="form-horizontal" method="POST" action="/leave/bulk-delete-application">
                    {{ csrf_field() }}

                    <div class="box-body" id="view_users">
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
						@foreach($division_levels as $division_level)
							<div class="form-group manual-field{{ $errors->has('division_level_' . $division_level->level) ? ' has-error' : '' }}">
								<label for="{{ 'division_level_' . $division_level->level }}"
									   class="col-sm-2 control-label">{{ $division_level->name }}</label>
								<div class="col-sm-10">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-black-tie"></i>
										</div>
										<select id="{{ 'division_level_' . $division_level->level }}"
												name="{{ 'division_level_' . $division_level->level }}"
												class="form-control"
												onchange="divDDOnChange(this, null, 'view_users')">
										</select>
									</div>
								</div>
							</div>
						@endforeach
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
                                    <select id="leave_type" name="leave_type" class="form-control">
                                        <option value=" ">*** Select Leave Type ***</option>
                                        @foreach($leaveTypes as $leaveType)
                                            <option value="{{ $leaveType->id }}">{{ $leaveType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
							 <div class="col-xs-4">
                                <div class="form-group from-field {{ $errors->has('time_from') ? ' has-error' : '' }}">
                                    <label for="time_from" class="col-sm-6 control-label">Date</label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control" id="date_applied" name="date_applied" value=""  placeholder="" data-mask>
                                        </div>
                                    </div>
                                </div>
                            </div>
						<div class="col-xs-4">
                                <div class="form-group from-field {{ $errors->has('hours') ? ' has-error' : '' }}">
                                    <label for="hours" class="col-sm-6 control-label">day(s)</label>
                                    <div class="col-sm-">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input type="number" class="form-control" id="hours" name="hours" min="0" step=".01" value="{{ old('hours') }}" placeholder="Enter Hours...">
                                        </div>
                                    </div>
                                </div>
                         </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <input type="submit" id="load-allocation" name="load-allocation" class="btn btn-primary pull-right" value="Submit">
                        </div>
                        <!-- /.box-footer -->
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- End new User Form-->
        <!-- Confirmation Modal -->
        @if(Session('success_application'))
            @include('leave.partials.success_action', ['modal_title' => "Application Successful!", 'modal_content' => session('success_application')])
        @endif
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

    <script src="../../plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>

    <script src="../../plugins/timepicker/bootstrap-timepicker.min.js"></script>
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
            //Initialize Select2 Elements
            $(".select2").select2();
            $('input[name="date_applied"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: false,
            });

            //Load divisions drop down
			/*var parentDDID = '';
			var loadAllDivs = 1;
			var firstDivDDID = null;
			var parentContainer = $('#view_users');
			@foreach($division_levels as $divisionLevel)
				//Populate drop down on page load
				var ddID = '{{ 'division_level_' . $divisionLevel->level }}';
				var postTo = '{!! route('divisionsdropdown') !!}';
				var selectedOption = '';
				//var divLevel = parseInt('{{ $divisionLevel->level }}');
				var incInactive = -1;
				var loadAll = loadAllDivs;
				@if($loop->first)
					var selectFirstDiv = 1;
					var divHeadSpecific = 1;
					loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo, selectFirstDiv, divHeadSpecific, parentContainer);
					firstDivDDID = ddID;
				@else
					loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo, null, null, parentContainer);
				@endif
				//parentDDID
				parentDDID = ddID;
				loadAllDivs = -1;
			@endforeach*/
			
			//Load divisions drop down
			var parentDDID = '';
			var loadAllDivs = 1;
			@foreach($division_levels as $division_level)
			//Populate drop down on page load
			var ddID = '{{ 'division_level_' . $division_level->level }}';
			var postTo = '{!! route('divisionsdropdown') !!}';
			var selectedOption = '';
			var divLevel = parseInt('{{ $division_level->level }}');
			var incInactive = -1;
			var loadAll = loadAllDivs;
			loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo);
			parentDDID = ddID;
			loadAllDivs = -1;
			@endforeach
        });
        
        
    </script>
@endsection