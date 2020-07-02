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
                    <h3 class="box-title">Appraisal Reports</h3>
                    <p>Select report parameters:</p>
                </div>
                <!-- /.box-header -->

                <!-- Form Start -->
                <form name="load-kpi-form" class="form-horizontal" method="POST" action="/appraisal/reports/result" >
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
                            <div class="form-group{{ $errors->has('report_type') ? ' has-error' : '' }}">
                                <label for="report_type" class="col-sm-2 control-label">Report Type</label>

                                <div class="col-sm-10">
                                    <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_emp" name="report_type" value="1"{{ !old('report_type') ? ' checked' : '' }}{{ old('report_type') == 1 ? ' checked' : '' }}> Employees Appraisal</label>
                                    <label class="radio-inline"><input type="radio" id="rdo_ranking" name="report_type" value="2" {{ old('report_type') == 2 ? ' checked' : '' }}> Employees Ranking</label>
                                    <label class="radio-inline"><input type="radio" id="rdo_divisions" name="report_type" value="3" {{ old('report_type') == 2 ? ' checked' : '' }}> Group Performance</label>
                                </div>
                            </div>
                        @foreach($division_levels as $division_level)
                            <div class="form-group{{ $errors->has('division_level_' . $division_level->level) ? ' has-error' : '' }}{{ $loop->last ? ' last-level' : '' }}">
                                <label for="{{ 'division_level_' . $division_level->level }}" class="col-sm-2 control-label">{{ $division_level->name }}</label>

                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-black-tie"></i>
                                        </div>
                                        <select id="{{ 'division_level_' . $division_level->level }}" name="{{ 'division_level_' . $division_level->level }}" class="form-control select2" onchange="divDDOnChange(this, null, 'view_users')" style="width: 100%">
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
                                    <select id="hr_person_id" name="hr_person_id[]" class="form-control select2" multiple="multiple" data-placeholder="Select at Least One Employee" style="width: 100%;">
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                            <div class="row emp-field" style="display: block;">
                                <div class="col-xs-6">
                                    <div class="form-group {{ $errors->has('date_from') ? ' has-error' : '' }}">
                                        <label for="date_from" class="col-sm-4 control-label">From</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control" id="date_from" name="date_from" value="{{ old('date_from') }}" placeholder="Select Month...">
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
                                                <input type="text" class="form-control" id="date_to" name="date_to" value="{{ old('date_to') }}" placeholder="Select Month...">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ranking-field{{ $errors->has('ranking_type') ? ' has-error' : '' }}">
                                <label for="ranking_type" class="col-sm-2 control-label">Ranking Type</label>

                                <div class="col-sm-10">
                                    <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rank_top" name="ranking_type" value="1"{{ !old('ranking_type') ? ' checked' : '' }}{{ old('ranking_type') == 1 ? ' checked' : '' }}> Top</label>
                                    <label class="radio-inline"><input type="radio" id="rank_bottom" name="ranking_type" value="2" {{ old('ranking_type') == 2 ? ' checked' : '' }}> Bottom</label>
                                </div>
                            </div>
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
                location.href = '/';
            });
            //Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
            //Date picker
            $('#date_from').datepicker({
                format: 'MM yyyy',
                autoclose: true,
                startView: "months",
                minViewMode: "months",
                todayHighlight: true
            });
            $('#date_to').datepicker({
                format: 'MM yyyy',
                autoclose: true,
                startView: "months",
                minViewMode: "months",
                todayHighlight: true
            });

            //call hide/show fields functions on doc ready
            hideFields();

            //show/hide file upload or manual fields on radio checked
            $('#rdo_emp, #rdo_ranking, #rdo_divisions').on('ifChecked', function(){
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

        //function to hide/show report type fields
        function hideFields() {
            var reportType = $("input[name='report_type']:checked").val();
            //console.log(reportType);
            if (reportType == 1) { //Emp Appraisal report
                $('.emp-field').show();
                $('.ranking-field').hide();
                //$('#load-kpi-form').attr('action', '/appraisal/upload_appraisals');
            }
            else if (reportType == 2) { //Ranking report
                $('.ranking-field').show();
                $('.emp-field').hide();
            }
            else if (reportType == 3) { //Divisions report
                $('.emp-field, .ranking-field, .last-level').hide();
            }
            return reportType;
        }
    </script>
@endsection
