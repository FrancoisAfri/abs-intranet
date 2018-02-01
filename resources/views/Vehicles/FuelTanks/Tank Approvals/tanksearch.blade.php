@extends('layouts.main_layout')
<!--  -->
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
<!-- bootstrap file input -->
<link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css"/>
<!-- iCheck -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
<!-- Select 2-->
<!--  -->
@section('page_dependencies')

@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border" align="center">
                    <i class="fa fa-truck pull-right"></i>
                    <h3 class="box-title">FuelTank Search criteria</h3>
                    <p>Enter search details:</p>
                </div>
                <form name="leave-application-form" class="form-horizontal" method="POST" action=" "
                      enctype="multipart/form-data">
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


                        <div class="form-group">
                            <label for="path" class="col-sm-2 control-label">Fleet Number</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-truck"></i>
                                    </div>
                                    <input type='text' class="form-control" id='fleet_no'
                                           name="fleet_no"/>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="path" class="col-sm-2 control-label">Vehicle</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-truck"></i>
                                    </div>
                                    <select class="form-control select2" style="width: 100%;"
                                            id="vehicle_type" name="vehicle_type">
                                        <option value="0">*** Select a Vehicle ***</option>
                                        @foreach($vehiclemodel as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('leave_types_id') ? ' has-error' : '' }}">
                            <label for="days" class="col-sm-2 control-label">Transaction Date</label>
                            <div class="col-sm-8">
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

                        <div class="form-group{{ $errors->has('application_type') ? ' has-error' : '' }}">
                            <label for="Leave_type" class="col-sm-2 control-label"> Type</label>

                            <div class="col-sm-8">
                                <label class="radio-inline" style="padding-left: 0px;"><input type="radio"
                                                                                              id="rdo_levTkn"
                                                                                              name="application_type"
                                                                                              value="1" checked> Search
                                </label>
                                <label class="radio-inline"><input type="radio" id="rdo_bal" name="application_type"
                                                                   value="2"> Tank Fuel Approval</label>
                                <label class="radio-inline"><input type="radio" id="rdo_po" name="application_type"
                                                                   value="3"> Other </label>
                            </div>
                        </div>
                        <div class="form-group search-field">
                            <label for="status" class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-star-o"></i>
                                    </div>

                                    <select id="status" name="status" class="form-control">
                                        <option value="0">*** Select Status ***</option>
                                        <option value="1"> Rejected</option>
                                        <option value="2"> Manager Approval</option>
                                        <option value="3"> Approved</option>
                                        <option value="4"> CEO Approval</option>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <!-- /.box-body -->
                        <!-- <div class="box-footer">
                            <button type="button" class="btn btn-default pull-left" id="back_button"><i class="fa fa-arrow-left"></i> Cancel</button>
                            <button type="submit" id="gen_report" class="btn btn-primary pull-right"><i class="fa fa-check"></i> Generate Report</button>
                        </div> -->
                        <div class="box-footer">
                            {{--  <button type="button" id="cancel" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Cancel</button>  --}}
                            <button type="submit" id="gen-report" name="gen-report" class="btn btn-default pull-right">
                                <i class="fa fa-search"></i> Search
                            </button>
                        </div>
                        <!-- /.box-footer -->
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- End new User Form-->
        <!-- Confirmation Modal -->
        @if(Session('success_add'))
            @include('contacts.partials.success_action', ['modal_title' => "Registration Successful!", 'modal_content' => session('success_add')])
        @endif
    </div>
@endsection

@section('page_script')
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <!-- Date rane picker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Date Picker -->
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <!--  -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <!-- Bootstrap date picker -->
    <script src="/bower_components/AdminLTE/plugins/daterangepicker/moment.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.js"></script>
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
    <!--Time Charger-->
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>


    <script type="text/javascript">
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
            $('.search-field').show();
            //Cancel button click event

            $('#cancel').click(function () {
                location.href = '/leave/reports';
            });

            function postData(id, data) {
                alert(id);
                //if (data == 'approval_id') location.href = "/leave/approval/" + id;
            }

            //Phone mask
            $("[data-mask]").inputmask();

            //Date picker
            $('#date_from').datepicker({
                format: 'MM yyyy',
                autoclose: true,
                startView: "months",
                minViewMode: "months",
                todayHighlight: true
            });
            $('#date_to').datepicker({
                format: 'MM yyyy',
                autoclose: true,
                startView: "months",
                minViewMode: "months",
                todayHighlight: true
            });

            //Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });

            hideFields();
            //Date Range picker
            $('.daterangepicker').daterangepicker({
                format: 'DD/MM/YYYY',
                endDate: '-1d',
                autoclose: true
            });
            //show/hide fields on radio button toggles (depending on registration type)

            $('#rdo_levTkn, #rdo_bal ,#rdo_po ,#rdo_all,#rdo_levH, #rdo_cancelled_leaves').on('ifChecked', function () {
                var allType = hideFields();
                if (allType == 1) $('#box-subtitle').html('Leave Taken');
                else if (allType == 2) $('#box-subtitle').html('Leave Balance');
                else if (allType == 3) $('#box-subtitle').html('Leave Paid Out');
                else if (allType == 4) $('#box-subtitle').html('Leave Allowance');
                else if (allType == 5) $('#box-subtitle').html('Leave History');
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
            $(window).on('resize', function () {
                $('.modal:visible').each(reposition);
            });

            //Show success action modal
            $('#success-action-modal').modal('show');
        });

        //function to hide/show fields depending on the allocation  type
        function hideFields() {
            var allType = $("input[name='application_type']:checked").val();
            if (allType == 1) { //adjsut leave

                $('.search-field').show();
                $('form[name="leave-application-form"]').attr('action', '/vehicle_management/search');
                $('#gen-report').val("Submit");
            }
            else if (allType == 2) { //resert leave
                $('.search-field').hide();
                $('form[name="leave-application-form"]').attr('action', '/vehicle_management/tankApproval');
                $('#gen-report').val("Submit");
            }
            else if (allType == 3) {
                $('.search-field').hide();
                $('form[name="leave-application-form"]').attr('action', '/vehicle_management/other');
                $('#gen-report').val("Submit");
            }

            return allType;
        }


    </script>
@endsection