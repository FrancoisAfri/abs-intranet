@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Employee Appraisals</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->

                <!-- Form Start -->
                <form name="load-kpi-form" class="form-horizontal" method="POST" action="" enctype="multipart/form-data">
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
                        <div class="form-group{{ $errors->has('appraisal_type') ? ' has-error' : '' }}">
                            <label for="appraisal_type" class="col-sm-2 control-label">Appraisal Type</label>

                            <div class="col-sm-10">
                                <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_upload" name="appraisal_type" value="1"{{ old('appraisal_type') != 2 ? ' checked' : '' }}> File Upload</label>
                                <label class="radio-inline"><input type="radio" id="rdo_manual" name="appraisal_type" value="2" {{ old('appraisal_type') == 2 ? ' checked' : '' }}> Manual</label>
                            </div>
                        </div>
						@foreach($division_levels as $division_level)
							<div class="form-group manual-field{{ $errors->has('division_level_' . $division_level->level) ? ' has-error' : '' }}">
								<label for="{{ 'division_level_' . $division_level->level }}" class="col-sm-2 control-label">{{ $division_level->name }}</label>

                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-black-tie"></i>
                                        </div>
                                        <select id="{{ 'division_level_' . $division_level->level }}" name="{{ 'division_level_' . $division_level->level }}" class="form-control select2" onchange="divDDOnChange(this)" style="width: 100%;">
                                        </select>
                                    </div>
                                </div>
							</div>
						@endforeach
						<div class="form-group manual-field{{ $errors->has('hr_person_id') ? ' has-error' : '' }}">
							<label for="hr_person_id" class="col-sm-2 control-label">Employee</label>

							<div class="col-sm-10">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-user"></i>
									</div>
									<select id="hr_person_id" name="hr_person_id" class="form-control select2" style="width: 100%;">
										<option value="">*** Select an Employee ***</option>
										@foreach($employees as $employee)
											<option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="form-group file-upload-field {{ $errors->has('upload_type') ? ' has-error' : '' }}">
							<label for="upload_type" class="col-sm-2 control-label">Upload Types</label>

							<div class="col-sm-10">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-user"></i>
									</div>
									<select id="upload_type" name="upload_type" class="form-control">
										<option value="">*** Select Upload Type ***</option>
											<option value="1">General</option>
											<option value="2">Clock In</option>
											<option value="3">Query Report </option>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group file-upload-field{{ $errors->has('kpi_id') ? ' has-error' : '' }}">
                            <label for="kpi_id" class="col-sm-2 control-label">KPI</label>

							<div class="col-sm-10">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-user"></i>
									</div>
									<select id="kpi_id" name="kpi_id" class="form-control select2" style="width: 100%;">
										<option value="">*** Select a KPI ***</option>
										@foreach($kpis as $kpi)
											<option value="{{ $kpi->id }}">{{ $kpi->measurement }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
                        <div class="form-group manual-field{{ $errors->has('appraisal_month') ? ' has-error' : '' }}">
                            <label for="appraisal_month" class="col-sm-2 control-label">Appraisal Month</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" name="appraisal_month" id="appraisal_month" placeholder="Click To Select A Month" value="">
                                </div>
                            </div>
                        </div>
						<div class="form-group file-upload-field {{ $errors->has('date_uploaded') ? ' has-error' : '' }}">
                            <label for="date_uploaded" class="col-sm-2 control-label">Date Uploaded</label>
                            <div class="col-sm-10">
                                <div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-user"></i>
								</div>
								<input type="text" class="form-control datepicker" id="date_uploaded" name="date_uploaded" value="" placeholder="Select Date Uploaded...">
                                </div>
                            </div>
                        </div>
						<div class="form-group file-upload-field {{ $errors->has('file_input') ? ' has-error' : '' }}">
                            <label for="file_input" class="col-sm-2 control-label">File input</label>
                            <div class="col-sm-10">
                                <div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-user"></i>
								</div>
								  <input type="file" class="form-control " id="file_input" name="input_file">
								</div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn btn-default pull-left" id="back_button"><i class="fa fa-arrow-left"></i> Back</button>
                        <button type="submit" id="load-kpis" class="btn btn-primary pull-right"><i class="fa fa-cloud-download"></i> Load KPIs</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Include add new modal -->
    </div>
@endsection

@section('page_script')
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- Date Picker -->
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
            //Cancel button click event
            $('#back_button').click(function () {
                location.href = '/appraisal/load_appraisals';
            });
            //Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
            //Date picker
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });
            $('#appraisal_month').datepicker({
                format: 'MM yyyy',
                autoclose: true,
                startView: "months",
                minViewMode: "months",
                todayHighlight: true
            });

            //call hide/show fields functions on doc ready
            hideFields();

            //show/hide file upload or manual fields on radio checked
            $('#rdo_upload, #rdo_manual').on('ifChecked', function(){
                hideFields();
            });

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
        //function to hide/show manual or file upload fields
        function hideFields() {
            var appraisalType = $("input[name='appraisal_type']:checked").val();
            if (appraisalType == 1) { //File upload
                $('.file-upload-field').show();
                $('.manual-field').hide();
                $('form[name="load-kpi-form"]').attr('action', '/appraisal/upload_appraisals');
				$('#load-kpis').val("Upload KPIs");
            }
            else if (appraisalType == 2) { //Manual
                $('.manual-field').show();
                $('.file-upload-field').hide();
                $('form[name="load-kpi-form"]').attr('action', '/appraisal/load_emp_appraisals');
				$('#load-kpis').val("Load KPIs");
            }
            return appraisalType;
        }
    </script>
@endsection
