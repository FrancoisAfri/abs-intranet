@extends('layouts.main_layout')
<!--  -->
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
<!-- bootstrap file input -->
<link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css"/>

<!-- Include Date Range Picker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<!-- iCheck -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
<!-- Select 2-->
<link rel="stylesheet" type="text/css" href="css/dropzone.css"/>
<!--  -->
@section('page_dependencies')

@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-search-plus pull-right"></i>
                    {{--<h3 class="box-title">Leave Reports Search criteria</h3>--}}
                    {{--<p>Enter search details:</p>--}}
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
                        <div class="form-group{{ $errors->has('application_type') ? ' has-error' : '' }}">
                            <label for="Leave_type" class="col-sm-2 control-label"> Type</label>

                            <div class="col-sm-9">
                                <label class="radio-inline" style="padding-left: 0px;"><input type="radio"
                                                                                              id="rdo_levTkn"
                                                                                              name="application_type"
                                                                                              value="1" checked> Vehicle
                                    Documents </label>
                                <label class="radio-inline"><input type="radio" id="rdo_bal" name="application_type"
                                                                   value="2"> Vehicle Images</label>

                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('hr_person_id') ? ' has-error' : '' }}">
                            <label for="hr_person_id" class="col-sm-2 control-label">Vehicle </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user-circle"></i>
                                    </div>
                                    <select class="form-control select2" style="width: 100%;" id="vehicle_type"
                                            name="vehicle_type">
                                        <option value="">*** Select an Vehicle Type ***</option>
                                        @foreach($vehicleTypes as $vehicletypes)
                                            <option value="{{ $vehicletypes->id }}">{{ $vehicletypes->vehicleMake . ' ' . $vehicletypes->vehicleModel . ' ' . $vehicletypes->year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('hr_person_id') ? ' has-error' : '' }}">
                            <label for="hr_person_id" class="col-sm-2 control-label">Vehicle Make</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user-circle"></i>
                                    </div>
                                    <select class="form-control select2" style="width: 100%;" id="vehicle_make"
                                            name="vehicle_make">
                                        <option value="">*** Select an Vehicle Make ***</option>
                                        @foreach($vehiclemaker as $vehiclemake)
                                            <option value="{{ $vehiclemake->id }}">{{ $vehiclemake->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group  {{ $errors->has('leave_types_id') ? ' has-error' : '' }}">
                            <label for="leave_types_id" class="col-sm-2 control-label">Fleet Number</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>

                                    <input type="text" class="form-control" id="fleet_no" name="fleet_no"
                                           placeholder="Enter an fleet number...">

                                </div>
                            </div>
                        </div>

                        <div class="form-group  {{ $errors->has('leave_types_id') ? ' has-error' : '' }}">
                            <label for="leave_types_id" class="col-sm-2 control-label">Description </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>

                                    <input type="text" class="form-control" id="description" name="description"
                                           placeholder="Enter an fleet number...">

                                </div>
                            </div>
                        </div>

                        <hr class="hr-text" data-content="Date ">

                        <div class="form-group  {{ $errors->has('leave_types_id') ? ' has-error' : '' }}">
                            <label for="days" class="col-sm-2 control-label">Action Date</label>
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

                        <div class="form-group doc-field  {{ $errors->has('leave_types_id') ? ' has-error' : '' }}">
                            <label for="days" class="col-sm-2 control-label">Expiry Date</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" id="expiry_date"
                                           name="expiry_date" value="" placeholder="Select Expiry Date...">
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">

                            <button type="submit" id="gen-report" name="gen-report" class="btn btn-primary pull-right">
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
    <!-- canvas-to-blob.min.js is only needed if you wish to resize images before upload. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/canvas-to-blob.min.js"
            type="text/javascript"></script>
    {{----}}
    <script type="text/javascript" src="js/dropzone.js"></script>
    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
    <!-- Date picker -->
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>

    <!--        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>-->
    <!--    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->

    <script type="text/javascript">
        $(function () {
            //Initialize Select2 Elements

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

            $(document).ready(function () {

                $('input[name="expiry_date"]').datepicker({
                    format: 'dd/mm/yyyy',
                    autoclose: true,
                    todayHighlight: true
                });


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
                //$('.hours-field').hide();
                $('.doc-field').show();
                $('form[name="leave-application-form"]').attr('action', '/vehicle_management/doc_search');
                $('#gen-report').val("Submit");
            }
            else if (allType == 2) { //resert leave
                $('.doc-field').hide();
                $('form[name="leave-application-form"]').attr('action', '/vehicle_management/image_search');
                $('#gen-report').val("Submit");
            }

            return allType;
        }


    </script>
@endsection