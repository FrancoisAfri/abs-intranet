@extends('layouts.main_layout')

@section('page_dependencies')
        <!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<!-- Select2
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/select2/select2.min.css">-->
<!-- bootstrap file input -->
<link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <i class="fa fa-file pull-right"></i>
                    <h3 class="box-title">Activity</h3>
                    <p>Activity details:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{ '/education/activity/'.$activity->id.'/approve' }}">
                    {{ csrf_field() }}

                    <div class="box-body">
                        <div class="callout {{ $status_labels[$activity->status] }}">
                            <h4><i class="fa fa-info-circle"></i> Activity Status</h4>

                            <p>{{ $status_strings[$activity->status] }}{{ ($activity->status === -1) ? ' for the following reason: ' . $activity->rejection_reason : '.' }}</p>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label for="name" class="control-label">Activity Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           value="{{ $activity->name }}" readonly>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                                    <label for="code" class="control-label">Activity Code</label>
                                    <input type="text" class="form-control" id="code" name="code"
                                           value="{{ $activity->code }}" readonly>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group{{ $errors->has('start_date') ? ' has-error' : '' }}">
                                    <label for="start_date" class="control-label">Start Date</label>
                                    <input type="text" class="form-control" id="start_date" name="start_date"
                                           value="{{ !empty($activity->start_date) ? date('d/m/Y', $activity->start_date) : '' }}"
                                           readonly>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group{{ $errors->has('end_date') ? ' has-error' : '' }}">
                                    <label for="end_date" class="control-label">End Date</label>
                                    <input type="text" class="form-control" id="end_date" name="end_date"
                                           value="{{ !empty($activity->end_date) ? date('d/m/Y', $activity->end_date) : '' }}"
                                           readonly>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group{{ $errors->has('topic') ? ' has-error' : '' }}">
                                    <label for="topic" class="control-label">Topic</label>
                                    <input type="text" class="form-control" id="topic" name="topic"
                                           value="{{ $activity->topic }}" readonly>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group{{ $errors->has('budget_expenditure') ? ' has-error' : '' }}">
                                    <label for="budget" class="control-label">Budget</label>
                                    <input type="text" class="form-control" id="budget" name="budget"
                                           value="{{ !empty($activity->budget) ? 'R ' . number_format($activity->budget, 2) : '' }}"
                                           readonly>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group{{ $errors->has('programme_id') ? ' has-error' : '' }}">
                                    <label for="programme_id" class="control-label">Programme</label>
                                    <input type="text" class="form-control" id="programme_id" name="programme_id"
                                           value="{{ !empty($activity->programme->id) ? $activity->programme->name : '' }}"
                                           readonly>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group{{ $errors->has('project_id') ? ' has-error' : '' }}">
                                    <label for="project_id" class="control-label">Project</label>
                                    <input type="text" class="form-control" id="project_id" name="project_id"
                                           value="{{ !empty($activity->project->id) ? $activity->project->name : '' }}"
                                           readonly>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                    <label for="description" class="control-label">Brief Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="4"
                                              readonly>{{ $activity->description }}</textarea>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('actual_cost') ? ' has-error' : '' }}">
                                    <label for="actual_cost" class="control-label">Actual Cost</label>
                                    <input type="text" class="form-control" id="actual_cost" name="actual_cost"
                                           value="{{ !empty($activity->actual_cost) ? 'R ' . number_format($activity->actual_cost, 2) : '' }}"
                                           readonly>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group{{ $errors->has('facilitator_id') ? ' has-error' : '' }}">
                                    <label for="facilitator_id" class="control-label">Activity Facilitator</label>
                                    <input type="text" class="form-control" id="facilitator_id" name="facilitator_id"
                                           value="{{ !empty($activity->facilitator->id) ? $activity->facilitator->first_name . ' ' . $activity->facilitator->surname : '' }}"
                                           readonly>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group{{ $errors->has('sponsor') ? ' has-error' : '' }}">
                                    <label for="sponsor" class="control-label">Sponsor</label>
                                    <input type="text" class="form-control" id="sponsor" name="sponsor"
                                           value="{{ $activity->sponsor }}" readonly>
                                </div>
                                <!-- /.form-group -->
                                <!--
                                <div class="form-group">
                                    <label for="sponsor_id" class="control-label">Sponsor</label>
                                    <select class="form-control select2" style="width: 100%;" id="sponsor_id" name="sponsor_id">
                                        <option selected="selected">*** Select a Sponsor ***</option>
                                        <option>Sponsor One</option>
                                        <option>Sponsor Two</option>
                                    </select>
                                </div>
                                -->
                                <!-- /.form-group -->
                                <div class="form-group{{ $errors->has('sponsorship_amount') ? ' has-error' : '' }}">
                                    <label for="sponsorship_amount" class="control-label">Value of Sponsorship</label>
                                    <input type="text" class="form-control" id="sponsorship_amount"
                                           name="sponsorship_amount"
                                           value="{{ !empty($activity->sponsorship_amount) ? 'R ' . number_format($activity->sponsorship_amount, 2) : '' }}"
                                           readonly>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group{{ $errors->has('service_provider_id') ? ' has-error' : '' }}">
                                    <label for="service_provider_id" class="control-label">Service Provider</label>
                                    <input type="text" class="form-control" id="service_provider_id"
                                           name="service_provider_id"
                                           value="{{ !empty($activity->serviceProvider->id) ? $activity->serviceProvider->name : '' }}"
                                           readonly>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group{{ $errors->has('contract_amount') ? ' has-error' : '' }}">
                                    <label for="contract_amount" class="control-label">Contract Value</label>
                                    <input type="text" class="form-control" id="contract_amount" name="contract_amount"
                                           value="{{ !empty($activity->contract_amount) ? 'R ' . number_format($activity->contract_amount, 2) : '' }}"
                                           readonly>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group{{ $errors->has('contract_doc') ? ' has-error' : '' }}">
                                    <label for="contract_doc" class="control-label">Contract Document</label>
                                    @if(!empty($contract_doc))
                                        <br><a class="btn btn-default btn-flat btn-block" href="{{ $contract_doc }}"
                                               target="_blank"><i class="fa fa-file-pdf-o"></i> Click Here To View The
                                            Document</a>
                                    @else
                                        <br><a class="btn btn-default btn-flat btn-block"><i
                                                    class="fa fa-exclamation-triangle"></i> Nothing Was Uploaded</a>
                                    @endif
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group{{ $errors->has('supporting_doc') ? ' has-error' : '' }}">
                                    <label for="supporting_doc" class="control-label">Other Supporting Document</label>
                                    @if(!empty($supporting_doc))
                                        <br><a class="btn btn-default btn-flat btn-block" href="{{ $supporting_doc }}"
                                               target="_blank"><i class="fa fa-file-pdf-o"></i> Click Here To View The
                                            Document</a>
                                    @else
                                        <br><a class="btn btn-default btn-flat btn-block"><i
                                                    class="fa fa-exclamation-triangle"></i> Nothing Was Uploaded</a>
                                    @endif
                                </div>
                                <!-- /.form-group -->
                                @if($activity->status === 3)
                                    <div class="form-group">
                                        <label>Comment</label>
                                        <textarea class="form-control" id="comment" name="comment" rows="4"
                                                  readonly>{{ $activity->comment }}</textarea>
                                    </div>
                                    <!-- /.form-group -->
                                @endif
                            </div>
                            <!-- /.col -->
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer" style="text-align: center;">
                        @if($show_edit)
                            <a href="/education/programme/edit" class="btn btn-primary"><i
                                        class="fa fa-pencil-square-o"></i> Edit</a>
                        @endif
                        @if($show_complete)
                            <button type="button" id="reject" class="btn btn-primary" data-toggle="modal"
                                    data-target="#comment-modal"><i class="fa fa-level-up"></i> Complete
                            </button>
                        @endif
                        @if($show_approve)
                            <button type="button" id="reject" class="btn btn-danger pull-left" data-toggle="modal"
                                    data-target="#rejection-reason-modal"><i class="fa fa-times"></i> Reject
                            </button>
                            <button type="submit" id="approve" class="btn btn-success pull-right"><i
                                        class="fa fa-check"></i> Approve
                            </button>
                        @endif
                        @if($activity->status === 2)
                            <a href="{!! route('learnerregistration', ['project' => $activity->project_id, 'activity'=> $activity->id]) !!}"
                               class="btn btn-primary"><i class="fa fa-user-plus"></i> Add Learner</a>
                            <a href="{!! route('educatorregistration', ['project' => $activity->project_id, 'activity'=> $activity->id]) !!}"
                               class="btn btn-primary"><i class="fa fa-user-plus"></i> Add Educator</a>
                        @endif
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- End new User Form-->

        <!-- script to add learners -->


        <!--  -->


        @if($show_approve)
        @include('education.partials.programme_rejection_reason', ['modal_title' => 'Why Are You Rejecting This Activity?'])
        @endif
        @if($show_complete)
        @include('education.partials.comment', ['modal_title' => 'Any Comments on This Activity?'])
        @endif

                <!-- Confirmation Modal -->
        @if(Session('success_edit'))
            @include('contacts.partials.success_action', ['modal_title' => "Programme Details Updated!", 'modal_content' => session('success_edit')])
        @elseif(Session('success_add'))
            @include('contacts.partials.success_action', ['modal_title' => "New Programme Added!", 'modal_content' => session('success_add')])
        @elseif(Session('success_approve'))
            @include('contacts.partials.success_action', ['modal_title' => "Programme Approved!", 'modal_content' => session('success_approve')])
        @endif
    </div>
    @if(in_array($activity->status, [2, 3]))
        <div class="row">
            <div class="col-sm-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Expenditures</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-remove"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <th>Date</th>
                                <th>Supplier/Payee</th>
                                <th style="text-align: center;">Supporting Document</th>
                                <th style="text-align: right;">Amount</th>
                            </tr>
                            @foreach($activity->expenditure as $expenditure)
                                <tr>
                                    <td>{{ ($expenditure->date_added) ? date('d/m/Y', $expenditure->date_added) : '' }}</td>
                                    <td>{{ ($expenditure->payee) ? $expenditure->payee : '' }}</td>
                                    <td style="text-align: center;">
                                        @if(!empty($expenditure->supporting_doc))
                                            <a class="btn btn-default btn-flat btn-xs"
                                               href="{{ Storage::disk('local')->url("activity/expenditure/$expenditure->supporting_doc") }}"
                                               target="_blank"><i class="fa fa-file-pdf-o"></i> Click Here To View The
                                                Document</a>
                                        @else
                                            <a class="btn btn-default btn-flat btn-xs"><i
                                                        class="fa fa-exclamation-triangle"></i> Nothing Was Uploaded</a>
                                        @endif
                                    </td>
                                    <td style="text-align: right;">{{ ($expenditure->amount) ? 'R ' . number_format($expenditure->amount) : '' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    @if($activity->status === 2)
                        <div class="box-footer">
                            <button type="button" id="add-expenditure" class="btn btn-success pull-right"
                                    data-toggle="modal" data-target="#add-expenditure-modal">Add Expenditure
                            </button>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-sm-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Incomes</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-remove"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->

                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <th>Date</th>
                            <th>Supplier/Payee</th>
                            <th style="text-align: center;">Supporting Document</th>
                            <th style="text-align: right;">Amount</th>
                        </tr>
                        @foreach($activity->income as $income)
                            <tr>
                                <td>{{ ($income->date_added) ? date('d/m/Y', $income->date_added) : '' }}</td>
                                <td>{{ ($income->payer) ? $income->payer : '' }}</td>
                                <td style="text-align: center;">
                                    @if(!empty($income->supporting_doc))
                                        <a class="btn btn-default btn-flat btn-xs"
                                           href="{{ Storage::disk('local')->url("activity/income/$incomw->supporting_doc") }}"
                                           target="_blank"><i class="fa fa-file-pdf-o"></i> Click Here To View The
                                            Document</a>
                                    @else
                                        <a class="btn btn-default btn-flat btn-xs"><i
                                                    class="fa fa-exclamation-triangle"></i> Nothing Was Uploaded</a>
                                    @endif
                                </td>
                                <td style="text-align: right;">{{ ($income->amount) ? 'R ' . number_format($income->amount) : '' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <!-- /.box-body -->
                    @if($activity->status === 2)
                        <div class="box-footer">
                            <button type="button" id="add-income" class="btn btn-success pull-right" data-toggle="modal"
                                    data-target="#add-income-modal">Add Income
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Include add expenditure and add income modals -->
            @if($activity->status === 2)
                @include('education.partials.add_expenditure', ['modal_title' => 'Add Expenditure For This Activity'])
                @include('education.partials.add_income', ['modal_title' => 'Add Income For This Activity'])
            @endif
        </div>
        @endif
        @endsection

        @section('page_script')
                <!-- Select2 -->
        <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>

        <!-- bootstrap datepicker -->
        <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>

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
        <!-- optionally if you need translation for your language then include locale file as mentioned below -->
        <!--<script src="/bower_components/bootstrap_fileinput/js/locales/<lang>.js"></script>-->
        <!-- End Bootstrap File input -->

        <!-- InputMask -->
        <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
        <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
        <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

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

                //Phone mask
                $("[data-mask]").inputmask();

                //Date picker
                $('.datepicker').datepicker({
                    format: 'dd/mm/yyyy',
                    autoclose: true
                });

                // [bootstrap file input] initialize with defaults
                $("#input-1").fileinput();
                // with plugin options
                //$("#input-id").fileinput({'showUpload':false, 'previewFileType':'any'});

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
                $(window).on('resize', function () {
                    $('.modal:visible').each(reposition);
                });

                //Show success action modal
                $('#success-action-modal').modal('show');

                //Submit rejection form to serve with ajax
                $('#submit-rejection-reason').on('click', function () {
                    var strUrl = '{{ '/education/activity/' . $activity->id . '/reject' }}';
                    var objData = {
                        rejection_reason: $('#rejection_reason').val(),
                        _token: $('input[name=_token]').val()
                    };
                    var modalID = 'rejection-reason-modal';
                    var submitBtnID = 'submit-rejection-reason';
                    var redirectUrl = '{{ '/education/activity/' . $activity->id . '/view' }}';
                    var successMsgTitle = 'Activity Rejected!';
                    var successMsg = 'The activity has been rejected. An email stating the rejection reason(s) has been sent to the person who loaded this activity.';
                    modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                });

                //Submit comment (complete) form to serve with ajax
                $('#submit-comment').on('click', function () {
                    var strUrl = '{{ '/education/activity/' . $activity->id . '/complete' }}';
                    var objData = {
                        comment: $('#comment').val(),
                        _token: $('input[name=_token]').val()
                    };
                    var modalID = 'comment-modal';
                    var submitBtnID = 'submit-comment';
                    var redirectUrl = '{{ '/education/activity/' . $activity->id . '/view' }}';
                    var successMsgTitle = 'Activity Completed!';
                    var successMsg = 'The activity has been completed. An email notification has been sent to the Education and Learning Manager.';
                    modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                });

                //Submit programme expenditures form to server with ajax
                $('#save-expenditure').on('click', function () {
                    var strUrl = '{{ '/education/activity/' . $activity->id . '/addexpenditure' }}';
                    var objData = {
                        amount: $('#amount').val(),
                        date_added: $('#date_added').val(),
                        supplier_name: $('#supplier_name').val(),
                        supporting_doc: $('#supporting_doc').val(),

                        _token: $('input[name=_token]').val()
                    };
                    var modalID = 'add-expenditure-modal';
                    var submitBtnID = 'save-expenditure';
                    var redirectUrl = '{{ '/education/activity/' . $activity->id . '/view' }}'; // live it like that
                    var successMsgTitle = 'expenditure Updated successfully!';
                    var successMsg = 'The expenditure has been successfully added.';
                    var errorAlert = 'invalid-exp-input-alert';
                    modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, errorAlert);
                });


                //Submit programme income form to server with ajax

                $('#save_income').on('click', function () {
                    var strUrl = '{{ '/education/activity/' . $activity->id . '/addincome' }}';
                    var objData = {
                        inc_amount: $('#inc_amount').val(),
                        inc_date_added: $('#inc_date_added').val(),
                        inc_supplier_name: $('#inc_supplier_name').val(),
                        supporting_doc: $('#inc_supporting_doc').val(),

                        _token: $('input[name=_token]').val()
                    };
                    var modalID = 'add-income-modal';
                    var submitBtnID = 'save-income';
                    var redirectUrl = '{{ '/education/activity/' . $activity->id . '/view' }}'; // live it like that
                    var successMsgTitle = 'income Updated successfully!';
                    var successMsg = 'The income has been successfully added.';
                    var errorAlert = 'invalid-exp-input-alert';
                    modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, errorAlert);
                });
            });
        </script>
@endsection