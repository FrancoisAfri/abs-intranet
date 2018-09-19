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
                    <h3 class="box-title"> Vehicle Reminders </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <!-- <form class="form-horizontal" method="POST" action="/hr/document"> -->
       
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
                            <th>Name</th>
                            <th>Description</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th style="width: 5px; text-align: center;"></th>
                            <th style="width: 5px; text-align: center;"></th>
                        </tr>
                        @if (count($reminders) > 0)
                            @foreach ($reminders as $reminder)
                                <tr id="categories-list">
                                    <td nowrap>
                                        <button reminder="button" id="edit_compan" class="btn btn-warning  btn-xs"
                                                data-toggle="modal" data-target="#edit-reminder-modal"
                                                data-id="{{ $reminder->id }}" data-name="{{ $reminder->name }}"
                                                data-description="{{ $reminder->description }}"
                                                data-start_date="{{ $reminder->start_date }}"
                                                data-end_date="{{ $reminder->end_date }}"><i
                                                    class="fa fa-pencil-square-o"></i> Edit
                                        </button>
                                    </td>
                                    <td>{{ !empty($reminder->name) ?  $reminder->name : '' }}</td>
                                    <td>{{ !empty($reminder->description) ?  $reminder->description : '' }}</td>
                                    <td>{{ !empty($reminder->start_date) ? date(' d M Y', $reminder->start_date) : '' }}</td>
                                    <td>{{ !empty($reminder->end_date) ? date(' d M Y', $reminder->end_date) : '' }}</td>
                                    <td>
                                        <!--   leave here  -->
                                        <button reminder="button" id="view_ribbons"
                                                class="btn {{ (!empty($reminder->status) && $reminder->status == 1) ? " btn-danger " : "btn-success " }}
                                                        btn-xs" onclick="postData({{$reminder->id}}, 'actdeac');"><i
                                                    class="fa {{ (!empty($reminder->status) && $reminder->status == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($reminder->status) && $reminder->status == 1) ? "De-Activate" : "Activate"}}
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-xs" data-toggle="modal"
                                                data-target="#delete-contact-warning-modal"><i class="fa fa-trash"></i>
                                            Delete
                                        </button>
                                    </td>

                                </tr>
                            @endforeach
                        @else
                            <tr id="categories-list">
                                <td colspan="7">
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
                                data-target="#add-reminder-modal">Upload New Reminder
                        </button>
                    </div>
                </div>
            </div>
            <!-- Include add new prime rate modal -->
        @include('Vehicles.partials.add_reminder_modal')
        @include('Vehicles.partials.edit_reminder_modal')
        <!-- Include delete warning Modal form-->

            @if (count($reminders) > 0)
                @include('Vehicles.warnings.reminders_warning_action', ['modal_title' => 'Delete Reminder', 'modal_content' => 'Are you sure you want to delete this vehicle model ? This action cannot be undone.'])
            @endif

        </div>   
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
                    if (data == 'actdeac') location.href = "/vehicle_management/reminder_act/" + id;

                }

                $('#back_button').click(function () {
                    location.href = '/vehicle_management/viewdetails/{{ $maintenance->id }}';
                });

                var moduleId;
                //Initialize Select2 Elements
                $(".select2").select2();
                $('.zip-field').hide();


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

            //Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '10%' // optional
            });

                $(document).ready(function () {

                    $('#start_date').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });

                    $('#end_date').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });

                    $('#startdate').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });

                    $('#enddate').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });
                });

            function changetextbox() {
                var levID = document.getElementById("key_status").value;
                    if (levID == 1) {
                    $('.sex-field').hide();
                    // $('.Sick-field').show();
              } 
            }    
            

                //Post perk form to server using ajax (add)
                $('#add_reminder').on('click', function () {
                    var strUrl = '/vehicle_management/addreminder';
                    var formName = 'add-reminder-form';
                    var modalID = 'add-reminder-modal';
                    var submitBtnID = 'add_reminder';
                    var redirectUrl = '/vehicle_management/reminders/{{ $maintenance->id }}';
                    var successMsgTitle = 'New reminder Added!';
                    var successMsg = 'The Reminder  has been updated successfully.';
                    modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                });

       // });

                var reminderID;
                $('#edit-reminder-modal').on('show.bs.modal', function (e) {
                    var btnEdit = $(e.relatedTarget);
                     var btnEdit = $(e.relatedTarget);
                     if (parseInt(btnEdit.data('id')) > 0) {
                     reminderID = btnEdit.data('id');
                    }
                
                    var name = btnEdit.data('name');
                    var description = btnEdit.data('description');
                    var start_date = btnEdit.data('start_date');
                    var end_date = btnEdit.data('end_date');
                    var valueID = btnEdit.data('valueID');
                    var modal = $(this);
                    modal.find('#name').val(name);
                    modal.find('#description').val(description);
                    modal.find('#start_date').val(start_date);
                    modal.find('#end_date').val(end_date);
                    modal.find('#valueID').val(valueID);
                });

                $('#edit_reminder').on('click', function () {
                    var strUrl = '/vehicle_management/edit_reminder/' + reminderID;
                    var modalID = 'edit-reminder-modal';
                    var objData = {
                        name: $('#' + modalID).find('#name').val(),
                        description: $('#' + modalID).find('#description').val(),
                        start_date: $('#' + modalID).find('#start_date').val(),
                        end_date: $('#' + modalID).find('#end_date').val(),
                        valueID: $('#' + modalID).find('#valueID').val(),
                        _token: $('#' + modalID).find('input[name=_token]').val()
                    };
                    var submitBtnID = 'edit_reminder';
                    var redirectUrl = '/vehicle_management/reminders/{{ $maintenance->id }}';
                    var successMsgTitle = 'Changes Saved!';
                    var successMsg = 'The Reminder  has been updated successfully.';
                    var Method = 'PATCH';
                    modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
                });


            </script>
@endsection
