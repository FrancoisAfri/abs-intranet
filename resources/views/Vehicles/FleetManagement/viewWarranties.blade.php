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
                    <h3 class="box-title"> Vehicle Warranties </h3>
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

                        <a href="/vehicle_management/Incidents_type" class="btn btn-app">
                            <i class="fa fa-list-alt"></i> Fines
                        </a>
                        <a href="/vehicle_management/group_admin" class="btn btn-app">
                            <i class="fa fa-comments"></i> Service Details
                        </a>
                        <a href="/vehicle_management/group_admin" class="btn btn-app">
                            <i class="fa fa-yoast"></i> Insurance
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
                            <th>Service Provider</th>
                            <th>Policy/Document #</th>
                            <th>Type</th>
                            <th>Inception Date</th>
                            <th>Expiry Date</th>
                            <th>Warranty Amount (R)</th>
                            <th>Maximum Kliometres</th>
                            <th> Documents</th>
                            <th style="width: 5px; text-align: center;"></th>
                        </tr>
                        @if (count($vehiclewarranties) > 0)
                            @foreach ($vehiclewarranties as $reminder)
                                <tr id="categories-list">
                                    <td nowrap>
                                        <button reminder="button" id="edit_compan" class="btn btn-warning  btn-xs"
                                                data-toggle="modal" data-target="#edit-warrantie-modal"
                                                data-id="{{ $reminder->id }}" 
                                                data-description="{{ $reminder->description }}"><i
                                                    class="fa fa-pencil-square-o"></i> Edit
                                        </button>
                                    </td>
                                    
                                    <td>{{ !empty($reminder->service_provider) ? $reminder->service_provider : '' }}</td>
                                    <td>{{ !empty($reminder->policy_no) ?  $reminder->policy_no : '' }}</td>
                                    <td>{{ !empty($reminder->type) ? $reminder->type : '' }}</td>
                                    <td>{{ !empty($reminder->inception_date) ? date(' d M Y', $reminder->inception_date) : '' }}</td>
                                    <td>{{ !empty($reminder->exp_date) ? date(' d M Y', $reminder->exp_date) : '' }}</td>
                                    <td>R{{ !empty($reminder->warranty_amount) ?  $reminder->warranty_amount : '' }}.00</td>
                                    <td>{{ !empty($reminder->kilometers) ?  $reminder->kilometers : '' }}</td>
                                    <td>{{ !empty($reminder->name) ?  $reminder->name : '' }}</td>
                                    <td>
                                        <!--   leave here  -->
                                        <button reminder="button" id="view_ribbons"
                                                class="btn {{ (!empty($reminder->status) && $reminder->status == 1) ? " btn-danger " : "btn-success " }}
                                                        btn-xs" onclick="postData({{$reminder->id}}, 'actdeac');"><i
                                                    class="fa {{ (!empty($reminder->status) && $reminder->status == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($reminder->status) && $reminder->status == 1) ? "De-Activate" : "Activate"}}
                                        </button>
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
                                data-target="#add-warrantie-modal">Add new Warranty
                        </button>
                    </div>
                </div>
            </div>
            <!-- Include add new prime rate modal -->
        @include('Vehicles.partials.add_vehicleWarranties_modal')
        @include('Vehicles.partials.edit_vehicleWarranties_modal')
        

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
                    if (data == 'actdeac') location.href = "/vehicle_management/warranty_act/" + id;

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

                    $('#inception_date').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });

                     $('#exp_date').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });


                });

               
                //Post perk form to server using ajax (add)
                $('#add_warrantie').on('click', function () {
                    var strUrl = '/vehicle_management/addwarranty';
                    var formName = 'add-warrantie-form';
                    var modalID = 'add-warrantie-modal';
                    var submitBtnID = 'add_warrantie';
                    var redirectUrl = '/vehicle_management/warranties/{{ $maintenance->id }}';
                    var successMsgTitle = 'New Record Added!';
                    var successMsg = 'The Record  has been updated successfully.';
                    modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                });


                var costsID;
                $('#edit-costs-modal').on('show.bs.modal', function (e) {
                    var btnEdit = $(e.relatedTarget);
                    costsID = btnEdit.data('id');
                    var date = btnEdit.data('date');
                    var document_number = btnEdit.data('document_number');
                    var supplier_name = btnEdit.data('supplier_name');
                    var cost_type = btnEdit.data('cost_type');
                    var cost = btnEdit.data('cost');
                    var litres = btnEdit.data('litres');
                    var description = btnEdit.data('description');
                    var person_esponsible = btnEdit.data('person_esponsible');
                    var valueID = btnEdit.data('valueID');
                    var modal = $(this);
                    modal.find('#date').val(date);
                    modal.find('#document_number').val(document_number);
                    modal.find('#supplier_name').val(supplier_name);
                    modal.find('#cost_type').val(cost_type);
                    modal.find('#cost').val(cost);
                    modal.find('#litres').val(litres);
                    modal.find('#description').val(description);
                    modal.find('#person_esponsible').val(person_esponsible);
                    modal.find('#valueID').val(valueID);
                });

                $('#edit_costs').on('click', function () {
                    var strUrl = '/vehicle_management/edit_costs/' + costsID;
                    var modalID = 'edit-costs-modal';
                    var objData = {
                        date: $('#' + modalID).find('#date').val(),
                        document_number: $('#' + modalID).find('#document_number').val(),
                        supplier_name: $('#' + modalID).find('#supplier_name').val(),
                        cost_type: $('#' + modalID).find('#cost_type').val(),
                        cost: $('#' + modalID).find('#cost').val(),
                        litres: $('#' + modalID).find('#litres').val(),
                        description: $('#' + modalID).find('#description').val(),
                        person_esponsible: $('#' + modalID).find('#person_esponsible').val(),
                        valueID: $('#' + modalID).find('#valueID').val(),
                        _token: $('#' + modalID).find('input[name=_token]').val()
                    };
                    var submitBtnID = 'edit_costs';
                    var redirectUrl = '/vehicle_management/general_cost/{{ $maintenance->id }}';
                    var successMsgTitle = 'Changes Saved!';
                    var successMsg = 'The Record  has been updated successfully.';
                    var Method = 'PATCH';
                    modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
                });


            </script>
@endsection
