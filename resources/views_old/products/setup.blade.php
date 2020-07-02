@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary collapsed-box">
                <form class="form-horizontal" method="POST" action="/product/services">
                    {{ csrf_field() }}
                    <div class="box-header with-border">
                        <h3 class="box-title">Services Settings</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-plus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-remove"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="services-settings" style="max-height: 250px;">
                            <div class="form-group products-field {{ $errors->has('service_unit_name') ? ' has-error' : '' }}">
                                <label for="service_unit_name" class="col-sm-2 control-label">Unit Name</label>

                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-info"></i>
                                        </span>
                                        <input type="text" class="form-control" name="service_unit_name"
                                               id="service_unit_name" placeholder="Service Unit Name (e.g. hour)"
                                               value="{{ ($serviceSettings) ? $serviceSettings->service_unit_name : '' }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group products-field {{ $errors->has('service_unit_plural_name') ? ' has-error' : '' }}">
                                <label for="service_unit_plural_name" class="col-sm-2 control-label">Unit Plural
                                    Name</label>

                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-info"></i>
                                        </span>
                                        <input type="text" class="form-control" name="service_unit_plural_name"
                                               id="service_unit_plural_name"
                                               placeholder="Service Unit Plural Name (e.g. hours)"
                                               value="{{ ($serviceSettings) ? $serviceSettings->service_unit_plural_name : '' }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group products-field {{ $errors->has('service_rate') ? ' has-error' : '' }}">
                                <label for="service_rate" class="col-sm-2 control-label">Rate Per Unit</label>

                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            R
                                        </span>
                                        <input type="number" class="form-control" name="service_rate" id="service_rate"
                                               placeholder="Service Rate Per Unit (e.g. 450)"
                                               value="{{ ($serviceSettings) ? $serviceSettings->service_rate : '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" id="add-new-profile" class="btn btn-primary pull-right"
                                data-toggle="modal" data-target="#add-new-profile-modal"><i class="fa fa-floppy-o"></i>
                            Save Changes
                        </button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>

        </div>
        <!-- Include modal -->
        @if(Session('changes_saved'))
            @include('contacts.partials.success_action', ['modal_title' => "Settings Updated!", 'modal_content' => session('changes_saved')])
        @endif

    </div>
@endsection

@section('page_script')
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
    <!-- optionally if you need translation for your language then include locale file as mentioned below
    <script src="/bower_components/bootstrap_fileinput/js/locales/<lang>.js"></script>-->
    <!-- End Bootstrap File input -->

    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>

    <!-- CK Editor -->
    <script src="https://cdn.ckeditor.com/4.7.1/standard/ckeditor.js"></script>

    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>

    <script>
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

            // [bootstrap file input] initialize with defaults
            $("#letter_head").fileinput();
            // with plugin options
            //$("#input-id").fileinput({'showUpload':false, 'previewFileType':'any'});

            // Replace the <textarea id="send_quote_message"> with a CKEditor
            // instance, using default configuration.
            CKEDITOR.replace('send_quote_message');
            CKEDITOR.replace('approved_quote_message');
            // CKEDITOR.replace('term_name');

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
            @if(Session('changes_saved'))
            $('#success-action-modal').modal('show');
            @endif
        });
    </script>
@endsection