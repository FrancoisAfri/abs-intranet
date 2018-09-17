@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <!-- Include Date Range Picker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
    <!-- Time picker -->
    <!--  -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css"
          rel="stylesheet">
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-anchor pull-right"></i>
                    <h3 class="box-title">Confirm Return</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form name="leave-application-form" class="form-horizontal" method="POST"
                      action="/vehicle_management/{{ $returnVeh->id }}/confirmreturn" enctype="multipart/form-data">

                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}

                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                    <strong class="lead">Vehicle Details</strong><br>

                                    @if(!empty($vehiclemaker))
                                        | &nbsp; &nbsp; <strong>Vehicle Make:</strong><em>{{ $vehiclemaker->name }}</em>
                                        &nbsp;
                                        &nbsp;
                                    @endif
                                    @if(!empty($vehiclemodeler))
                                        -| &nbsp; &nbsp; <strong>Vehicle Model:</strong>
                                        <em>{{ $vehiclemodeler->name }}</em>
                                        &nbsp; &nbsp;
                                    @endif
                                    @if(!empty($vehicleTypes))
                                        -| &nbsp; &nbsp; <strong>Vehicle Type:</strong>
                                        <em>{{ $vehicleTypes->name }}</em> &nbsp;
                                        &nbsp;
                                    @endif
                                    @if(!empty($maintenance->vehicle_registration))
                                        -| &nbsp; &nbsp; <strong>Vehicle Registration:</strong>
                                        <em>{{ $vehiclebookings->vehicle_reg }}</em> &nbsp; &nbsp;
                                    @endif
                                    @if(!empty($maintenance->year))
                                        -| &nbsp; &nbsp; <strong>Year:</strong>
                                        <em>{{ $vehiclebookings->vehicle_reg->year }}</em> &nbsp;
                                        &nbsp;
                                    @endif
                                    @if(!empty($maintenance->vehicle_color))
                                        -| &nbsp; &nbsp; <strong>Vehicle Color:</strong>
                                        <em>{{ $vehiclebookings->vehicle_reg->vehicle_color }}</em> &nbsp; &nbsp; -|
                                    @endif

                                </p>
                            </div>
                        </div>
                        <div class="box-body">
                            @if (count($errors) > 0)
                                <div class="alert alert-danger alert-dismissible fade in">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                        &times;
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
                                <label for="path" class="col-sm-2 control-label">Vehicle Model</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-bullseye"></i>
                                        </div>
                                        <input type="text" id="vehiclemodel" class="form-control pull-left"
                                               name="vehiclemodel"
                                               value="{{ (!empty($vehiclemodeler->name)) ?  $vehiclemodeler->name : ''}}"
                                               readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="path" class="col-sm-2 control-label">Vehicle Reg. No</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-id-card-o"></i>
                                        </div>
                                        <input type="text" id="vehicle_reg" class="form-control pull-left"
                                               name="vehicle_reg"
                                               value="{{ $vehiclebookings->vehicle_reg }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="path" class="col-sm-2 control-label">Required From </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" id="require_datetime"
                                               class="form-control pull-left" name="require_datetime"
                                               value="{{ date("F j, Y, g:i a", $vehiclebookings->require_datetime) }}"
                                               readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="path" class="col-sm-2 control-label">Collected </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" id="collect_timestamp"
                                               class="form-control pull-left" name="collect_timestamp"
                                               value="{{ !empty($vehiclebookings->collect_timestamp ) ?  date("F j, Y, g:i a", $vehiclebookings->collect_timestamp)  : ''}}"
                                               readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="path" class="col-sm-2 control-label">Required To </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" id="return_datetime"
                                               class="form-control pull-left" name="return_datetime"
                                               value="{{ date("F j, Y, g:i a", $vehiclebookings->return_datetime) }}"
                                               readonly>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="path" class="col-sm-2 control-label">Capturer </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user-o"></i>
                                        </div>
                                        <input type="text" id="capturer_id" class="form-control pull-left"
                                               name="capturer_id"
                                               value="{{  $vehiclebookings->capturer_id }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="path" class="col-sm-2 control-label"> VehicleDriver </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user-o"></i>
                                        </div>
                                        <input type="text" id="driver_id" class="form-control pull-left"
                                               name="driver_id"
                                               value="{{  $vehiclebookings->firstname . ' ' . $vehiclebookings->surname }}"
                                               readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="path" class="col-sm-2 control-label">Returned At </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type='text' class="form-control" id='return_timestamp'
                                               name="return_timestamp"/>
                                    </div>
                                </div>
                            </div>
                             @if (isset($vehiclebookings) && $vehiclebookings->metre_reading_type === 1)
                            <div class="form-group">
                                <label for="path" class="col-sm-2 control-label"> Start Odometer
                                    Reading </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-tachometer"></i>
                                        </div>
                                        <input type="text" id="start_mileage_id" class="form-control pull-left"
                                               name="start_mileage_id"
                                               value="{{ !empty($vehiclebookings->odometer_reading) ? $vehiclebookings->odometer_reading : ''}}"
                                               readonly>
                                    </div>
                                </div>
                            </div>
                                @else
                             <div class="form-group">
                                <label for="path" class="col-sm-2 control-label"> Start Hours
                                    Reading </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-tachometer"></i>
                                        </div>
                                        <input type="text" id="start_mileage_id" class="form-control pull-left"
                                               name="start_mileage_id"
                                               value="{{ !empty($vehiclebookings->odometer_reading) ? $vehiclebookings->odometer_reading : ''}}"
                                               readonly>
                                    </div>
                                </div>
                            </div>
                              @endif    
                            <div class="form-group">
                                <label for="end_mileage_id" class="col-sm-2 control-label"> End Odometer
                                    Reading </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-tachometer"></i>
                                        </div>
                                        <input type="text" id="end_mileage_id" class="form-control pull-left"
                                               name="end_mileage_id" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="end_mileage_id" class="col-sm-2 control-label"> Additional Actions </label>
                                <div class="col-sm-10">
                                    <button type="button" id="cat_module"
                                            class="btn btn-muted " data-toggle="modal"
                                            data-target="#add-fuel-modal">Add Fuel Log
                                    </button>
                                    <button type="button" id="cat_module"
                                            class="btn btn-muted " data-toggle="modal"
                                            data-target="#add-fines-modal">Add Fine
                                    </button>
                                    <button type="button" id="cat_module"
                                            class="btn btn-muted " data-toggle="modal"
                                            data-target="#add-incidents-modal">Add Incident
                                    </button>
                                    <button type="button" id="cat_module"
                                            class="btn btn-muted " data-toggle="modal"
                                            data-target="#add-costs-modal">Add General Cost
                                    </button>
                                </div>
                            </div>
                            <div class="box-footer">
                            </div>
                            <!-- /.box-footer -->
                            <div class="box-footer" style="text-align: center;">
                                <button type="button" id="cancel" class="btn btn-default pull-left">Cancel</button>
                                <button type="submit" name="command" id="update" class="btn btn-primary pull-right">
                                    Update
                                </button>

                                @if (isset($InforceVehiclerules) && $InforceVehiclerules->inforce_vehicle_image === 1)
                                    <button type="button" id="cat_module"
                                            class="btn btn-info btn-xs" data-toggle="modal"
                                            data-target="#add-returnimage-modal">Inspection Images
                                    </button>
                                @endif

                                @if (isset($InforceVehiclerules) && $InforceVehiclerules->inforce_vehicle_documents === 1)
                                    <button type="button" id="cat_module"
                                            class="btn btn-muted btn-xs" data-toggle="modal"
                                            data-target="#add-returndocument-modal">Inspection Documents
                                    </button>
                                @endif
                            </div>
                            <!-- /.box-footer -->
                        </div>
                </form>
            </div>
        </div>
        <!-- /.box -->
    </div>
    @include('Vehicles.Create_request.return_document_modal')
    @include('Vehicles.Create_request.return_image_modal')
    <!-- Addition Actions -->
    @include('Vehicles.Create_request.add_vehicleFuelRecords_modal')
    @include('Vehicles.Create_request.add_vehicleFines_modal')
    @include('Vehicles.Create_request.add_vehicleIncidents_modal')
    @include('Vehicles.Create_request.add_generalcosts_modal')

    @if(Session('success_application'))
    @include('Vehicles.sucess.success_action', ['modal_title' => "Application Successful!", 'modal_content' => session('success_application')])
    @endif

    </div>
