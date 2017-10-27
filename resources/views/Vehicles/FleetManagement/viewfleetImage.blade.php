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
                        <h3 class="box-title"> Image(s) for -{{ !empty($vehiclemaintenances->vehicle_model . ' ' . $vehiclemaintenances->vehicle_registration . ' ' . $vehiclemaintenances->year) ? $vehiclemaintenances->vehicle_model . ' ' . $vehiclemaintenances->vehicle_registration . ' ' . $vehiclemaintenances->year : ''}}

                        </h3>
                    </div>
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <div class="box-body">



                        <table class = "table table-striped table-bordered">
                                @foreach ($vehiclemaintenance as $vehiclemaintenance)
                        </table>

                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 10px; text-align: center;"></th>
                                <th style="width: 5px; text-align: center;">Image</th>
                                <th>Description</th>
                                <th>Date Uploaded</th>
                                <th> Registration</th>
                                <th>Uploaded By</th>
                                <th style="width: 5px; text-align: center;"></th>
                            </tr>
                            @if (count($vehiclemaintenance) > 0)

                                    <tr id="categories-list">
                                        <td nowrap>
                                            <button type="button" id="edit_compan" class="btn btn-default  btn-xs" data-toggle="modal" data-target="#edit-package-modal" data-id="{{ $vehiclemaintenance->id }}" data-image="{{ $vehiclemaintenance->image }}" ><i class="fa fa-pencil-square-o"></i> Edit</button>
                                        </td>
                                        <td>


                                            <div id="my_div" class="hidden">
                                                <a href="{{ '/vehicle_management/viewImage/' . $vehiclemaintenance->id }}"
                                                   id="edit_compan" class="btn btn-default  btn-xs"
                                                   data-id="{{ $vehiclemaintenance->id }}">image</a>
                                            </div>


                                        </td>
                                        <td>
                                            <div class="product-img">
                                                <img src="{{ (!empty($vehiclemaintenance->image)) ? Storage::disk('local')->url("image/$vehiclemaintenance->image") : 'http://placehold.it/60x50' }}"  alt="Product Image" width="50" height="50">
                                            </div>
                                        </td>
                                        {{--<td>{{ (!empty( $card->image)) ?  $card->image : ''}} </td>--}}
                                        <td>{{ !empty($vehiclemaintenance->vehicle_model . ' ' . $vehiclemaintenance->year ) ? $vehiclemaintenance->vehicle_model  . ' ' . $vehiclemaintenance->year: ''}}</td>
                                        <td></td>

                                        <td></td>


                                    </tr>

                            @else
                                <tr id="categories-list">
                                    <td colspan="9">
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                                &times;
                                            </button>
                                            No Fleet to display, please start by adding a new Fleet..
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </table>


                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-body" align="center">
                   
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



            //Post perk form to server using ajax (edit)
            $('#edit_vehicle').on('click', function() {
                var strUrl = '/vehicle_management/edit_vehicleDetails/' + vehicleID;
                var formName = 'edit-vehicledetails-form';
                var modalID = 'edit-vehicle-modal';
                var submitBtnID = 'edit_vehicle';
                var redirectUrl = '/vehicle_management/viewdetails/{{ $vehiclemaintenances->id }}}';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The Vehicle details have been updated successfully!';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            var vehicleID;
            $('#edit-vehicle-modal').on('show.bs.modal', function (e) {
                var btnEdit = $(e.relatedTarget);
                vehicleID = btnEdit.data('id');
                var vehicle_make = btnEdit.data('vehicle_make');
                var vehicle_model = btnEdit.data('vehicle_model');
                var vehicle_type = btnEdit.data('vehicle_type');
                var year = btnEdit.data('year');
                var vehicle_registration = btnEdit.data('vehicle_registration');
                var chassis_number = btnEdit.data('chassis_number');
                var engine_number = btnEdit.data('engine_number');
                var vehicle_color = btnEdit.data('vehicle_color');
                var odometer_reading = btnEdit.data('odometer_reading');
                var hours_reading = btnEdit.data('hours_reading');
                var size_of_fuel_tank = btnEdit.data('size_of_fuel_tank');
                var cell_number = btnEdit.data('cell_number');
                var tracking_umber = btnEdit.data('tracking_umber');
                var extras = btnEdit.data('extras');
                var image = btnEdit.data('image');
                var registration_papers = btnEdit.data('registration_papers');
                var modal = $(this);
                modal.find('#vehicle_make').val(vehicle_make);
                modal.find('#vehicle_model').val(vehicle_model);
                modal.find('#vehicle_type').val(vehicle_type);
                modal.find('#year').val(year);
                modal.find('#vehicle_registration').val(vehicle_registration);
                modal.find('#chassis_number').val(chassis_number);
                modal.find('#engine_number').val(engine_number);
                modal.find('#vehicle_color').val(vehicle_color);
                modal.find('#odometer_reading').val(odometer_reading);
                modal.find('#hours_reading').val(hours_reading);
                modal.find('#size_of_fuel_tank').val(size_of_fuel_tank);
                modal.find('#cell_number').val(cell_number);
                modal.find('#tracking_umber').val(tracking_umber);
                modal.find('#extras').val(extras);
                modal.find('#image').val(image);
                modal.find('#registration_papers').val(registration_papers);

            });


        });
    </script>
@endsection
