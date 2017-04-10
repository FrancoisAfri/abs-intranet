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
                <form action="/appraisal/emp/appraisal/save" id="kpi-result-form" name="kpi-result-form" class="form-horizontal" method="POST">
                    {{ csrf_field() }}

                    <input type="hidden" name="hr_person_id" value="{{ $emp->id }}">
                    <input type="hidden" name="appraisal_month" value="{{ $appraisalMonth }}">

                    <div class="box-body">
                        @if($emp->jobTitle && $emp->jobTitle->kpiTemplate && $emp->jobTitle->kpiTemplate->kpi)
                            <div class="row">
                                <div class="col-sm-12">
                                    <p class="lead">Appraisal Month: {{ $appraisalMonth }}</p>
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
                                        <th style="text-align: center;">KPI Weight</th>
                                        <!--<th style="text-align: center;">Score Range</th>-->
                                        <th>Result</th>
                                    </tr>
                                    @foreach ($kpis as $kpi)
                                        <input type="hidden" name="kpi_id[]" value="{{ $kpi->id }}">
                                        @if($loop->first || (isset($prevKPA) && $prevKPA != $kpi->kpa_id))
                                            <?php $prevKPA = 0; ?>
                                            <tr>
                                                <th class="success"><i class="fa fa-caret-right"></i></th>
                                                <th class="success" colspan="6"><i>KPA: {{ $kpi->kpa_name }}<span class="pull-right">KPA Weight: {{ $kpi->kpa_weight . '%' }}</span></i></th>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td style="vertical-align: middle;">{{ $loop->iteration }}</td>
                                            <td style="vertical-align: middle;">{{ $kpi->measurement }}</td>
                                            <td style="vertical-align: middle;">{{ $kpi->indicator }}</td>
                                            <td style="vertical-align: middle;">{{ $kpi->source_of_evidence }}</td>
                                            <td style="text-align: center; vertical-align: middle;">{{ $kpi->weight . '%' }}</td>
                                            <!--<td style="text-align: center;"></td>-->
                                            <td style="vertical-align: middle;">
                                                @if($kpi->kpi_type === 1)
                                                    <input type="number" class="form-control input-sm" id="range_score" name="score[{{ $kpi->id }}]" placeholder="Enter Result" value="{{ count($kpi->results) > 0 ? $kpi->results->first()->score : '' }}">
                                                @elseif($kpi->kpi_type === 2)
                                                    <input type="number" class="form-control input-sm" id="number_score" name="score[{{ $kpi->id }}]" placeholder="Enter Result" value="{{ count($kpi->results) > 0 ? $kpi->results->first()->score : '' }}">
                                                @elseif($kpi->kpi_type === 3)
                                                    <select id="one_to_score" name="score[{{ $kpi->id }}]" class="form-control select2" style="width: 100%;">
                                                        <option value="">Select a Score</option>
                                                        @foreach($kpi->kpiIntScore->sortBy('score') as $score)
                                                            <option value="{{ $score->score }}"{{ (count($kpi->results) > 0 && $kpi->results->first()->score == $score->score) ? ' selected' : '' }}>{{ $score->score }}</option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </td>
                                        </tr>
                                        <?php $prevKPA = $kpi->kpa_id; ?>
                                    @endforeach
                                </table>
                            </div>
                        @else
                            <div class="alert alert-danger alert-dismissible fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-ban"></i> No KPIs found!</h4>
                                <p>Please make sure that the employee you have selected has a template linked to his/her job title and active KPIs linked to his/her template.</p>
                            </div>
                        @endif
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn btn-default pull-left" id="back_button"><i class="fa fa-arrow-left"></i> Back</button>
                        <button type="submit" id="save_result" class="btn btn-primary pull-right"><i class="fa fa-floppy-o"></i> Save Result</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Include add new modal -->
        <!-- Confirmation Modal -->
        @if(Session('success_edit'))
            @include('contacts.partials.success_action', ['modal_title' => "Appraisal Result Saved!", 'modal_content' => session('success_edit')])
        @endif
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

            //Vertically center modals on page
            function reposition() {
                var modal = $(this),
                        dialog = modal.find('.modal-dialog');
                modal.css('display', 'block');

                // Dividing by two centers the modal exactly, but dividing by three
                // or four works better for larger screens.
                dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 2));
            }
            // Reposition when a modal is shown
            $('.modal').on('show.bs.modal', reposition);
            // Reposition when the window is resized
            $(window).on('resize', function() {
                $('.modal:visible').each(reposition);
            });

            //Show success action modal
            @if(Session('success_edit'))
                $('#success-action-modal').modal('show');
            @endif
        });
    </script>
@endsection