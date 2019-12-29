@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- bootstrap file input -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
@endsection

@section('content')
    <div class="row">
        <!-- User Form -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user pull-right"></i>
                    <h3 class="box-title">User</h3>
                    <p>User details:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/cms/{{$Cmsnews->id }}/update"
                      enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <div class="box-body" id="view_users">
                        <hr class="hr-text" data-content="Edit News for - {{ $Cmsnews->name }} ">
                        <div class="form-group">
                            <label for="path" class="col-sm-2 control-label"> Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="name" name="name"
                                       value="{{ $Cmsnews->name  }}"
                                       placeholder="Enter Name" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="path" class="col-sm-2 control-label"> Description</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="description" name="description"
                                       value="{{ $Cmsnews->description  }}"
                                       placeholder="Enter Description" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exp_date" class="col-sm-2 control-label">Date </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control datepicker" id="exp_date" name="exp_date"
                                           value="{{ ($Cmsnews->expirydate) ? date('d/m/Y',$Cmsnews->expirydate) : '' }}"
                                           placeholder="Click to Select a Date...">
                                </div>
                            </div>
                        </div>
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
                                                onchange="divDDOnChange(this, null, 'view_users')"
                                                style="width: 100%;">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="form-group">
                            <label for="profile_pic" class="col-sm-2 control-label">Profile Picture</label>

                            <div class="col-sm-8">
                                <div style="margin-bottom: 10px;">
                                    <img src="{{ $avatar }}" class="img-responsive img-thumbnail" width="320" height="150">
                                </div>
                                <input type="file" id="image" name="image" class="file file-loading" data-allowed-file-extensions='["jpg", "jpeg", "png"]' data-show-upload="false">
                            </div>
                        </div>
                        <div class="box-body">
                            <textarea id="send_invoice_message" name="summary" rows="10"
                                      cols="60">{{$Cmsnews->summary}}</textarea>
                            <input type="hidden" name="template_key" value="send_invoice">
                            <br>
                            {{--<p style="margin-bottom: 0;"><b>Placeholders:</b> [client name]</p>--}}
                        </div>
                        <!-- Confirmation Modal -->
                    @if(Session('success_application'))
                        @include('cms.partials.success_action', ['modal_title' => "Content Edit Successful!", 'modal_content' => session('success_application')])
                    @endif
                    <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                            <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-floppy-o"></i> Save
                            </button>
                        </div>
                        <!-- /.box-footer -->
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page_script')
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>

    <script src="/bower_components/bootstrap_fileinput/js/plugins/canvas-to-blob.min.js"
            type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js"
            type="text/javascript"></script>
    <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js"
            type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
    <!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
    <script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
    <!-- optionally if you need translation for your language then include locale file as mentioned below
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
    <!-- CK Editor -->
    <script src="https://cdn.ckeditor.com/4.7.1/standard/ckeditor.js"></script>
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>

    <script>

        $('#back_button').click(function () {
            location.href = '/cms/viewnews';
        });

        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();

            //Tooltip
            $('[data-toggle="tooltip"]').tooltip();

            //slimScroll
            $('#quote-profile-list').slimScroll({
                height: '',
                railVisible: true
                //alwaysVisible: true
            });

            // Replace the <textarea id="send_quote_message"> with a CKEditor
            // instance, using default configuration.
            CKEDITOR.replace('summary');

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

            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });

            //Show success action modal
            @if(Session('changes_saved'))
            $('#success-action-modal').modal('show');
            @endif

            //Load divisions drop down
            var parentDDID = '';
            var loadAllDivs = 1;
            @foreach($division_levels as $division_level)
            //Populate drop down on page load
            var ddID = '{{ 'division_level_' . $division_level->level }}';
            var postTo = '{!! route('divisionsdropdown') !!}';
            var selectedOption = '';
            var divLevel = parseInt('{{ $division_level->level }}');
            if (divLevel == 5) selectedOption = '{{ $Cmsnews->division_level_5 }}';
            else if (divLevel == 4) selectedOption = '{{ $Cmsnews->division_level_4 }}';
            else if (divLevel == 3) selectedOption = '{{ $Cmsnews->division_level_3 }}';
            else if (divLevel == 2) selectedOption = '{{ $Cmsnews->division_level_2 }}';
            else if (divLevel == 1) selectedOption = '{{ $Cmsnews->division_level_1 }}';
            var incInactive = -1;
            var loadAll = loadAllDivs;
            loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo);
            parentDDID = ddID;
            loadAllDivs = -1;
            @endforeach
        });

    </script>
@endsection