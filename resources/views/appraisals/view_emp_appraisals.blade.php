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
                <form action="{{ $formAction }}" id="kpi-result-form" name="kpi-result-form" class="form-horizontal" method="POST">
                    {{ csrf_field() }}

                    <input type="hidden" name="hr_person_id" value="{{ $emp->id }}">
                    <input type="hidden" name="appraisal_month" value="{{ $appraisalMonth }}">

                    <div class="box-body">
                        @if($emp->jobTitle && $emp->jobTitle->kpiTemplate && $emp->jobTitle->kpiTemplate->kpi)
                            <div class="row">
                                <div class="col-sm-8">
                                    <ul class="products-list product-list-in-box">
                                        <!-- item -->
                                            <li class="item">
                                                <div class="product-img">
                                                    <img src="{{ $emp->profile_pic_url }}" alt="Profile Picture">
                                                </div>
                                                <div class="product-info">
                                                    <!--<a href="{{ '/users/' . $emp->user_id . '/edit' }}" class="product-title">-->{{ $emp->first_name . ' ' . $emp->surname }}<!--</a>-->
                                                    <!--<span class="label {{ ($emp->status === 1) ? 'label-success' : 'label-danger' }} pull-right">{{ $status_values[$emp->status] }}</span>--><!-- </a> -->
                                                    <span class="product-description">
                                @if(!empty($emp->email))
                                                            <i class="fa fa-envelope-o"></i> {{ $emp->email }}
                                                        @endif
                                                        @if(!empty($emp->position) && count($positions) > 0)
                                                            &nbsp; {{ ' | ' }} &nbsp; <i class="fa fa-user-circle"></i> {{ $positions[$emp->position] }}
                                                        @endif
                            </span>
                                                </div>
                                            </li>
                                    <!-- /.item -->
                                    </ul>
                                </div>
                                <div class="col-sm-4" style="vertical-align: bottom;">
                                    <br>
                                    <p class="lead pull-right">Appraisal Month: {{ $appraisalMonth }}</p>
                                </div>
                            </div>

                            <hr class="hr-text" data-content="KPI APPRAISAL RESULT (GROUPED BY KPA)" style="margin-top: 0;">

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
                                        <th width="150px">Result</th>
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
                                                @if($isEmpAppraisal)
                                                    @if($kpi->kpi_type === 1)
                                                        <input type="number" class="form-control input-sm" id="range_score" name="score[{{ $kpi->id }}]" placeholder="Enter Result" value="{{ count($kpi->empResults) > 0 ? $kpi->empResults->first()->score : '' }}">
                                                    @elseif($kpi->kpi_type === 2)
                                                        <input type="number" class="form-control input-sm" id="number_score" name="score[{{ $kpi->id }}]" placeholder="Enter Result" value="{{ count($kpi->empResults) > 0 ? $kpi->empResults->first()->score : '' }}">
                                                    @elseif($kpi->kpi_type === 3)
                                                        <select id="one_to_score" name="score[{{ $kpi->id }}]" class="form-control select2" style="width: 100%;">
                                                            <option value="">Select a Score</option>
                                                            @foreach($kpi->kpiIntScore->sortBy('score') as $score)
                                                                <option value="{{ $score->score }}"{{ (count($kpi->empResults) > 0 && $kpi->empResults->first()->score == $score->score) ? ' selected' : '' }}>{{ $score->score }}</option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                @else
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
                        @if(! $isEmpAppraisal)
                            <button type="button" class="btn btn-default pull-left" id="back_button"><i class="fa fa-arrow-left"></i> Back</button>
                        @endif
                        <button type="submit" id="save_result" class="btn btn-primary pull-right"><i class="fa fa-floppy-o"></i> Save Result</button>
                    </div>
                </form>
            </div>
        </div>

        @if($showThreeSixtySection)
            <div class="col-sm-8 col-sm-offset-2">
                <!-- Three sixty Employees -->
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">People in Your Three-Sixty</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        @if(count($threeSixtyPeople) > 0)
                            <ul class="users-list clearfix">
                                @foreach($threeSixtyPeople as $threeSixtyPerson)
                                    <li onmouseover="showRemoveLabel(this)" onmouseout="hideRemoveLabel(this)">
                                        <a class="label label-danger pull-right delete-label" href="/appraisal/remove-from-three-sixty-people/{{ $emp->id }}/{{ $threeSixtyPerson->id }}" style="display: none;"><i class="fa fa-trash"></i> Remove</a>
                                        <img src="{{ $threeSixtyPerson->profile_pic_url }}" alt="User Profile Photo">
                                        <a class="users-list-name">{{ $threeSixtyPerson->full_name }}</a>
                                        <span class="users-list-date">{{ $threeSixtyPerson->email }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="callout callout-danger">
                                <h4>No Records Found!</h4>

                                <p>No one has been added to your Three-Sixty Group for this month yet. Please click the button below to add people.</p>
                            </div>
                    @endif
                        <!-- /.users-list -->
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer text-center">
                        <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-three-sixty-person-modal"><i class="fa fa-user-plus"></i> Add Person</button>
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /.Three sixty Employees -->
            </div>
            <!-- Include add new modal -->
            @include('appraisals.partials.add_three_sixty_person')
        @endif

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

            //Submit the three-sixty-people modal form with ajax (add)
            $('#add-three-sixty-person-btn').on('click', function() {
                var strUrl = '/appraisal/add-three-sixty-people/{{ $emp->id }}';
                var formName = 'add-three-sixty-person-form';
                var modalID = 'add-three-sixty-person-modal';
                //var modal = $('#'+modalID);
                var submitBtnID = 'add-three-sixty-person-btn';
                var redirectUrl = '/appraisal/appraise-yourself';
                var successMsgTitle = 'Employee(s) Added!';
                var successMsg = 'The selected employee(s) have been successfully added to your 360 group for this month! They will receive an email with link to your appraisal page.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
        });

        //function to show the remove label
        function showRemoveLabel(listItem){
            $(listItem).find('.delete-label').show();
        }

        //function to show the remove label
        function hideRemoveLabel(listItem){
            $(listItem).find('.delete-label').hide();
        }
    </script>
@endsection