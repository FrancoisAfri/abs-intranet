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
                    <h3 class="box-title">Fuel Search criteria</h3>
                    <p>Enter search details:</p>
                </div>
                <form name="fuel-search-form" class="form-horizontal" method="POST" action=" "
                      enctype="multipart/form-data">
                    {{ csrf_field() }}
					<input type="hidden" name="no_errors" value="1" id="no_errors">
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
                            <label for="application_type" class="col-sm-2 control-label">Search Type</label>
                            <div class="col-sm-8">
                                <label class="radio-inline" style="padding-left: 0px;">
									<input type="radio" id="rdo_levTkn" name="application_type" value="1" checked> Search
                                </label>
                                <label class="radio-inline">
									<input type="radio" id="rdo_bal" name="application_type"
										value="2"> Tank Fuel Approval</label>
                                <label class="radio-inline">
									<input type="radio" id="rdo_po" name="application_type"
                                         value="3"> Other </label>
                            </div>
                        </div>
                        <div class="form-group" id="vehicle-field">
                            <label for="vehicle_id" class="col-sm-2 control-label">Vehicle</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-truck"></i>
                                    </div>
                                    <select class="form-control select2" style="width: 100%;"
                                            id="vehicle_id" name="vehicle_id">
                                        <option value="0">*** Select a Vehicle ***</option>
                                        @foreach($vehicleDetails as $vehicle)
                                            <option value="{{ $vehicle->id }}">{{ $vehicle->fleet_number."|".$vehicle->vehicle_registration}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
						<div class="form-group tank-field">
                            <label for="vehicle_type" class="col-sm-2 control-label">Tank</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-truck"></i>
                                    </div>
                                    <select class="form-control select2" style="width: 100%;"
                                            id="vehicle_type" name="vehicle_type">
                                        <option value="0">*** Select a Tank ***</option>
                                        @foreach($fueltank as $tank)
                                            <option value="{{ $tank->id }}">{{ $tank->tank_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('leave_types_id') ? ' has-error' : '' }}">
                            <label for="action_date" class="col-sm-2 control-label">Transaction Date</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
										<input type="text" class="form-control daterangepicker" id="action_date"
                                           name="action_date" value="" placeholder="Select Action Date...">
                                </div>
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
                                        <option value="14"> Rejected</option>
                                        <option value="4"> Manager Approval</option>
                                        <option value="1"> Approved</option>
                                        <option value="10"> CEO Approval</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" id="gen-report" name="gen-report" class="btn btn-default pull-right">
                                <i class="fa fa-search"></i> Submit
                            </button>
                        </div>
                        <!-- /.box-footer -->
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
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
            $('.tank-field').hide();
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
            if (allType == 1) {

                $('.search-field').show();
                $('.tank-field').hide();
                $('form[name="fuel-search-form"]').attr('action', '/vehicle_management/search');
                $('#gen-report').val("Submit");
            }
            else if (allType == 2) {
                $('.search-field').hide();
                $('.tank-field').show();
                $('form[name="fuel-search-form"]').attr('action', '/vehicle_management/tankApproval');
                $('#gen-report').val("Submit");
            }
            else if (allType == 3) {
                $('.search-field').show();
                $('.tank-field').hide();
				$('#vehicle-field').show();
                $('form[name="fuel-search-form"]').attr('action', '/vehicle_management/other');
                $('#gen-report').val("Submit");
            }
            return allType;
        }
    </script>
@endsection