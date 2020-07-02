@extends('layouts.main_layout')
@section('page_dependencies')
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
    <!--Time Charger-->
@endsection
@section('content')

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <i class="fa fa-sliders pull-right"></i>
                    <h3 class="box-title">Job Card Configuration</h3>
                </div>
                <form class="form-horizontal" method="POST"
                      action="/jobcards/configuration_setings/{{$configuration->id}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="box-body">
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
                        <div class="box-body" style="max-height: 600px; overflow-y: scroll;">
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <td class="caption" colspan="2">Use procurement</td>
                                    <input type="hidden" name="use_procurement" value="0">
                                    <td colspan="3"><input type="checkbox" name="use_procurement"
                                                           value="1" {{(!empty($configuration->use_procurement) && $configuration->use_procurement === 1) ? 'checked ="checked"' : 0 }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="caption" colspan="2">Send sms to mechanic</td>
                                    <input type="hidden" name="mechanic_sms" value="0">
                                    <td colspan="3"><input type="checkbox" name="mechanic_sms"
                                                           value="1" {{(!empty($configuration->mechanic_sms) && $configuration->mechanic_sms === 1) ? 'checked ="checked"' : 0 }}>

                                    </td>
                                </tr>
                            </table>
							<table class="table table-striped table-bordered">
                                <hr class="hr-text" data-content="Directories">
								<tr>
                                    <td class="caption" colspan="2">Service File From</td>
                                    <td colspan="3"><input type="text" class="form-control" id="service_file_from" name="service_file_from"
												value="{{(!empty($configuration->service_file_from)) ? $configuration->service_file_from : '' }}" placeholder="Enter Service File From ...">				   
                                    </td>
                                </tr>
                                <tr>
                                    <td class="caption" colspan="2">Service File To</td>
                                    <td colspan="3"><input type="text" class="form-control" id="service_file_to" name="service_file_to"
												value="{{(!empty($configuration->service_file_to)) ? $configuration->service_file_to : '' }}" placeholder="Enter Service File To Directory  ...">				   
                                    </td>
                                </tr>
								<tr>
                                    <td class="caption" colspan="2">Brake Test From</td>
                                    <td colspan="3"><input type="text" class="form-control" id="break_test_from" name="break_test_from"
												value="{{(!empty($configuration->break_test_from)) ? $configuration->break_test_from : '' }}" placeholder="Enter Break Test From Directory ...">				   
                                    </td>
                                </tr>
								<tr>
                                    <td class="caption" colspan="2">Brake Test To</td>
                                    <td colspan="3"><input type="text" class="form-control" id="break_test_to" name="break_test_to"
												value="{{(!empty($configuration->break_test_to)) ? $configuration->break_test_to : '' }}" placeholder="Enter Break Test To Directory ...">				   
                                    </td>
                                </tr>
							</table>
                        </div>
                        <br>
                        <div class="box-footer">
                            <button type="button" id="cancel" class="btn-sm btn-default btn-flat pull-left"><i
                                        class="fa fa-arrow-left"></i> Back
                            </button>
                            <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-cog"></i> Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('page_script')
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- bootstrap datepicker -->
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>

    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js"
            type="text/javascript"></script>
    <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->

    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>

    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script type="text/javascript">
        //Cancel button click event
        document.getElementById("cancel").onclick = function () {
            location.href = '/jobcards/set_up';
        };
        $(function () {
            $(".select2").select2();
            $('.hours-field').hide();
            $('.comp-field').hide();
            var moduleId;
            //Tooltip
            $('[data-toggle="tooltip"]').tooltip();

            //Vertically center modals on page

            //Phone mask
            $("[data-mask]").inputmask();

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
        });

        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true
        });

        //Initialize iCheck/iRadio Elements
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '10%' // optional
        });


    </script>
@endsection
