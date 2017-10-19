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
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Add Vehicle Details</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <form method="POST" action=" " enctype="multipart/form-data">
                {{ csrf_field() }}
            {{ method_field('PATCH') }}
            <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 10px; text-align: center;"></th>
                            <th style="width: 5px; text-align: center;">Image</th>
                            <th>Vehicle Model/Year</th>
                            <th>Fleet Number</th>
                            <th>Vehicle Registration</th>
                            <th>VIN Numberr</th>
                            <th>Engine Number</th>
                            <th>Odometer/Hours</th>
                            <th>Company</th>
                            <th>Department</th>
                            <th style="width: 5px; text-align: center;"></th>
                        </tr>
                        @if (count($vehiclemaintenance) > 0)
                            @foreach ($vehiclemaintenance as $card)
                                <tr id="categories-list">
                                    <td>
                                    <a href="{{ '/vehicle_management/viewdetails/' . $card->id }}" id="edit_compan" class="btn btn-default  btn-xs"   data-id="{{ $card->id }}" >View</a>

                                    <div id="my_div" class="hidden">
                                    <a href="http://www.google.com">booking log</a>
                                    </div>
                                    <div id="my_div" class="hidden">
                                    <a href="http://www.google.com">fuel log</a>
                                    </div>
                                    <div id="my_div" class="hidden">
                                    <a href="http://www.google.com">oil log</a>
                                    </div>
                                    <div id="my_div" class="hidden">
                                    <a href="http://www.google.com">incident</a>
                                    </div>
                                    <div id="my_div" class="hidden">
                                    <a href="http://www.google.com">fines</a>
                                    </div>


                                   </td>
                                    <td>{{ (!empty( $card->image)) ?  $card->image : ''}} </td>
                                    <td>{{ !empty($card->vehicle_model . ' ' . $card->year ) ? $card->vehicle_model  . ' ' . $card->year: ''}}</td>
                                    <td></td>
                                    <td>{{ !empty($card->vehicle_registration) ? $card->vehicle_registration : ''}}</td>
                                    <td></td>
                                    <td>{{ !empty($card->engine_number) ? $card->engine_number : ''}}</td>
                                    <td>{{ !empty($card->odometer_reading . ' ' . $card->hours_reading ) ? $card->odometer_reading  . ' ' . $card->hours_reading: ''}}</td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <!--   leave here  -->
                                        <button vehice="button" id="view_ribbons"
                                                class="btn {{ (!empty($card->status) && $card->status == 1) ? " btn-danger " : "btn-success " }}
                                                        btn-xs" onclick="postData({{$card->id}}, 'actdeac');"><i
                                                    class="fa {{ (!empty($card->status) && $card->status == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($card->status) && $card->status == 1) ? "De-Activate" : "Activate"}}
                                        </button>
                                    </td>
                                    {{--<td>--}}
                                        {{--<button type="button" class="btn btn-danger btn-xs" data-toggle="modal"--}}
                                                {{--data-target="#delete-contact-warning-modal"><i class="fa fa-trash"></i>--}}
                                            {{--Delete--}}
                                        {{--</button>--}}
                                    {{--</td>--}}
                                </tr>
                            @endforeach
                        @else
                            <tr id="categories-list">
                                <td colspan="5">
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        No FleetType to display, please start by adding a new FleetType..
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </table>
                    <!--   </div> -->
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn btn-warning pull-left" id="back_button"><i
                                    class="fa fa-arrow-left"></i> Back</button>

                        <button type="button" id="cat_module" class="btn btn-warning pull-right" data-toggle="modal"
                                data-target="#add-vehicledetails-modal">Add new Incident Type
                        </button>

                    </div>
                </div>
            </div>
            @include('Vehicles.FleetManagement.partials.add_vehicleDetails_modal')
            @include('Vehicles.partials.add_vehicledetails_modal')
        </div>

        @endsection

        @section('page_script')
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
                function postData(id, data) {
                    if (data == 'qual') location.href = "/hr/addqul/" + id;
                    // else if (data == 'doc') location.href = "/hr/adddoc/" + id;
                    // else if (data == 'dactive') location.href = "/hr/document/" + id + '/activate';
                    // else if (data == 'activateGroupLevel') location.href = '/hr/grouplevel/activate/' + id;
                }

                $('#back_button').click(function () {
                    location.href = '/vehicle_management/manage_fleet';
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
                    $('#edit-category-modal').on('show.bs.modal', function (e) {
                        //console.log('kjhsjs');
                        var btnEdit = $(e.relatedTarget);
                        doc_typeID = btnEdit.data('id');
                        var name = btnEdit.data('name');
                        var description = btnEdit.data('description');
                        //var employeeName = btnEdit.data('employeename');
                        var modal = $(this);
                        modal.find('#name').val(name);
                        modal.find('#description').val(description);

                    });
                    $('#edit_category').on('click', function () {
                        var strUrl = '/Product/category_edit/' + doc_typeID;
                        // Product/category_edit/{Category}
                        var modalID = 'edit-category-modal';
                        var objData = {
                            name: $('#' + modalID).find('#name').val(),
                            description: $('#' + modalID).find('#description').val(),
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
