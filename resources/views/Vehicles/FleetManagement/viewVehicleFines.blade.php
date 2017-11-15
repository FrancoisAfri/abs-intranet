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
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                <strong class="lead">Vehicle Details</strong><br>

                                @if(!empty($vehiclemaker))
                                    | &nbsp; &nbsp; <strong>Vehicle Make:</strong> <em>{{ $vehiclemaker }}</em> &nbsp;
                                    &nbsp;
                                @endif
                                @if(!empty($vehiclemodeler))
                                    -| &nbsp; &nbsp; <strong>Vehicle Model:</strong> <em>{{ $vehiclemodeler }}</em>
                                    &nbsp; &nbsp;
                                @endif
                                @if(!empty($vehicleTypes))
                                    -| &nbsp; &nbsp; <strong>Vehicle Type:</strong> <em>{{ $vehicleTypes }}</em> &nbsp;
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
                        <a href="/vehicle_management/fleet_card" class="btn btn-app">
                            <i class="fa fa-book"></i> Booking Log
                        </a>

                        <a href="/vehicle_management/fillingstaion" class="btn btn-app">
                            <i class="fa fa-tint"></i> Fuel Log
                        </a>

                        <a href="/vehicle_management/Document_type" class="btn btn-app">
                            <i class="fa fa-file-o"></i> Oil Log
                        </a>

                        <a href="/vehicle_management/Permit" class="btn btn-app">
                            <i class="fa fa-medkit"></i> Incidents
                        </a>
                         <a href="{{ '/vehicle_management/fines/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-list-alt"></i> Fines 
                        </a>
                         <a href="{{ '/vehicle_management/service_details/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-bars"></i> Service Details
                        </a>
                        <a href="{{ '/vehicle_management/insurance/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-bars"></i>Insurance
                        </a>
                        <a href="{{ '/vehicle_management/warranties/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-bars"></i>Warranties
                        </a>
                        <a href="{{ '/vehicle_management/general_cost/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-bars"></i> General Cost
                        </a>
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
                                                data-toggle="modal" data-target="#edit-servicedetails-modal"
                                                data-id="{{ $details->id }}" 
                                               ><i class="fa fa-pencil-square-o"></i> Edit
                                                   
                                        </button>
                                    </td>
                                    <td>{{ !empty($details->date_serviced) ? date(' d M Y', $details->date_serviced) : '' }}</td>
                                    <td>{{ !empty($details->garage) ? $details->garage : '' }}</td>
                                    <td>{{ !empty($details->invoice_number) ?  $details->invoice_number : '' }}</td>
                                    <td>R {{ !empty($details->total_cost) ? $details->total_cost : '' }} .00</td>
                                    <td>{{ !empty($details->nxt_service_date) ? date(' d M Y', $details->nxt_service_date) : '' }}</td>
                                    <td>R{{ !empty($details->nxt_service_km) ?  $details->nxt_service_km : '' }}.KM</td>
                                       <td nowrap>
                                        <div class="form-group{{ $errors->has('details') ? ' has-error' : '' }}">
                                            <label for="document" class="control-label"></label>
                                            @if(!empty($details->document))
                                                <a class="btn btn-default btn-flat btn-block pull-right btn-xs"
                                                   href="{{ $details->document }}" target="_blank"><i
                                                            class="fa fa-file-pdf-o"></i> View Document</a>
                                            @else
                                                <a class="btn btn-default pull-centre btn-xs"><i
                                                            class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
                                            @endif
                                        </div>
                                        <div class="form-group{{ $errors->has('details') ? ' has-error' : '' }}">
                                            <label for="document" class="control-label"></label>
                                            @if(!empty($details->document1))
                                                <a class="btn btn-default btn-flat btn-block pull-right btn-xs"
                                                   href="{{ $details->document1 }}" target="_blank"><i
                                                            class="fa fa-file-pdf-o"></i> View Document</a>
                                            @else
                                                <a class="btn btn-default pull-centre btn-xs"><i
                                                            class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
                                            @endif
                                        </div>
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
                    <!--   </div> -->
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                        <button type="button" id="cat_module" class="btn btn-warning pull-right" data-toggle="modal"
                                data-target="#add-fines-modal">Add new Vehicle Fines
                        </button>
                    </div>
                </div>
            </div>
            <!-- Include add new prime rate modal -->
        @include('Vehicles.partials.add_vehicleFines_modal')
        @include('Vehicles.partials.edit_service_details_modal')
        

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


                var serviceID;
                $('#edit-servicedetails-modal').on('show.bs.modal', function (e) {
                    var btnEdit = $(e.relatedTarget);
                    serviceID = btnEdit.data('id');
                    var invoice_number = btnEdit.data('invoice_number');
                    var total_cost = btnEdit.data('total_cost');
                    var dateserviced = btnEdit.data('dateserviced');
                    var garage = btnEdit.data('garage');
                    var nxtservice_date = btnEdit.data('nxtservicedate');
                    var nxt_service_km = btnEdit.data('nxt_service_km');
                    var description = btnEdit.data('description');
                    var documents = btnEdit.data('documents');
                    var documents1 = btnEdit.data('documents1');
                    var valueID = btnEdit.data('valueID');
                    var modal = $(this);
                    modal.find('#invoice_number').val(invoice_number);
                    modal.find('#total_cost').val(total_cost);
                    modal.find('#dateserviced').val(dateserviced);
                    modal.find('#garage').val(garage);
                    modal.find('#nxtservice_date').val(nxtservice_date);
                    modal.find('#nxt_service_km').val(nxt_service_km);
                    modal.find('#description').val(description);
                    modal.find('#documents').val(documents);
                    modal.find('#documents1').val(documents1);
                    modal.find('#valueID').val(valueID);
                });

                 $('#edit_servicedetails').on('click', function () {
                    var strUrl = '/vehicle_management/edit_servicedetails/'+ serviceID ;
                    var formName = 'add-servicedetails-form';
                    var modalID = 'add-servicedetails-modal';
                    var submitBtnID = 'edit_servicedetails';
                    var redirectUrl = '/vehicle_management/service_details/{{ $maintenance->id }}';
                    var successMsgTitle = 'New Record Added!';
                    var successMsg = 'The Record  has been updated successfully.';
                     var Method = 'PATCH'
                    modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg,Method);
                });


            </script>
@endsection
