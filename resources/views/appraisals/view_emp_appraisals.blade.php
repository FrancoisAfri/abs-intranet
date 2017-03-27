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
                    <h3 class="box-title">Employee Appraisal Results</h3>
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
                        <div class="row">
                            <div class="col-sm-12">
                                <!--
                                <ul class="products-list product-list-in-box text-muted well well-sm no-shadow">
                                    <li class="item">
                                        <div class="product-img">
                                            <img src="{{ (!empty($emp->profile_pic)) ?
                                                Storage::disk('local')->url("avatars/$emp->profile_pic") :
                                                (($emp->gender === 0) ? $f_silhouette : $m_silhouette) }}" alt="Profile Picture">
                                        </div>
                                        <div class="product-info">
                                            <!--<a href="{{ '/users/' . $emp->user_id . '/edit' }}" class="product-title">--
                                            <strong>{{ $emp->first_name . ' ' . $emp->surname }}</strong>
                                            <!--</a>--
                                            <span class="label label-primary pull-right">Appraisal Month: </span>
                                                <span class="product-description">
                                                    @if(!empty($emp->email))
                                                        <i class="fa fa-envelope-o"></i> {{ $emp->email }}
                                                    @endif
                                                    @if(!empty($emp->position) && count($emp) > 0)
                                                        &nbsp; {{ ' | ' }} &nbsp; <i class="fa fa-user-circle"></i> {{ $emp->jobTitle->name }}
                                                    @endif
                                                </span>
                                        </div>
                                    </li>
                                </ul>
                                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                    <img src="{{ (!empty($emp->profile_pic)) ?
                                                Storage::disk('local')->url("avatars/$emp->profile_pic") :
                                                (($emp->gender === 0) ? $f_silhouette : $m_silhouette) }}" alt="Profile Picture"
                                         style="max-height: 50px;" class="img-responsive img-thumbnail pull-left">
                                    <strong class="lead">Financed Asset Description</strong><br>
                                    <strong>Asset Type:</strong> <em>Lorem ipsum</em> &nbsp; &nbsp;
                                    @if(1 == 1)
                                        | &nbsp; &nbsp; <strong>Make:</strong> <em>Lorem ipsum</em> &nbsp; &nbsp;
                                    @endif
                                </p>
                                -->
                            </div>
                        </div>
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
                        <div style="overflow-x:auto;">
                            <table class="table table-striped">
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Measurement</th>
                                    <th>Indicator</th>
                                    <th>Source of Evidence</th>
                                    <th>KPA (Weight)</th>
                                    <th style="text-align: center;">KPI Weight</th>
                                    <th>Result</th>
                                </tr>
                                @foreach ($emp->jobTitle->kpiTemplate->kpi as $kpi)
                                    <tr>
                                        <td style="vertical-align: middle;"></td>
                                        <td style="vertical-align: middle;">{{ $kpi->measurement }}</td>
                                        <td style="vertical-align: middle;">{{ $kpi->indicator }}</td>
                                        <td style="vertical-align: middle;">{{ $kpi->source_of_evidence }}</td>
                                        <td style="vertical-align: middle;">{{ $kpi->kpiskpas->name . ' (' . $kpi->kpiskpas->weight . '%)' }}</td>
                                        <td style="text-align: center; vertical-align: middle;">{{ $kpi->weight . '%' }}</td>
                                        <td style="vertical-align: middle; width: 40px;">
                                            @if($kpi->kpi_type === 1)
                                                <input type="text" class="form-control input-sm" id="range_score" name="score" placeholder="Enter Result" value="">
                                            @elseif($kpi->kpi_type === 2)
                                                <input type="text" class="form-control input-sm" id="number_score" name="score" placeholder="Enter Result" value="">
                                            @elseif($kpi->kpi_type === 3)
                                                <select id="one_to_score" name="score" class="form-control select2" style="width: 100%;">
                                                    <option value="">Select a Score</option>
                                                    @foreach($kpi->kpiIntScore as $score)
                                                        <option value="{{ $score->score }}">{{ $score->score }}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn btn-default pull-left" id="back_button"><i class="fa fa-arrow-left"></i> Back</button>
                        <button type="button" id="load-kpis" class="btn btn-primary pull-right"><i class="fa fa-floppy-o"></i> Save Result</button>
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
        });
    </script>
@endsection