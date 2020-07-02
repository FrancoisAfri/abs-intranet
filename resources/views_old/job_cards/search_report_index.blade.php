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
                    <i class="fa fa-anchor pull-right"></i>
                    <h3 class="box-title">Reports Search criteria</h3>
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
                        <div class="form-group{{ $errors->has('application_type') ? ' has-error' : '' }}">
                            <label for="Leave_type" class="col-sm-2 control-label"> Report Type</label>

                            <div class="col-sm-9">
                                <label class="radio-inline" style="padding-left: 0px;"><input type="radio"
                                                                                              id="rdo_levTkn"
                                                                                              name="application_type"
                                                                                              value="1" checked> Job
                                    cards</label>
                                <!-- <label class="radio-inline"><input type="radio" id="rdo_bal" name="application_type" value="2">Rejected Job cards</label> -->
                                <label class="radio-inline"><input type="radio" id="rdo_po" name="application_type"
                                                                   value="2"> Parts</label>
                                <label class="radio-inline"><input type="radio" id="rdo_all" name="application_type"
                                                                   value="4">Notes</label>
                            </div>
                        </div>
                        <div class="form-group levAction-field {{ $errors->has('status') ? ' has-error' : '' }}">
                            <label for="status" class="col-sm-2 control-label">Status </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user-circle"></i>
                                    </div>
                                    <select class="form-control select2" style="width: 100%;" id="status"
                                            name="status">
                                        <option value="">*** Select a Status ***</option>
                                        @foreach($processflow as $flow)
                                            <option value="{{ $flow->step_name }}">{{ $flow->step_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('vehicle_id') ? ' has-error' : '' }}">
                            <label for="vehicle_id" class="col-sm-2 control-label">Vehicle</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user-circle"></i>
                                    </div>
                                    <select class="form-control select2" style="width: 100%;" id="vehicle_id"
                                            name="vehicle_id">
                                        <option value="">*** Select a Vehicle***</option>
                                        @foreach($vehicledetails as $details)
                                            <option value="{{ $details->fleet_id }}">{{ $details->fleet_number . ' ' .  $details->vehicle_registration . ' ' . $details->vehicle_make . ' ' . $details->vehicle_model }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group levAction-field {{ $errors->has('action_date') ? ' has-error' : '' }}">
                            <label for="action_date" class="col-sm-2 control-label">Action Date</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control daterangepicker" id="actions_date"
                                           name="actions_date" value="" placeholder="Select Action Date...">
                                </div>
                            </div>
                        </div>

                        <div class="form-group day-field {{ $errors->has('action_date') ? ' has-error' : '' }}">
                            <label for="action_date" class="col-sm-2 control-label">Action Date</label>
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
                        <div class="form-group day-field{{ $errors->has('category_id') ? ' has-error' : '' }}">
                            <label for="{{ 'category_id' }}" class="col-sm-2 control-label">Category </label>

                            <div class="col-sm-8">
                                <select id="category_id" name="category_id" class="form-control select2"
                                        style="width: 100%;" onchange="productcategoryDDOnChange(this)">
                                    <option value="">*** Please Select a Product Category ***</option>
                                    <option value="0"></option>
                                    @foreach($productsCategories as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group day-field{{ $errors->has('product_id') ? ' has-error' : '' }}">
                            <label for="{{ 'product_id' }}" class="col-sm-2 control-label">Product </label>

                            <div class="col-sm-8">
                                <select id="product_id" name="product_id" class="form-control select2"
                                        style="width: 100%;">
                                    <option value="">*** Please Select a Category First ***</option>
                                </select>
                            </div>
                        </div>
						 <div class="form-group notedate-field{{ $errors->has('category_id') ? ' has-error' : '' }}">
                            <label for="{{ 'category_id' }}" class="col-sm-2 control-label">Note Details</label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control " id="note_details"
                                       name="note_details" value="" placeholder="">
                            </div>
                        </div>
                        <div class="form-group notedate-field {{ $errors->has('action_date') ? ' has-error' : '' }}">
                            <label for="action_date" class="col-sm-2 control-label">Action Date</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control daterangepicker" id="note_date"
                                           name="note_date" value="" placeholder="Select Action Date...">

                                </div>
                            </div>
                        </div>
                        <div class="form-group notedate-field {{ $errors->has('status') ? ' has-error' : '' }}">
                            <label for="status" class="col-sm-2 control-label">Employees</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user-circle"></i>
                                    </div>
                                    <select class="form-control select2" style="width: 100%;" id="user_id"
                                            name="user_id">
                                        <option value="">*** Select an Employee ***</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->first_name . ' ' . $user->surname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">

                            <button type="submit" id="gen-report" name="gen-report" class="btn btn-primary pull-right">
                                <i class="fa fa-check"></i> Generate Report
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
            $(".select2").select2();
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
                $('.day-field').hide();
                $('.levAction-field').show();
                $('.date-field').hide();
                $('.notedate-field').hide();
                $('form[name="leave-application-form"]').attr('action', '/jobcards/reports/cards');
                $('#gen-report').val("Submit");
            }
            else if (allType == 2) { //resert leave
                $('.day-field').show();
                $('.from-field').hide();
                $('.notes-field').hide();
                $('.levAction-field').hide();
                $('.notedate-field').hide();
                $('form[name="leave-application-form"]').attr('action', '/jobcards/reports/parts');
                //$('form[name="leave-application-form"]').attr('action', '/leave/print/bal');
                $('#gen-report').val("Submit");
            }

            else if (allType == 4) {
                $('.day-field').hide();
                $('.from-field').hide();
                $('.notes-field').hide();
                $('.levAction-field').hide();
                $('.notedate-field').show();
                $('form[name="leave-application-form"]').attr('action', '/jobcards/reports/notes');
                $('#gen-report').val("Submit");
            }
            return allType;
        }
        //Load divisions drop down
    </script>
@endsection