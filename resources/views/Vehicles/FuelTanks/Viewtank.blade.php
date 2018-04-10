@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
    <!--Time Charger-->

@endsection

@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                </div>
                <form name="leave-application-form" class="form-horizontal" method="POST" action="/vehicle_management/both/{{ $ID }}" enctype="multipart/form-data">
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
                        <div class="col-md-8 col-md-offset-2">
                            <div>
                                <div class="box-header with-border" align="center">
                                    <h3 class="box-title">Search Fuel Tank Details </h3>
                                </div>
                                <div class="box-body" id="vehicle_details">
                                    <div class="form-group">
                                        <label for="path" class="col-sm-2 control-label">Tank Name </label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type='text' class="form-control" id='tank_name'
                                                       name="tank_name"
                                                       value="{{$fuel->tank_name . ' ' . $company . ' ' . $Department}}"
                                                       readonly=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('leave_types_id') ? ' has-error' : '' }}">
                                        <label for="days" class="col-sm-2 control-label">Transaction Date</label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <!--                                    <input type="text" class="form-control pull-right" id="reservation">-->
                                                <input type="text" class="form-control daterangepicker" id="action_date"
                                                       name="action_date" value="" placeholder="Select Action Date...">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('transaction_type') ? ' has-error' : '' }}">
                                        <label for="transaction_type" class="col-sm-2 control-label"> Transaction Type</label>
                                        <div class="col-sm-9">
                                            <label class="radio-inline"><input type="radio" id="rdo_incoming" name="transaction_type" value="1">  Incoming </label>
                                            <label class="radio-inline"><input type="radio" id="rdo_outgoing" name="transaction_type" value="2">  Outgoing </label>
                                            <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_both" name="transaction_type" value="3"> Private Usage  </label>
                                            <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_both" name="transaction_type" value="0" checked> All  </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="path" class="col-sm-2 control-label">employee</label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user-circle"></i>
                                                </div>
                                                <select class="form-control select2" style="width: 100%;"
                                                        id="reciever_id" name="reciever_id">
                                                    <option value="">*** Select a User ***</option>
                                                    @foreach($employees as $User)
                                                        <option value="{{ $User->id }}"> {{ !empty($User->first_name . ' ' . $User->surname) ? $User->first_name . ' ' . $User->surname : ''}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
									<button type="button" id="cancel" class="btn btn-default pull-left"><i
                                                class="fa fa-arrow-left"></i> Back
                                    </button>
                                    <button type="submit" id="gen-report" name="gen-report" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Search </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.box -->
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
    <!-- Date rane picker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>

    <script type="text/javascript">
	//Cancel button click event
		document.getElementById("cancel").onclick = function () {
		location.href = "/vehicle_management/fuel_tank/";
		};
        $(function () {
            $(".select2").select2();
            $('form[name="leave-application-form"]').attr('action', '/vehicle_management/both/{{ $ID }}');
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

        $('.required_from').datepicker({
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

        //Date Range picker
        $('.daterangepicker').daterangepicker({
            format: 'DD/MM/YYYY',
            endDate: '-1d',
            autoclose: true
        });

        //show/hide fields on radio button toggles (depending on registration type)
        $('#rdo_incoming, #rdo_outgoing , #rdo_both').on('ifChecked', function () {
            var allType = hideFields();
            if (allType == 1)
                $('#box-subtitle').html('Days');
            else if (allType == 2)
                $('#box-subtitle').html('Hours');
            else if (allType == 3)
                $('#box-subtitle').html('Hours');
        });

        //function to hide/show fields depending on the allocation  type
        function hideFields() {
            var allType = $("input[name='transaction_type']:checked").val();
            if (allType == 1) { //adjsut leave
                $('.usage-field').hide();
                 $('#gen-report').val("Submit");
            } else if (allType == 2) { //resert leave
                $('.usage-field').show();
                $('#gen-report').val("Submit");
            }else if (allType == 3) { //resert leave
                $('.usage-field').show();
                $('#gen-report').val("Submit");
            }
            else
                $('.usage-field').show();
                return allType;
        }

        $(document).ready(function () {

            $(function () {
                $('#required_from').datepicker();
            });

            $('#required_to').datepicker({});

        });
    </script>
@endsection