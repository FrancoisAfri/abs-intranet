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
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title"> Key Details </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                <strong class="lead">Vehicle Details</strong><br>

                                @if(!empty($vehiclemaker))
                                    | &nbsp; &nbsp; <strong>Vehicle Make:</strong> <em>{{ $vehiclemaker->name }}</em> &nbsp;
                                    &nbsp;
                                @endif
                                @if(!empty($vehiclemodeler))
                                    -| &nbsp; &nbsp; <strong>Vehicle Model:</strong> <em>{{ $vehiclemodeler->name }}</em>
                                    &nbsp; &nbsp;
                                @endif
                                @if(!empty($vehicleTypes))
                                    -| &nbsp; &nbsp; <strong>Vehicle Type:</strong> <em>{{ $vehicleTypes->name }}</em> &nbsp;
                                    &nbsp;
                                @endif
                                @if(!empty($maintenance->vehicle_registration))
                                    -| &nbsp; &nbsp; <strong>Vehicle Registration:</strong>
                                    <em>{{ $maintenance->vehicle_registration }}</em> &nbsp; &nbsp;
                                @endif
                                @if(!empty($maintenance->year))
                                    -| &nbsp; &nbsp; <strong>Year:</strong> <em>{{ $maintenance->year }}</em> &nbsp;
                                    &nbsp;
                                @endif
                                @if(!empty($maintenance->vehicle_color))
                                    -| &nbsp; &nbsp; <strong>Vehicle Color:</strong>
                                    <em>{{ $maintenance->vehicle_color }}</em> &nbsp; &nbsp; -|
                                @endif

                            </p>
                        </div>
                    </div>
					<div align="center">
                        <!--  -->
                        <a href="{{ '/vehicle_management/viewdetails/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-bars"></i> General Details
                        </a>
                        <a href="{{ '/vehicle_management/bookin_log/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-book"></i> Booking Log
                        </a>

                        <a href="{{ '/vehicle_management/fuel_log/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-tint"></i> Fuel Log
                        </a>

                        <a href="{{ '/vehicle_management/oil_log/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-file-o"></i> Oil Log
                        </a>

                        <a href="{{ '/vehicle_management/incidents/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-medkit"></i> Incidents
                        </a>
                        <a href="{{ '/vehicle_management/fines/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-list-alt"></i> Fines
                        </a>
                        <a href="{{ '/vehicle_management/service_details/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-area-chart"></i> Service Details
                        </a>
                        <a href="{{ '/vehicle_management/insurance/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-car"></i>Insurance
                        </a>
                        <a href="{{ '/vehicle_management/warranties/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-snowflake-o"></i>Warranties
                        </a>
                        <a href="{{ '/vehicle_management/general_cost/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-money"></i> General Cost
                        </a>
						<a href="{{ '/vehicle_management/fleet-communications/' . $maintenance->id }}"
                                   class="btn btn-app"><i class="fa fa-money"></i> Communications</a>
                        <!--  -->
                    </div>
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 10px; text-align: center;"></th>
                            <th style="width: 5px; text-align: center;"> Issued To</th>
                            <th>Employee</th>
                            <th>Safe Name</th>
                            <th> Safe Controller</th>
                            <th> Date Issued</th>
                            <th>Date Status Change</th>
                            <th>Issued By</th>
                            <th>Description</th>
                            <th>Key Status</th>
                            <th>Date Lost</th>
                            <th>Reason for loss</th>
                        </tr>
                        @if (count($keytracking) > 0)
                            @foreach ($keytracking as $key)
                                <tr id="categories-list">
                                    <td nowrap>
                                        <button vehice="button" id="edit_compan" class="btn btn-warning  btn-xs"
                                                data-toggle="modal" data-target="#edit-key-modal"
                                                data-id="{{ $key->id }}" data-key_number="{{ $key->key_number }}"
                                                data-key_type="{{$key->key_type}}"
                                                data-key_status="{ {$key->key_status}}"
                                                data-description="{{$key->description}}"
                                                data-date_issued="{{date(' d M Y', $key->date_issued)}}"
                                                data-issued_by="{{ $key->issued_by}}"
                                                data-reason_loss="{{ $key->reason_loss}}"
                                                data-date_lost="{{ date(' d M Y', $key->date_lost) }}"

                                        ><i class="fa fa-pencil-square-o"></i> Edit
                                        </button>
                                    </td>
                                    <td>{{ (!empty( $key->firstname)) ?  $key->employee : ''}} </td>
                                    <td>{{ (!empty( $key->firstname . ' ' . $key->surname)) ?   $key->firstname . ' ' . $key->surname : ''}} </td>
                                    <td>{{ (!empty( $key->safeName)) ?  $key->safeName : ''}} </td>
                                    <td>{{ (!empty( $key->safeController)) ?  $key->safeController : ''}} </td>
                                    <td>{{ !empty($key->date_issued) ? date(' d M Y', $key->date_issued) : '' }}</td>
                                    <td></td>
                                    <td>{{ (!empty( $key->issuedBy)) ?  $key->issuedBy : ''}} </td>
                                    <td>{{ (!empty( $key->description)) ?  $key->description : ''}} </td>
                                    <td>{{ (!empty($key->key_status)) ?  $keyStatus[$key->key_status] : ''}} </td>
                                    @if (isset($key) && $key->key_status === 3)
                                        <td bgcolor="red">{{ (!empty($key->date_lost)) ?  date(' d M Y', $key->date_lost) : ''}} </td>
                                        <td bgcolor="red" >{{ (!empty($key->reason_loss)) ?  $key->reason_loss : ''}} </td>
                                    @endif

                                </tr>
                            @endforeach
                        @else
                            <tr id="categories-list">
                                <td colspan="14">
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        No Record for this vehicle, please start by adding Record for this
                                        vehicle..
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </table>
                    <!--   </div> -->
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                        <button type="button" id="cat_module" class="btn btn-warning pull-right" data-toggle="modal"
                                data-target="#add-key-modal">Add New Key Details
                        </button>
                    </div>
                </div>
            </div>
            <!-- Include add new prime rate modal -->
        @include('Vehicles.partials.add_key_modal')
        @include('Vehicles.partials.edit_key_modal')
        <!-- Include delete warning Modal form-->

        </div>


        @endsection

        @section('page_script')
            <script src="/custom_components/js/modal_ajax_submit.js"></script>
            <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
            <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
            <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
            <!-- iCheck -->
            <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
            <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
            <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
            <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
            <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
            <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js"
                    type="text/javascript"></script>
            <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
            <script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js"
                    type="text/javascript"></script>
            <!-- the main fileinput plugin file -->
            <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
            <!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
            <script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
            <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

            <!-- InputMask -->
            <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
            <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
            <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
            <script>
                function postData(id, data) {
                    if (data == 'actdeac') location.href = "/vehice/fleetcard_act/" + id;

                }

                $('#back_button').click(function () {
                    location.href = '/vehicle_management/viewdetails/{{ $maintenance->id }}';
                });

                $(function () {
                    var moduleId;
                    //Initialize Select2 Elements
                    $(".select2").select2();
                    $('.safe-field').hide();
                    $('.lost-field').hide();


                    //Tooltip

                    $('[data-toggle="tooltip"]').tooltip();

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

                    //

                    $(".js-example-basic-multiple").select2();

                    $('.datepicker').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });
                     $(document).ready(function () {
                        $('input[name="date_issued"]').datepicker({
                                format: 'dd/mm/yyyy',
                                autoclose: true,
                                todayHighlight: true
                            });	
		                });


                    $(function () {
                        $('img').on('click', function () {
                            $('.enlargeImageModalSource').attr('src', $(this).attr('src'));
                            $('#enlargeImageModal').modal('show');
                        });
                    });

                    //Initialize iCheck/iRadio Elements
                    $('input').iCheck({
                        checkboxClass: 'icheckbox_square-blue',
                        radioClass: 'iradio_square-blue',
                        increaseArea: '10%' // optional
                    });

                    $(document).ready(function () {

                        $('#date_issued').datepicker({
                            format: 'dd/mm/yyyy',
                            autoclose: true,
                            todayHighlight: true
                        });

                    });

                    $(document).ready(function () {

                        $('#issued').datepicker({
                            format: 'dd/mm/yyyy',
                            autoclose: true,
                            todayHighlight: true
                        });

                    });

                    $(document).ready(function () {

                        $('#date_lost').datepicker({
                            format: 'dd/mm/yyyy',
                            autoclose: true,
                            todayHighlight: true
                        });

                    });

                    //#hide fields on loss click
                    $('#key_status').on("click", function (event) {
                        var levID = document.getElementById("key_status").value;
                        // alert (levID);
                        if (levID == 3) {
                            $('.lost-field').show();
                        } else if (levID == 0 , 1 , 2 , 4) {
                            $('.lost-field').hide();
                        }
                    });


                    $('#Status').on("click", function (event) {
                        var levID = document.getElementById("Status").value;
                        // alert (levID);
                        if (levID == 3) {
                            $('.lost-field').show();
                            $('.notes-field').hide();

                        } else if (levID == 0 , 1 , 2 , 4) {
                            $('.lost-field').hide();
                            $('.notes-field').show();
                        }
                    });


                    $('#rdo_user, #rdo_safe').on('ifChecked', function () {
                        var allType = hideFields();
                        if (allType == 1) $('#box-subtitle').html('Site Address');
                        else if (allType == 2) $('#box-subtitle').html('Temo Site Address');
                    });


                    function hideFields() {
                        var allType = $("input[name='key']:checked").val();
                        if (allType == 1) {
                            $('.safe-field').hide();
                            $('.user-field').show();
                        }
                        else if (allType == 2) {
                            $('.user-field').hide();
                            $('.safe-field').show();
                        }
                        return allType;
                    }


                    //Post perk form to server using ajax (add)
                    $('#add-key-card').on('click', function () {
                        var strUrl = '/vehicle_management/add_keys';
                        var formName = 'add-key-form';
                        var modalID = 'add-key-modal';;
                        var submitBtnID = 'add-key-card';
                        var redirectUrl = '/vehicle_management/keys/{{ $maintenance->id }}';
                        var successMsgTitle = 'New Record Added!';
                        var successMsg = 'The Record  has been Added successfully.';
                        modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                    });


                    var keyID;
                    $('#edit-key-modal').on('show.bs.modal', function (e) {
                        //console.log('kjhsjs');
                        var btnEdit = $(e.relatedTarget);
                        if (parseInt(btnEdit.data('id')) > 0) {
                            keyID = btnEdit.data('id');
                        }
                        var key_number = btnEdit.data('key_number');
                        var valueID = btnEdit.data('valueID');
                        var key_status = btnEdit.data('key_status');
                        var reason_loss = btnEdit.data('reason_loss');
                        var date_lost = btnEdit.data('date_lost');
                        var description = btnEdit.data('description');
                        var date_issued = btnEdit.data('date_issued');
                        var issued_by = btnEdit.data('issued_by');
                        var employee = btnEdit.data('employee');
//                        var key_type = btnEdit.data('key_type');
//                        var key = btnEdit.data('key');
//                        var safe_name = btnEdit.data('safe_name');
//                        var safe_controller = btnEdit.data('safe_controller');
//                        var issued_to = btnEdit.data('issued_to');
                        var modal = $(this);
                        modal.find('#key_number').val(key_number);
                        modal.find('#valueID').val(valueID);
                        modal.find('#key_status').val(key_status);
                        modal.find('#reason_loss').val(reason_loss);
                        modal.find('#date_lost').val(date_lost);
                        modal.find('#date_issued').val(date_issued);
                        modal.find('#description').val(description);
                        modal.find('#issued_by').val(issued_by);

                    });
                    {{--$('#edit_key').on('click', function () {--}}
                        {{--var strUrl = '/vehicle_management/edit_key/' + keyID;--}}
                        {{--var modalID = 'edit-key-modal';--}}
                        {{--var objData = {--}}
                            {{--date_issued: $('#' + modalID).find('#date_issued').val(),--}}
                            {{--key_number: $('#' + modalID).find('#key_number').val(),--}}
                            {{--key_type: $('#' + modalID).find('#key_type').val(),--}}
                            {{--key_status: $('#' + modalID).find('#key_status').val(),--}}
                            {{--description: $('#' + modalID).find('#description').val(),--}}
                            {{--key: $('#' + modalID).find('input:checked[name = key]').val(),--}}
                            {{--issued_by: $('#' + modalID).find('#issued_by').val(),--}}
                            {{--safe_name: $('#' + modalID).find('#safe_name').val(),--}}
                            {{--safe_controller: $('#' + modalID).find('#safe_controller').val(),--}}
                            {{--issued_to: $('#' + modalID).find('#issued_to').val(),--}}
                            {{--employee: $('#' + modalID).find('#employee').val(),--}}
                            {{--date_lost: $('#' + modalID).find('#date_lost').val(),--}}
                            {{--valueID: $('#' + modalID).find('#valueID').val(),--}}
                            {{--reason_loss: $('#' + modalID).find('#reason_loss').val(),--}}
                            {{--_token: $('#' + modalID).find('input[name=_token]').val()--}}
                        {{--};--}}
                        {{--var submitBtnID = 'edit_key';--}}
                        {{--var redirectUrl = '/vehicle_management/key/{{ $maintenance->id }}';--}}
                        {{--var successMsgTitle = 'Changes Saved!';--}}
                        {{--var successMsg = 'The Key Details have been updated successfully.';--}}
                        {{--var Method = 'PATCH';--}}
                        {{--modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);--}}
                    {{--});--}}

                    //Post perk form to server using ajax (edit)
                    $('#edit_key').on('click', function () {
                        var strUrl = '/vehicle_management/edit_key/' + keyID;
                        var formName = 'edit-key-form';
                        var modalID = 'edit-key-modal';
                        var submitBtnID = 'edit_key';
                        var redirectUrl = '/vehicle_management/keys/{{$maintenance->id}}';
                        var successMsgTitle = 'Changes Saved!';
                        var successMsg = 'The  details have been updated successfully!';
                        var Method = 'PATCH';
                        modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                    });


                });
            </script>
@endsection