@endsection

@section('page_script')
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- bootstrap datepicker -->
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <!--  Date Picker -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <!-- File Input-->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <script src="/bower_components/bootstrap_fileinput/js/plugins/canvas-to-blob.min.js"
            type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
    <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
    <!-- Date rane picker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- iCheck -->
    <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js"
            type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
    <!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
    <script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
    <!-- Date picker -->
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>

    <!--        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>-->
    <!--    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->

    <script type="text/javascript">
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
            $('.zip-field').hide();
            $('.transaction-field').hide();
            //Cancel button click event
            $('#cancel').click(function () {
                location.href = '/vehicle_management/create_request';
            });

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

        //Initialize iCheck/iRadio Elements
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '10%' // optional
        });
        $('#rdo_single, #rdo_zip').on('ifChecked', function () {
            var allType = hideFields();
            if (allType == 1) $('#box-subtitle').html('Site Address');
            else if (allType == 2) $('#box-subtitle').html('Temo Site Address');
        });

        $(document).ready(function () {

            $(function () {
                $('#return_timestamp').datetimepicker();
            });

            // $('#required_to').datetimepicker({});

        });


        function hideFields() {
            var allType = $("input[name='image_type']:checked").val();
            if (allType == 1) {
                $('.zip-field').hide();
                $('.Single-field').show();
            }
            else if (allType == 2) {
                $('.Single-field').hide();
                $('.zip-field').show();
            }
            return allType;
        }

        // 
        $('#rdo_transaction, #rdo_Other').on('ifChecked', function () {
            var allType = hideFields();

        });

        $(document).ready(function () {

            $('#date_of_incident').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });

            //
            $('#dateofincident').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });

        });

        $(document).ready(function () {

            $('input[name="date"]').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });


        });

        $('#ss_date').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true
        });

        function hideFields() {
            var allType = $("input[name='transaction']:checked").val();
            if (allType == 1) {
                $('.transaction-field').hide();
                $('.Tanks-field').show();
            }
            else if (allType == 2) {
                $('.transaction-field').show();
                $('.Tanks-field').hide();
            }
            return allType;
        }

        $('#returndocument').on('click', function () {
            var strUrl = '/vehicle_management/return_document';
            var formName = 'add-returndocument-form';
            var modalID = 'add-returndocument-modal';
            var submitBtnID = 'returndocument';
            var redirectUrl = '/vehicle_management/return_vehicle/{{ $returnVeh->id}}';
            var successMsgTitle = 'Document Added!';
            var successMsg = 'The Document  has been updated successfully.';
            modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });

        //Post perk form to server using ajax (add)
        $('#add-return_image').on('click', function () {
            var strUrl = '/vehicle_management/return_Image';
            var formName = 'add-new-returnimage-form';
            var modalID = 'add-returnimage-modal';
            var submitBtnID = 'add-return_image';
            var redirectUrl = '/vehicle_management/return_vehicle/{{ $returnVeh->id }}';
            var successMsgTitle = 'Image Added!';
            var successMsg = 'The Image  has been updated successfully.';
            modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });

        //fuel log
        //Post perk form to server using ajax (add)
        $('#add_vehiclefuellog').on('click', function () {
            var strUrl = '/vehicle_management/addvehiclefuellog';
            var formName = 'add-fuel-form';
            var modalID = 'add-fuel-modal';
            var submitBtnID = 'add_vehiclefuellog';
            var redirectUrl = '/vehicle_management/return_vehicle/{{ $returnVeh->id}}';
            var successMsgTitle = 'New Record Added!';
            var successMsg = 'The Record  has been updated successfully.';
            modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });

        // fine

        //Post perk form to server using ajax (add)
        $('#add_fines').on('click', function () {
            var strUrl = '/vehicle_management/addvehiclefines';
            var formName = 'add-fines-form';
            var modalID = 'add-fines-modal';
            var submitBtnID = 'add_fines';
            var redirectUrl = '/vehicle_management/return_vehicle/{{ $returnVeh->id }}';
            var successMsgTitle = 'New Record Added!';
            var successMsg = 'The Record  has been updated successfully.';
            modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });

        // Incident

        //Post perk form to server using ajax (add)
        $('#add_vehicleincidents').on('click', function () {
            var strUrl = '/vehicle_management/addvehicleincidents';
            var formName = 'add-incidents-form';
            var modalID = 'add-incidents-modal';
            var submitBtnID = 'add_vehicleincidents';
            var redirectUrl = '/vehicle_management/return_vehicle/{{ $returnVeh->id }}';
            var successMsgTitle = 'New Record Added!';
            var successMsg = 'The Record  has been updated successfully.';
            modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });

        function clone(id, file_index, child_id) {
            var clone = document.getElementById(id).cloneNode(true);
            clone.setAttribute("id", file_index);
            clone.setAttribute("name", file_index);
            clone.style.display = "table-row";
            clone.querySelector('#' + child_id).setAttribute("name", child_id + '[' + file_index + ']');
            clone.querySelector('#' + child_id).disabled = false;
            clone.querySelector('#' + child_id).setAttribute("id", child_id + '[' + file_index + ']');
            return clone;
        }

        function addFile() {
            var table = document.getElementById("tab_tab");
            var file_index = document.getElementById("file_index");
            file_index.value = ++file_index.value;
            var file_clone = clone("file_row", file_index.value, "document");
            var name_clone = clone("name_row", file_index.value, "name");
            var final_row = document.getElementById("final_row").cloneNode(false);
            table.appendChild(file_clone);
            table.appendChild(name_clone);
            table.appendChild(final_row);
            var total_files = document.getElementById("total_files");
            total_files.value = ++total_files.value;
            //change the following using jquery if necessary
            var remove = document.getElementsByName("remove");
            for (var i = 0; i < remove.length; i++)
                remove[i].style.display = "inline";
        }

        // General Cost

        //Post perk form to server using ajax (add)
        $('#add_costs').on('click', function () {
            var strUrl = '/vehicle_management/addcosts';
            var formName = 'add-costs-form';
            var modalID = 'add-costs-modal';
            var submitBtnID = 'add_costs';
            var redirectUrl = '/vehicle_management/return_vehicle/{{ $returnVeh->id }}';
            var successMsgTitle = 'New Record Added!';
            var successMsg = 'The Record  has been updated successfully.';
            modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });


    </script>
@endsection