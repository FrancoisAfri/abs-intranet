@extends('layouts.main_layout')

@section('page_dependencies')
        <!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<!-- Select2
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/select2/select2.min.css">-->
<!-- bootstrap file input -->
<link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <i class="fa fa-file pull-right"></i>
                    <h3 class="box-title">New Activity</h3>
                    <p>Enter activity details:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="/education/activity" enctype="multipart/form-data">
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label for="name" class="control-label">Activity Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Enter Activity Name...">
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                                    <label for="code" class="control-label">Activity Code</label>
									<input type="text" class="form-control" id="code" name="code" value="ACT{{ !empty($activityCode->id) ? $activityCode->id : 1}}" placeholder="Enter Activity Code..." disabled>
                                </div>
								<!-- /.form-group -->
                                <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                    <label for="description" class="control-label">Brief Description</label>
                                    <textarea class="form-control" id="description" name="description" placeholder="Enter a Brief Description of Activity..." rows="4">{{ old('description') }}</textarea>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group{{ $errors->has('start_date') ? ' has-error' : '' }}">
                                    <label for="start_date" class="control-label">Start Date</label>
                                    <input type="text" class="form-control datepicker" id="start_date" name="start_date" value="{{ old('start_date') }}" placeholder="Select Activity Start Date...">
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group{{ $errors->has('end_date') ? ' has-error' : '' }}">
                                    <label for="end_date" class="control-label">End Date</label>
                                    <input type="text" class="form-control datepicker" id="end_date" name="end_date" value="{{ old('end_date') }}" placeholder="Select Activity End Date...">
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group{{ $errors->has('topic') ? ' has-error' : '' }}">
                                    <label for="topic" class="control-label">Topic</label>
                                    <input type="text" class="form-control" id="topic" name="topic" value="{{ old('topic') }}" placeholder="Enter Topic...">
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group{{ $errors->has('budget_expenditure') ? ' has-error' : '' }}">
                                    <label for="budget" class="control-label">Budget</label>
                                    <input type="number" class="form-control" id="budget" name="budget" value="{{ old('budget_expenditure') }}" placeholder="Enter Activity Budget...">
                                </div>
                                <!-- /.form-group -->
                                <!-- <div class="form-group{{ $errors->has('programme_id') ? ' has-error' : '' }}">
                                    <label for="programme_id" class="control-label">Programme</label>
                                    <select class="form-control select2" style="width: 100%;" id="programme_id" name="programme_id">
                                        <option value="">*** Select a Programme ***</option>
                                        @foreach($programmes as $programme)
                                            <option value="{{ $programme->id }}"{{ ($programme->id === old('programme_id')) ? ' selected="selected"' : '' }}>{{ $programme->name }}</option>
                                        @endforeach
                                    </select>
                                </div>-->
                                <!-- /.form-group -->
                                <div class="form-group{{ $errors->has('project_id') ? ' has-error' : '' }}">
                                    <label for="project_id" class="control-label">Project</label>
                                    <select class="form-control select2" style="width: 100%;" id="project_id" name="project_id">
                                        <option value="">*** Select a Project ***</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}"{{ ($project->id === old('project_id')) ? ' selected="selected"' : '' }}>{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('actual_cost') ? ' has-error' : '' }}">
                                    <label for="actual_cost" class="control-label">Actual Cost</label>
                                    <input type="number" class="form-control" id="actual_cost" name="actual_cost" value="{{ old('actual_cost') }}" placeholder="Actual Cost of Activity...">
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group{{ $errors->has('facilitator_id') ? ' has-error' : '' }}">
                                    <label for="facilitator_id" class="control-label">Activity Facilitator</label>
                                    <select class="form-control select2" style="width: 100%;" id="facilitator_id" name="facilitator_id">
                                        <option value="">*** Select a Facilitator ***</option>
                                        @foreach($facilitators as $facilitator)
                                            <option value="{{ $facilitator->id }}"{{ ($facilitator->id === old('facilitator_id')) ? ' selected="selected"' : '' }}>{{ $facilitator->first_name . ' ' . $facilitator->surname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group{{ $errors->has('sponsor') ? ' has-error' : '' }}">
                                    <label for="sponsor" class="control-label">External Sponsor</label>
                                    <input type="text" class="form-control" id="sponsor" name="sponsor" value="{{ old('sponsor') }}" placeholder="Sponsor...">
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
                                    <input type="number" class="form-control" id="sponsorship_amount" name="sponsorship_amount" value="{{ old('sponsorship_amount') }}" placeholder="Enter the Value of Sponsorship...">
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group{{ $errors->has('service_provider_id') ? ' has-error' : '' }}">
                                    <label for="service_provider_id" class="control-label">Service Provider</label>
                                    <select class="form-control select2" style="width: 100%;" id="service_provider_id" name="service_provider_id">
                                        <option value="">*** Select a Service Provider ***</option>
                                        @foreach($service_providers as $provider)
                                            <option value="{{ $provider->id }}"{{ ($provider->id === old('service_provider_id')) ? ' selected="selected"' : '' }}>{{ $provider->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group{{ $errors->has('contract_amount') ? ' has-error' : '' }}">
                                    <label for="contract_amount" class="control-label">Contract Value</label>
                                    <input type="number" class="form-control" id="contract_amount" name="contract_amount" value="{{ old('contract_amount') }}" placeholder="Enter the Contract Value...">
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group{{ $errors->has('contract_doc') ? ' has-error' : '' }}">
                                    <label for="contract_doc" class="control-label">Contract Document</label>
                                    <input type="file" id="contract_doc" name="contract_doc" class="file file-loading" data-allowed-file-extensions='["pdf", "docx"]' data-show-upload="false">
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group{{ $errors->has('supporting_doc') ? ' has-error' : '' }}">
                                    <label for="supporting_doc" class="control-label">Other Supporting Document</label>
                                    <input type="file" id="supporting_doc" name="supporting_doc" class="file file-loading" data-allowed-file-extensions='["pdf", "docx", "doc"]' data-show-upload="false">
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" id="cancel" class="btn btn-default"><i class="fa fa-arrow-left"></i> Cancel</button>
                        <button type="submit" class="btn btn-success pull-right"><i class="fa fa-database"></i> Add</button>
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
            <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>

    <!-- bootstrap datepicker -->
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>

    <!-- Start Bootstrap File input -->
    <!-- canvas-to-blob.min.js is only needed if you wish to resize images before upload. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/canvas-to-blob.min.js" type="text/javascript"></script>
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
    <script src="/bower_components/bootstrap_fileinput/js/locales/<lang>.js"></script>
    <!-- End Bootstrap File input -->

    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <script type="text/javascript">
        //Cancel button click event
        document.getElementById("cancel").onclick = function () {
            location.href = "/education/search";
        };
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
            $(window).on('resize', function() {
                $('.modal:visible').each(reposition);
            });
        });
    </script>
@endsection