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
                    <i class="fa fa-bug pull-right"></i>
                    <h3 class="box-title">Survey Reports</h3>
                    <p>Select report parameters:</p>
                </div>
                <!-- /.box-header -->

                <!-- Form Start -->
                <form name="survey-report-form" class="form-horizontal" method="POST" action="/survey/reports" >
                    {{ csrf_field() }}

                    <div class="box-body"  id="survey_report">
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
                        <div class="form-group{{ $errors->has('division_level_' . $division_level->level) ? ' has-error' : '' }}">
                            <label for="{{ 'division_level_' . $division_level->level }}"
                                   class="col-sm-2 control-label">{{ $division_level->name }}</label>

                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-black-tie"></i>
                                    </div>
                                    <select id="{{ 'division_level_' . $division_level->level }}"
                                            name="{{ 'division_level_' . $division_level->level }}"
                                            class="form-control select2"
                                            onchange="divDDOnChange(this, null, 'survey_report')"
                                            style="width: 100%;">
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endforeach
                        <div class="form-group emp-field{{ $errors->has('hr_person_id') ? ' has-error' : '' }}">
                            <label for="hr_person_id" class="col-sm-2 control-label">Employee(s)</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <select id="hr_person_id" name="hr_person_id" class="form-control select2" style="width: 100%;">
                                        <option value="">*** Select an Employee ***</option>}
                                        option
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row emp-field">
                            <div class="col-xs-6">
                                <div class="form-group {{ $errors->has('date_from') ? ' has-error' : '' }}">
                                    <label for="date_from" class="col-sm-4 control-label">From</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control" id="date_from" name="date_from" value="{{ old('date_from') }}" placeholder="Select Date...">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group {{ $errors->has('date_to') ? ' has-error' : '' }}">
                                    <label for="date_to" class="col-sm-3 control-label">To</label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control" id="date_to" name="date_to" value="{{ old('date_to') }}" placeholder="Select Date...">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--
                        <div class="form-group ranking-field{{ $errors->has('ranking_limit') ? ' has-error' : '' }}">
                            <label for="ranking_limit" class="col-sm-2 control-label">Ranking Limit</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-sort-numeric-asc"></i>
                                    </div>
                                    <input type="number" class="form-control" id="ranking_limit" name="ranking_limit" value="10" placeholder="Ranking Limit">
                                </div>
                            </div>
                        </div>
                        -->
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn btn-default pull-left" id="back_button"><i class="fa fa-arrow-left"></i> Cancel</button>
                        <button type="submit" id="gen_report" class="btn btn-primary pull-right"><i class="fa fa-check"></i> Generate Report</button>
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
    <!-- iCheck 
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <!-- Ajax form submit 
    <script src="/custom_components/js/modal_ajax_submit.js"></script>-->
    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
            //Cancel button click event
            $('#back_button').click(function () {
                location.href = '/';
            });
            //Date picker
            $('#date_from').datepicker({
                format: 'd MM yyyy',
                autoclose: true,
                //startView: "months",
                //minViewMode: "months",
                todayHighlight: true
            });
            $('#date_to').datepicker({
                format: 'd MM yyyy',
                autoclose: true,
                //startView: "months",
                //minViewMode: "months",
                todayHighlight: true
            });
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
    </script>
@endsection
