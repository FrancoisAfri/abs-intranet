@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- Include Date Range Picker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
    <!--Time Charger-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- year picker -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css"
          rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script> </head>
@endsection
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h4 class="box-title"></h4>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>

                <div align="center" class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title"> Vehicle Management-{{ !empty($vehiclemaintenances->vehicle_model . ' ' . $vehiclemaintenances->vehicle_registration . ' ' . $vehiclemaintenances->year) ? $vehiclemaintenances->vehicle_model . ' ' . $vehiclemaintenances->vehicle_registration . ' ' . $vehiclemaintenances->year : ''}}

                        </h3>
                    </div>
                    <div class="box-body">
                        <a href="/Jobcard_management/addJob_card" class="btn btn-app">
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
                        <a href="/vehicle_management/group_admin" class="btn btn-app">
                            <i class="fa fa-wpforms"></i> Warranties
                        </a>
                        <a href="/vehicle_management/group_admin" class="btn btn-app">
                            <i class="fa fa-money"></i> General Cost
                        </a>



                        <table class = "table table-striped table-bordered">

                                @foreach ($vehiclemaintenance as $vehiclemaintenance)
                            <tr>

                                <td class="caption">Fleet Number</td><td>{{ !empty($vehiclemaintenance->fleet_number) ? $vehiclemaintenance->fleet_number : ''}}</td>
                                <td class="caption">Year</td><td>{{ !empty($vehiclemaintenance->year) ? $vehiclemaintenance->year : ''}}</td>
                            </tr>
                            <tr>
                                <td class="caption">Registration</td><td>1 </td>
                                <td class="caption">Engine Number</td><td>{{ !empty($vehiclemaintenance->engine_number) ? $vehiclemaintenance->engine_number : ''}}</td>
                            </tr>
                            <tr>
                                <td class="caption" width="25%">Make</td><td width="25%">{{ !empty($vehiclemaintenance->vehicle_make) ? $vehiclemaintenance->vehicle_make : ''}}</td>
                                <td class="caption">Vehicle Type</td><td>{{ !empty($vehiclemaintenance->vehicle_type) ? $vehiclemaintenance->vehicle_type : ''}}</td>
                            </tr>
                            <tr>
                                <td class="caption" width="25%">Model</td><td width="25%">{{ !empty($vehiclemaintenance->vehicle_model) ? $vehiclemaintenance->vehicle_model : ''}}</td>
                                <td class="caption">Licence Next Renewal Date</td><td></td>
                            </tr>
                            <tr>
                                <td class="caption">Chassis Number</td><td>{{ !empty($vehiclemaintenance->chassis_number) ? $vehiclemaintenance->chassis_number : ''}}</td>
                                <td class="caption">COF Expiry Date</td><td></td>
                            </tr>
                            <tr>
                                <td class="caption">Vehicle Color</td><td>{{ !empty($vehiclemaintenance->vehicle_color) ? $vehiclemaintenance->vehicle_color : ''}}</td>
                                <td class="caption">Purchase Price</td><td>0
                                </td></tr><tr>	<td class="caption">Machine Hours</td><td>1</td>	<td class="caption">Vehicle Cell Number</td><td>{{ !empty($vehiclemaintenance->cell_number) ? $vehiclemaintenance->cell_number : ''}}</td>
                            </tr>
                            <tr>
                                <td class="caption">Fuel Type</td><td>{{ !empty($vehiclemaintenance->fuel_type) ? $vehiclemaintenance->fuel_type : ''}}</td>
                                <td class="caption">Tracking Cell Number</td><td>{{ !empty($vehiclemaintenance->tracking_umber) ? $vehiclemaintenance->tracking_umber : ''}}</td>
                            </tr>
                            <tr>
                                <td class="caption">Tank Size</td><td>{{ !empty($vehiclemaintenance->size_of_fuel_tank) ? $vehiclemaintenance->size_of_fuel_tank : ''}}</td>
                                <td class="caption">Fleet Card</td><td></td>
                            </tr>
                            <tr>
                                <td class="caption">Division</td><td>Marhine Plant Hire</td>
                                <td class="caption">Vehicle Owner Name</td><td>{{ !empty($vehiclemaintenance->vehicle_owner) ? $vehiclemaintenance->vehicle_owner : ''}}</td>
                            </tr>
                            <tr>
                                <td class="caption">Department</td><td></td>
                                <td class="caption">Title Holder Name</td><td>Marhine Plant Hire</td>
                            </tr>
                            <tr>
                                <td class="caption">Responsible Person</td><td></td>
                                <td class="caption">Registration Paper</td><td></td>
                            </tr>
                            <tr>
                                <td class="caption">Extras</td><td>{{ !empty($vehiclemaintenance->extras) ? $vehiclemaintenance->extras : ''}}</td>
                                <td class="caption">Status</td><td>{{ !empty($vehiclemaintenance->status) ? $vehiclemaintenance->status : ''}}</td>
                            </tr>





                        </table>


                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-body" align="center">
                    {{--//<a href="/vehicle_management/group_admin" class="btn btn-sm btn-default btn-flat">Edit</a>--}}
                    <button vehice="button" id="edit_compan" class="btn btn-sm btn-default btn-flat" data-toggle="modal" data-target="#edit-vehicle-modal" data-id="{{ $vehiclemaintenance->id }}" data-extras="{{ $vehiclemaintenance->extras }}" data-cell_number="{{$vehiclemaintenance->cell_number}}" ><i class="fa fa-pencil-square-o"></i> Edit</button>


                    <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat">Images</a>
                    <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat">Key Tracking</a>
                    <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat">Permit/Licences</a>
                    <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat">Documents</a>
                    <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat">Contracts</a>
                    <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat">Notes</a>
                    <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat">Reminders</a>

                </div>
                @endforeach
            </div>
        </div>
        @include('Vehicles.partials.edit_vehicledetails_modal')
    </div>
@endsection
@section('page_script')
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

    <script src="/custom_components/js/modal_ajax_submit.js"></script>



    <script>

        $('#back_button').click(function () {
            location.href = '/product/Packages';
        });
        $(function () {
            $(".select2").select2();
            $('.hours-field').hide();
            $('.comp-field').hide();
            var moduleId;
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

            //
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });

            //Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '10%' // optional
            });

            $(document).ready(function () {

                $('#year').datepicker({
                    minViewMode: 'years',
                    autoclose: true,
                    format: 'yyyy'
                });

            });

            $('#rdo_package, #rdo_product').on('ifChecked', function () {
                var allType = hideFields();
                if (allType == 1) $('#box-subtitle').html('Site Address');
                else if (allType == 2) $('#box-subtitle').html('Temo Site Address');
            });

            //

            $('#rdo_fin, #rdo_comp').on('ifChecked', function () {
                var allType = hidenFields();
                if (allType == 1) $('#box-subtitle').html('Site Address');
                else if (allType == 2) $('#box-subtitle').html('Temo Site Address');
            });


            function hideFields() {
                var allType = $("input[name='promotion_type']:checked").val();
                if (allType == 1) {
                    $('.hours-field').hide();
                    $('.odometer-field').show();
                }
                else if (allType == 2) {
                    $('.odometer-field').hide();
                    $('.hours-field').show();
                }
                return allType;
            }

            //
            function hidenFields() {
                var allType = $("input[name='title_type']:checked").val();
                if (allType == 1) {
                    $('.comp-field').hide();
                    $('.fin-field').show();
                }
                else if (allType == 2) {
                    $('.fin-field').hide();
                    $('.comp-field').show();
                }
                return allType;
            }

            $('#add_vehicledetails').on('click', function () {
                //console.log('strUrl');
                var strUrl = '/vehicle_management/add_vehicleDetails';
                var modalID = 'add-vehicledetails-modal';
                var objData = {
                    vehicle_make: $('#' + modalID).find('#vehicle_make').val(),
                    vehicle_model: $('#' + modalID).find('#vehicle_model').val(),
                    vehicle_type: $('#' + modalID).find('#vehicle_type').val(),
                    year: $('#' + modalID).find('#year').val(),
                    vehicle_registration: $('#' + modalID).find('#vehicle_registration').val(),
                    chassis_number: $('#' + modalID).find('#chassis_number').val(),
                    engine_number: $('#' + modalID).find('#engine_number').val(),
                    vehicle_color: $('#' + modalID).find('#vehicle_color').val(),
                    odometer_reading: $('#' + modalID).find('#odometer_reading').val(),
                    hours_reading: $('#' + modalID).find('#hours_reading').val(),
                    fuel_type: $('#' + modalID).find('#fuel_type').val(),
                    size_of_fuel_tank: $('#' + modalID).find('#size_of_fuel_tank').val(),
                    cell_number: $('#' + modalID).find('#cell_number').val(),
                    tracking_umber: $('#' + modalID).find('#tracking_umber').val(),
//                            fleet_number: $('#' + modalID).find('#fleet_number').val(),
                    vehicle_owner: $('#' + modalID).find('#vehicle_owner').val(),
                    financial_institution: $('#' + modalID).find('#financial_institution').val(),
                    company: $('#' + modalID).find('#company').val(),
                    extras: $('#' + modalID).find('#extras').val(),
                    image: $('#' + modalID).find('#image').val(),
                    registration_papers: $('#' + modalID).find('#registration_papers').val(),
                    property_type: $('#' + modalID).find('#property_type').val(),
                    _token: $('#' + modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'add_maintenance';
                var redirectUrl = '/vehicle_management/add_vehicle';
                var successMsgTitle = 'Fleet Type Added!';
                var successMsg = 'The Fleet Type has been updated successfully.';
                //var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            var doc_typeID;
            $('#edit-vehicle-modal').on('show.bs.modal', function (e) {
                //console.log('kjhsjs');
                var btnEdit = $(e.relatedTarget);
                doc_typeID = btnEdit.data('id');
                var vehicle_make = btnEdit.data('vehicle_make');
                var vehicle_model = btnEdit.data('vehicle_model');
                var vehicle_type = btnEdit.data('vehicle_type');
                var modal = $(this);
                modal.find('#vehicle_make').val(vehicle_make);
                modal.find('#vehicle_model').val(vehicle_model);
                modal.find('#vehicle_type').val(vehicle_type);

            });
            $('#edit_vehicle').on('click', function () {
                var strUrl = '/vehicle_management/edit_vehicleDetails/' + doc_typeID;
                // Product/category_edit/{Category}
                var modalID = 'edit-category-modal';
                var objData = {
                    vehicle_make: $('#' + modalID).find('#vehicle_make').val(),
                    vehicle_model: $('#' + modalID).find('#vehicle_model').val(),
                    vehicle_type: $('#' + modalID).find('#vehicle_type').val(),
                    _token: $('#' + modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'save_category';
                var redirectUrl = '/product/Categories';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'Category modal has been updated successfully.';
                var Method = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
            });

        });
    </script>
@endsection
