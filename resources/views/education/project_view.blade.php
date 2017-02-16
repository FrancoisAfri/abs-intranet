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
                    <h3 class="box-title">Project</h3>
                    <p>Project details:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="/project/{{ $projects->id }}/approve" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="box-body">
                        <div class="callout {{ $status_labels[$projects->status] }}">
                            <h4><i class="fa fa-info-circle"></i> Project Status</h4>

                            <p>{{ $status_strings[$projects->status] }}{{ ($projects->status === -1) ? ' for the following reason: ' . $projects->rejection_reason : '.' }}</p>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="control-label">Project Name:</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           value="{{ $projects->name }}" readonly>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group">
                                    <label for="code" class="control-label">Project Code:</label>
                                    <input type="text" class="form-control" id="code" name="code"
                                           value="{{ $projects->code }}" readonly>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group">
                                    <label for="start_date" class="control-label">Start Date:</label>
                                    <input type="text" class="form-control" id="start_date" name="start_date"
                                           value="{{ !empty($projects->start_date) ? date('d/m/Y', $projects->start_date) : '' }}"
                                           readonly>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group">
                                    <label for="end_date" class="control-label">End Date:</label>
                                    <input type="text" class="form-control" id="end_date" name="end_date"
                                           value="{{ !empty($projects->end_date) ? date('Y M d', $projects->end_date) : '' }}"
                                           readonly>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group">
                                    <label for="programme_id" class="control-label">Programme:</label>
                                    <input type="text" class="form-control" id="programme_id" name="programme_id"
                                           value="{{ !empty($projects->programme->code) ? $projects->programme->name  : '' }}"
                                           readonly>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group">
                                    <label for="facilitator_id" class="control-label">Project Facilitator:</label>
                                    <input type="text" class="form-control" id="facilitator_id" name="facilitator_id"
                                           value="{{ !empty($projects->facilitator->code) ? $projects->facilitator->first_name .' '. $projects->facilitator->surname : '' }}"
                                           readonly>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group">
                                    <label for="manager_id" class="control-label">Project Manager:</label>
                                    <input type="text" class="form-control" id="manager_id" name="manager_id"
                                           value="{{ !empty($projects->manager->code) ? $projects->manager->first_name .' '. $projects->manager->surname : '' }}"
                                           readonly>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group">
                                    <label for="description" class="control-label">Brief Description:</label>
                                    <textarea class="form-control" id="description" name="description" rows="4"
                                              readonly>{{ !empty($projects->description) ? $projects->description  : '' }}</textarea>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="budget" class="control-label">Budget:</label>
                                    <input type="text" class="form-control" id="budget" name="budget"
                                           value="{{ !empty($projects->budget) ? 'R ' . number_format($projects->budget, 2) : '' }}"
                                           readonly>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group">
                                    <label for="sponsor_id" class="control-label">Sponsor:</label>
                                    <input type="text" class="form-control" id="sponsor_id" name="sponsor_id"
                                           value="{{ !empty($projects->sponsor) ? $projects->sponsor  : '' }}" readonly>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group">
                                    <label for="sponsorship_amount" class="control-label">Value of Sponsorship:</label>
                                    <input type="text" class="form-control" id="sponsorship_amount"
                                           name="sponsorship_amount"
                                           value="{{ !empty($projects->sponsorship_amount) ? 'R ' . number_format($projects->sponsorship_amount, 2) : '' }}"
                                           readonly>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group">
                                    <label for="service_provider_id" class="control-label">Service Provider:</label>
                                    <input type="text" class="form-control" id="service_provider_id"
                                           name="service_provider_id"
                                           value="{{ !empty($projects->service_provider->name) ? $projects->service_provider->name  : '' }}"
                                           readonly>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group">
                                    <label for="contract_amount" class="control-label">Contract Value:</label>
                                    <input type="number" class="form-control" id="contract_amount"
                                           name="contract_amount"
                                           value="{{ !empty($projects->contract_amount) ? 'R ' . number_format($projects->contract_amount, 2) : '' }}"
                                           readonly>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group">
                                    <label for="contract_doc" class="control-label">Contract Document:</label>
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
                                <div class="form-group">
                                    <label for="supporting_doc" class="control-label">Other Supporting
                                        Document: </label>
                                    @if(!empty($supporting_doc))
                                        <br><a class="btn btn-default btn-flat btn-block" href="{{ $supporting_doc }}"
                                               target="_blank"><i class="fa fa-file-pdf-o"></i> Click Here To View The
                                            Document</a>
                                    @else
                                        <br><a class="btn btn-default btn-flat btn-block"><i
                                                    class="fa fa-exclamation-triangle"></i> Nothing Was Uploaded</a>
                                    @endif
                                </div>
                                @if(!empty($projects->status) && $projects->status === 3)
                                        <!-- /.form-group -->
                                <div class="form-group">
                                    <label>Comment</label>
                                    <textarea class="form-control" id="comment" name="comment" rows="4"
                                              readonly>{{ $projects->description }}</textarea>
                                </div>
                                @endif
                                        <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer" style="text-align: center;">
                        @if($show_approve)
                            <button type="button" class="btn btn-danger pull-left" id="reject_button" name="command"
                                    value="Reject" data-toggle="modal" data-target="#rejection-reason-modal">
                                <i class="fa fa-times"></i> Reject Project
                            </button>
                            <button type="submit" class="btn btn-success pull-right" id="approve_button" name="command"
                                    value="Approve"><i class="fa fa-check"></i> Approve Project
                            </button>

                            <!--<button type="button" class="btn btn-primary center-block" id="edit_button" name="command" value="Edit">Edit Project</button>-->
                        @endif
                        @if($show_complete)
                            <button type="button" id="complete" class="btn btn-primary" data-toggle="modal"
                                    data-target="#comment-modal"><i class="fa fa-level-up"></i> Complete
                            </button>
                        @endif
                        @if($projects->status == 2)
                            <a href="{!! route('learnerregistration', ['project'=> $projects->id, 'activity' => -1]) !!}"
                               class="btn btn-primary" class="btn btn-primary pull-left"
                               class="btn btn-primary"><i class="fa fa-user-plus"></i> Add Learner</a>
                            <a href="{!! route('educatorregistration', ['project'=> $projects->id, 'activity' => -1]) !!}"
                               class="btn btn-primary" class="btn btn-primary"><i
                                        class="fa fa-user-plus"></i> Add Educator</a>
                        @endif
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- End new User Form-->
        @if ($showButton == 1)
        @include('education.partials.project_rejection_reason')
        @endif
        @if (session('success_modal'))
        @include('education.partials.success_action', ['modal_title' => 'Education and Learning Manager - Project Approved!', 'modal_content' => session('success_modal')])
        @endif
        @if($show_complete)
        @include('education.partials.comment', ['modal_title' => 'Any Comments on This Project?'])
        @endif
                <!-- Confirmation Modal -->
        @if(Session('success_edit'))
            @include('contacts.partials.success_action', ['modal_title' => "Project Details Updated!", 'modal_content' => session('success_edit')])
        @elseif(Session('success_add'))
            @include('contacts.partials.success_action', ['modal_title' => "New Project Added!", 'modal_content' => session('success_add')])
        @elseif(Session('success_approve'))
            @include('contacts.partials.success_action', ['modal_title' => "Project Approved!", 'modal_content' => session('success_approve')])
        @endif
    </div>
    @if($projects->status == 2)
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
                            @foreach($projects->expenditure as $expenditure)
                                <tr>
                                    <td>{{ ($expenditure->date_added) ? date('d/m/Y', $expenditure->date_added) : '' }}</td>
                                    <td>{{ ($expenditure->payee) ? $expenditure->payee : '' }}</td>
                                    <td style="text-align: center;">
                                        @if(!empty($expenditure->supporting_doc))
                                            <a class="btn btn-default btn-flat btn-xs"
                                               href="{{ Storage::disk('local')->url("projects/expenditures/$expenditure->supporting_doc") }}"
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
                    <div class="box-footer">
                        <button type="button" id="add-expenditure" class="btn btn-success pull-right"
                                data-toggle="modal" data-target="#add-expenditure-modal">Add Expenditure
                        </button>
                    </div>
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
                    <div class="box-body">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <th>Date</th>
                                <th>Supplier/Payer</th>
                                <th style="text-align: center;">Supporting Document</th>
                                <th style="text-align: right;">Amount</th>
                            </tr>
                            @foreach($projects->income as $income)
                                <tr>
                                    <td>{{ ($income->date_added) ? date('d/m/Y', $income->date_added) : '' }}</td>
                                    <td>{{ ($income->payer) ? $income->payer : '' }}</td>
                                    <td style="text-align: center;">
                                        @if(!empty($income->supporting_doc))
                                            <a class="btn btn-default btn-flat btn-xs"
                                               href="{{ Storage::disk('local')->url("projects/incomes/$income->supporting_doc") }}"
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
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" id="add-income" class="btn btn-success pull-right" data-toggle="modal"
                                data-target="#add-income-modal">Add Income
                        </button>
                    </div>
                </div>
            </div>

            <!-- Include add expenditure and add income modals -->
            @include('education.partials.add_expenditure', ['modal_title' => 'Add Expenditure For This Project'])
            @include('education.partials.add_income', ['modal_title' => 'Add Income For This Project'])
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

            $(function () {
                //Initialize Select2 Elements
                $(".select2").select2();

                //Phone mask
                $("[data-mask]").inputmask();

                //Date picker
                $('.datepicker').datepicker({
                    format: 'dd/mm/yyyy',
                    autoclose: true,
                    todayHighlight: true
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
                    $.ajax({
                        method: 'POST',
                        url: '{{ '/project/' . $projects->id . '/reject' }}',
                        data: {
                            rejection_reason: $('#rejection_reason').val(),
                            _token: $('input[name=_token]').val()
                        },
                        success: function (success) {
                            //console.log(success);
                            $('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups
                            //$('form[name=set-rates-form]').trigger('reset'); //Reset the form

                            var successHTML = '<button type="button" id="close-invalid-input-alert" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-check"></i> Project Rejected!</h4>';
                            successHTML += 'The Project has been rejected. An email stating the rejection reason(s) has been sent to the applicant.';
                            $('#success-alert').addClass('alert alert-success alert-dismissible')
                                    .fadeIn()
                                    .html(successHTML);

                            //auto hide modal after 7 seconds
                            $("#rejection-reason-modal").alert();
                            window.setTimeout(function () {
                                $("#rejection-reason-modal").modal('hide');
                            }, 5000);

                            //autoclose alert after 7 seconds
                            $("#success-alert").alert();
                            window.setTimeout(function () {
                                $("#success-alert").fadeOut('slow');
                            }, 5000);
                        },
                        error: function (xhr) {
                            //console.log(xhr);
                            //if(xhr.status === 401) //redirect if not authenticated
                            //$( location ).prop( 'pathname', 'auth/login' );
                            if (xhr.status === 422) {
                                var errors = xhr.responseJSON; //get the errors response data
                                //console.log(errors);

                                $('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups

                                var errorsHTML = '<button type="button" id="close-invalid-input-alert" class="close" aria-hidden="true">&times;</button><h4><i class="icon fa fa-ban"></i> Invalid Input!</h4><ul>';
                                $.each(errors, function (key, value) {
                                    errorsHTML += '<li>' + value[0] + '</li>'; //shows only the first error.
                                    $('#' + key).closest('.form-group')
                                            .addClass('has-error'); //Add the has error class to form-groups with errors
                                });
                                errorsHTML += '</ul>';

                                $('#invalid-input-alert').addClass('alert alert-danger alert-dismissible')
                                        .fadeIn()
                                        .html(errorsHTML);

                                //autoclose alert after 7 seconds
                                $("#invalid-input-alert").alert();
                                window.setTimeout(function () {
                                    $("#invalid-input-alert").fadeOut('slow');
                                }, 7000);

                                //Close btn click
                                $('#close-invalid-input-alert').on('click', function () {
                                    $("#invalid-input-alert").fadeOut('slow');
                                });
                            }
                        }
                    });
                });
                //Submit comment (complete) form to serve with ajax
                $('#submit-comment').on('click', function () {
                    var strUrl = '{{ '/education/project/' . $projects->id . '/complete' }}';
                    var objData = {
                        comment: $('#comment').val(),
                        _token: $('input[name=_token]').val()
                    };
                    var modalID = 'comment-modal';
                    var submitBtnID = 'submit-comment';
                    var redirectUrl = '{{ '/project/view/' . $projects->id }}';
                    var successMsgTitle = 'Project Completed!';
                    var successMsg = 'The project has been completed. An email notification has been sent to the General Manager.';
                    modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                });
                //submit project expenditure to server
                $('#save-expenditure').on('click', function () {
                    var strUrl = '{{ '/education/project/' . $projects->id . '/addexpenditure' }}';
                    var objData = {
                        amount: $('#amount').val(),
                        date_added: $('#date_added').val(),
                        supplier_name: $('#supplier_name').val(),
                        supporting_doc: $('#supporting_doc').val(),
                        _token: $('input[name=_token]').val()
                    };
                    var modalID = 'add-expenditure-modal';
                    var submitBtnID = 'save-expenditure';
                    var redirectUrl = '{{ '/project/view/' . $projects->id }}';
                    var successMsgTitle = 'Expenditure Added';
                    var successMsg = 'The expenditure has been successfully added!';
                    modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                });
                //submit project income to server
                $('#save_income').on('click', function () {
                    var strUrl = '{{ '/education/project/' . $projects->id . '/addincome' }}';
                    var objData = {
                        inc_amount: $('#inc_amount').val(),
                        inc_date_added: $('#inc_date_added').val(),
                        inc_supplier_name: $('#inc_supplier_name').val(),
                        inc_supporting_doc: $('#inc_supporting_doc').val(),
                        _token: $('input[name=_token]').val()
                    };
                    var modalID = 'add-income-modal';
                    var submitBtnID = 'save_income';
                    var redirectUrl = '{{ '/project/view/' . $projects->id }}';
                    var successMsgTitle = 'Income Added';
                    var successMsg = 'The income has been successfully added!';
                    modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                });
            });
        </script>
@endsection