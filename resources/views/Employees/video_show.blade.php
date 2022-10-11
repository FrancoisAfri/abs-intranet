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
                    <h3 class="box-title">Assign Video rigts to Users</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->

                <!-- Form Start -->
                <form name="load-kpi-form" class="form-horizontal" method="POST" action="{{ route('video.assign') }}">
                    {{ csrf_field() }}

                    <div class="box-body" id="view_users">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
                                </button>
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
                                                class="form-control" onchange="divDDOnChange(this, null, 'view_users')">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <input type="hidden" name="video_id" value="{{ $video->id }}">

                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn btn-default pull-left" id="back_button"><i
                                    class="fa fa-arrow-left"></i> Back
                        </button>
                        <input type="submit" id="load-kpis" name="load-kpis" class="btn btn-primary pull-right">
                    </div>
                </form>
            </div>
        </div>
        <!-- Include add new modal -->
    </div>
@endsection

@section('page_script')
    <!-- Select2 -->
    <script src=" {{ asset('bower_components/AdminLTE/plugins/select2/select2.full.min.js') }}"></script>
    <!-- Date Picker -->
    <script src=" {{ asset('bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <!-- iCheck -->
    <script src="{{ asset('bower_components/AdminLTE/plugins/iCheck/icheck.min.js') }}"></script>
    <!-- Ajax form submit -->
    <script src="{{asset('custom_components/js/modal_ajax_submit.js')}}"></script>
    <!-- Ajax dropdown options load -->
    <script src="{{ asset('custom_components/js/load_dropdown_options.js') }}"></script>
    <script>
        $(function () {

            document.getElementById("back_button").onclick = function () {
                location.href = "{{ route('video.index')  }}";
            };

            //Initialize Select2 Elements
            $(".select2").select2();

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
