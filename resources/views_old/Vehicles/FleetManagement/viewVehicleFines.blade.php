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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">     
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title"> Vehicle Fines Details </h3>
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
                       {{--<a href="{{ '/vehicle_management/oil_log/' . $maintenance->id }}" class="btn btn-app">--}}
                            {{--<i class="fa fa-file-o"></i> Oil Log--}}
                        {{--</a>--}}
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
                            <th>Fine Ref</th>
                            <th> Fine Date</th>
                            <th>Fine Time</th>
                            <th>Fine Type </th>
                            <th> Amount (R)</th>
                            <th> Driver</th>
                            <th>Documents</th>
                            <th style="width: 5px; text-align: center;">Status</th>
                        </tr>
                        @if (count($vehiclefines) > 0)
                            @foreach ($vehiclefines as $details)
                                <tr id="categories-list">
                                    <td nowrap>
                                        <button details="button" id="edit_compan" class="btn btn-warning  btn-xs"
                                                data-toggle="modal" data-target="#edit-fines-modal"
                                                data-id="{{ $details->id }}"  data-date_captured="{{ date(' d M Y', $details->date_captured)}}" 
                                                data-fine_type="{{ $details->fine_type }}" data-fine_ref="{{ $details->fine_ref }}" data-dateoffine="{{ date(' d M Y', $details->date_of_fine) }}" data-timeoffine="{{ date('h:m:s', $details->time_of_fine) }}" data-amount="{{ $details->amount }}" data-reduced="{{ $details->reduced }}" data-additional_fee="{{ $details->additional_fee }}" data-location="{{ $details->location }}" data-speed="{{ $details->speed }}" data-zone_speed="{{ $details->zone_speed }}" data-driver="{{ $details->driver }}" data-magistrate_office="{{ $details->magistrate_office }}" data-courtdate="{{ date('d M Y ', $details->court_date) }}" data-amount_paid="{{ $details->amount_paid }}"  data-description="{{ $details->description }}"  data-fine_status="{{ $details->fine_status }}" data-paiddate ="{{ date(' d M Y', $details->paid_date)}}"

                                               ><i class="fa fa-pencil-square-o"></i> Edit
                                                   
                                        </button>
                                    </td>
                                    <td>{{ !empty($details->fine_ref) ? $details->fine_ref : '' }}</td>
                                    <td>{{ !empty($details->date_of_fine) ? date(' d M Y', $details->date_of_fine) : '' }}</td>
                                    <td>{{ !empty($details->time_of_fine) ? date(' h:m:z', $details->time_of_fine) : '' }}</td>
                                    <td>{{ (!empty($details->fine_type)) ?  $fineType[$details->fine_type] : ''}}</td>
                                    <td>{{ !empty($details->amount) ? 'R' .number_format($details->amount, 2) : '' }}</td>
                                    <td>{{ !empty($details->firstname . ' ' . $details->surname) ? $details->firstname . ' ' . $details->surname : '' }}</td>

                                    <td nowrap>
                                        <div class="form-group{{ $errors->has('document') ? ' has-error' : '' }}">
                                            <label for="document" class="control-label"></label>
                                            @if(!empty($details->document))
                                                <a class="btn btn-default btn-flat btn-block pull-right btn-xs"
                                                   href="{{ Storage::disk('local')->url("Vehicle/vehiclefines/$details->document") }}"
                                                   target="_blank"><i class="fa fa-file-pdf-o"></i> View Document</a>
                                            @else
                                                <a class="btn btn-default pull-centre btn-xs"><i class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
                                            @endif
                                        </div>


                                        <div class="form-group{{ $errors->has('document') ? ' has-error' : '' }}">
                                            <label for="document" class="control-label"></label>
                                            @if(!empty($details->document1))
                                                <a class="btn btn-default btn-flat btn-block pull-right btn-xs"
                                                   href="{{ Storage::disk('local')->url("Vehicle/vehiclefines/$details->document1") }}"
                                                   target="_blank"><i class="fa fa-file-pdf-o"></i> View Document</a>
                                            @else
                                                <a class="btn btn-default pull-centre btn-xs"><i class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
                                            @endif
                                        </div>
                                    </td>

                                         <td>{{ !empty($details->fine_status) ?  $status[$details->fine_status] : ''}}</td>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr id="categories-list">
                                <td colspan="10">
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        No Record for this vehicle, please start by adding a new Record for this
                                        vehicle..
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </table>
                    <div class="box-footer">
                        <button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                        <button type="button" id="cat_module" class="btn btn-warning pull-right" data-toggle="modal"
                                data-target="#add-fines-modal">Add New Vehicle Fines
                        </button>
                    </div>
                </div>
            </div>
            <!-- Include add new prime rate modal -->
        @include('Vehicles.partials.add_vehicleFines_modal')
        @include('Vehicles.partials.edit_vehicleFines_modal')
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
            <!-- time picker -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
            <script>
                function postData(id, data) {
                    if (data == 'actdeac') location.href = "/vehicle_management/policy_act/" + id;

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



                $(document).ready(function () {

                    // $('#date_captured').datepicker({
                    //     format: 'dd/mm/yyyy',
                    //     autoclose: true,
                    //     todayHighlight: true
                    // });

                    $('#time_of_fine').datetimepicker({
                             format: 'HH:mm:ss'
                        });

                     $('#date_of_fine').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });
                     
                     // 
                      $('#court_date').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });


                     $('#paid_date').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });

                     // 
                     $('#timeoffine').datetimepicker({
                             format: 'HH:mm:ss'
                        });

                     $('#dateoffine').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });
                     
                     // 
                      $('#courtdate').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });


                     $('#paiddate').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });

                });



               
                //Post perk form to server using ajax (add)
                $('#add_fines').on('click', function () {
                    var strUrl = '/vehicle_management/addvehiclefines';
                    var formName = 'add-fines-form';
                    var modalID = 'add-fines-modal';
                    var submitBtnID = 'add_fines';
                    var redirectUrl = '/vehicle_management/fines/{{ $maintenance->id }}';
                    var successMsgTitle = 'New Record Added!';
                    var successMsg = 'The Record  has been updated successfully.';
                    modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                });


                var fineID;
                $('#edit-fines-modal').on('show.bs.modal', function (e) {
                    var btnEdit = $(e.relatedTarget);
                     if (parseInt(btnEdit.data('id')) > 0) {
                       fineID = btnEdit.data('id');
                    }
                   
                    var date_captured = btnEdit.data('date_captured');
                   // var contact_number = btnEdit.data('contact_number');
                    var fine_ref = btnEdit.data('fine_ref');
                    var date_of_fine = btnEdit.data('dateoffine');
                    var time_of_fine = btnEdit.data('timeoffine');
                    var amount = btnEdit.data('amount');
                    var reduced = btnEdit.data('reduced');
                    var additional_fee = btnEdit.data('additional_fee');
                    var location = btnEdit.data('location');
                    var speed = btnEdit.data('speed');
                    var zone_speed = btnEdit.data('zone_speed');
                    var driver = btnEdit.data('driver');
                    var magistrate_office = btnEdit.data('magistrate_office');
                    var court_date = btnEdit.data('courtdate');
                    var paid_date = btnEdit.data('paiddate');
                    var amount_paid = btnEdit.data('amount_paid');
                    var description = btnEdit.data('description');
                    var fine_status = btnEdit.data('fine_status');
                    var documents = btnEdit.data('documents');
                    var documents1 = btnEdit.data('documents1');
                    var valueID = btnEdit.data('valueID');
                    var modal = $(this);
                    modal.find('#date_captured').val(date_captured);
                    modal.find('#fine_ref').val(fine_ref);
                    modal.find('#fine_ref').val(fine_ref);
                    modal.find('#date_of_fine').val(date_of_fine);
                    modal.find('#time_of_fine').val(time_of_fine);
                    modal.find('#amount').val(amount);
                    modal.find('#reduced').val(reduced);
                    modal.find('#additional_fee').val(additional_fee);
                    modal.find('#location').val(location);
                    modal.find('#speed').val(speed);
                    modal.find('#zone_speed').val(zone_speed);
                    modal.find('#driver').val(driver);
                    modal.find('#magistrate_office').val(magistrate_office);
                    modal.find('#court_date').val(court_date);
                    modal.find('#paid_date').val(paid_date);
                    modal.find('#amount_paid').val(amount_paid);
                    modal.find('#description').val(description);
                    modal.find('#fine_status').val(fine_status)
                    modal.find('#documents').val(documents);
                    modal.find('#documents1').val(documents1);
                    modal.find('#valueID').val(valueID);
                });

                 $('#edit_fines').on('click', function () {
                    var strUrl = '/vehicle_management/edit_fines/'+ fineID ;
                    var formName = 'edit-fines-form';
                    var modalID = 'edit-fines-modal';
                    var submitBtnID = 'edit_fines';
                    var redirectUrl = '/vehicle_management/fines/{{ $maintenance->id }}';
                    var successMsgTitle = 'New Record Added!';
                    var successMsg = 'The Record  has been updated successfully.';
                     var Method = 'PATCH'
                    modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg,Method);
                });


            </script>
@endsection
