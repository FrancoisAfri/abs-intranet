@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <form class="form-horizontal" method="POST" action="/email-template/save">
                    {{ csrf_field() }}
                    <div class="box-header with-border">
                        <h3 class="box-title">Send Invoice Email Template</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <textarea id="send_invoice_message" name="template_content" rows="10" cols="80">{{ ($sendInvoiceTemplate) ? $sendInvoiceTemplate->template_content : '' }}</textarea>
                        <input type="hidden" name="template_key" value="send_invoice">
                        <br>
                        <p style="margin-bottom: 0;"><b>Placeholders:</b> [client name]</p>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-floppy-o"></i> Save</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page_script')
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

            // Replace the <textarea id="send_quote_message"> with a CKEditor
            // instance, using default configuration.
            CKEDITOR.replace('send_invoice_message');

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
            @if(Session('changes_saved'))
                $('#success-action-modal').modal('show');
            @endif

        });
    </script>
@endsection