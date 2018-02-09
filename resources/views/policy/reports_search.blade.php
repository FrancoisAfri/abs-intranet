@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-8 col-md-offset-2">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-leanpub pull-right"></i>
                    <h3 class="box-title">View report for</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" id="report_form" method="POST" action="/System/policy/reportsearch/">
                    <!-- audits -->
                    {{ csrf_field() }}

                    <div class="box-body" id="policy">

                        @foreach($division_levels as $division_level)
                            <div class="form-group manual-field{{ $errors->has('division_level_' . $division_level->level) ? ' has-error' : '' }}">
                                <label for="{{ 'division_level_' . $division_level->level }}"
                                       class="col-sm-2 control-label">{{ $division_level->name }}</label>

                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-black-tie"></i>
                                        </div>
                                        <select id="{{ 'division_level_' . $division_level->level }}"
                                                name="{{ 'division_level_' . $division_level->level }}"
                                                class="form-control"
                                                onchange="divDDOnChange(this, null, 'policy')">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="form-group {{ $errors->has('hr_person_id') ? ' has-error' : '' }}">
                            <label for="hr_person_id" class="col-sm-2 control-label">Policy Name</label>
                            <div class="col-sm-8">
                                <select class="form-control select2" style="width: 100%;" id="policy_name"
                                        name="policy_name">
                                    <option value="">*** Select a Policy ***</option>
                                    @foreach($policy as $policy)
                                        <option value="{{ $policy->id }}">{{ $policy->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="form-group day-field {{ $errors->has('leave_types_id') ? ' has-error' : '' }}">
                            <label for="days" class="col-sm-2 control-label">Action Date</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control daterangepicker" id="action_date"
                                           name="action_date" value="" placeholder="Select Action Date...">

                                </div>
                            </div>
                        </div>


                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-search-plus"></i>
                            View
                        </button>
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
    <script src="/bower_components/bootstrap_fileinput/js/plugins/canvas-to-blob.min.js"
            type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
    <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
    <!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
    <script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
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
                format: 'DD/MM/YYYY',
                endDate: '-1d',
                autoclose: true
            });
        });
        //Phone mask
        $("[data-mask]").inputmask();

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