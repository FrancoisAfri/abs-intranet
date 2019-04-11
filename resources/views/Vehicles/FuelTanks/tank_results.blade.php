@extends('layouts.main_layout')
@section('page_dependencies')
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                    <h3 class="box-title"> Incoming for  {{!empty($tank->tank_name) ? $tank->tank_name : '' }} </h3>
                </div>
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
                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div style="overflow-X:auto;">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th style="width: 5px; text-align: center;"></th>
                                        <th>Transaction Date</th>
                                        <th>Transaction Type</th>
                                        <th>Supplier/Employee</th>
                                        <th>Reading before filling</th>
                                        <th>Reading after filling</th>
                                        <th>Litres</th>
                                        <th>Rate Per Litre</th>
                                        <th>Cost</th>
                                        <th>Litres Available</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (count($Fueltank) > 0)
                                        @foreach ($Fueltank as $booking)
                                            <tr id="categories-list">
                                                <td>
                                                    <a href="/vehicle_management/bookingdetails/"
                                                       id="edit_compan" class="btn btn-default  btn-xs"
                                                       data-id="{{ $booking->id }}">select</a>
                                                </td>
                                               
                                                <td>{{ !empty($booking->topup_date) ? date(' d M Y', $booking->topup_date) : '' }}</td>
                                                <td>{{ (!empty($booking->type)) ?  $topUpStatus[$booking->type] : ''}}</td>
                                                <td>{{ (!empty($booking->Supplier)) ?  $booking->Supplier : ''}}</td>
                                                <td>{{ (!empty($booking->reading_before_filling)) ?  number_format($booking->reading_before_filling, 2) : 0.00}}</td>
                                                 <td>{{ (!empty($booking->reading_after_filling)) ?  number_format($booking->reading_after_filling, 2) : ''}}</td>
                                                <td>{{ (!empty($booking->litres_new)) ?  number_format($booking->litres_new, 2) : ''}}</td>
                                                <td>{{ (!empty($booking->cost_per_litre)) ?  'R' .number_format($booking->cost_per_litre, 2) : ''}}</td>
                                                <td>{{ (!empty($booking->total_cost)) ? 'R' .number_format($booking->total_cost, 2) : '' }}</td>
                                                <td>{{ (!empty($booking->available_litres ))  ?  number_format($booking->reading_before_filling + $booking->litres_new , 2) : ''}}</td>
                                               
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th style="width: 5px; text-align: center;"></th>
                                        <th>Transaction Date</th>
                                        <th>Transaction Type</th>
                                        <th>Supplier/Employee</th>
                                      
                                        <th>Reading before filling</th>
                                        <th>Reading after filling</th>
                                        <th>Litres</th>
                                        <th>Rate Per Litre</th>
                                        <th>Cost</th>
                                        <th>Litres Available</th>
                                    </tr>
                                    </tfoot>
                                </table>
                                <div class="box-footer" style="text-align: center;">
                                    <button type="button" id="cancel" class="btn btn-default pull-left"><i
                                                class="fa fa-arrow-left"></i> Cancel
                                    </button>
                                    <button type="button" id="cat_module" class="btn btn-primary btn-xs"
                                            data-toggle="modal" data-target="#add-topUp-modal"><i
                                                class="fa fa-caret-square-o-up"></i> Top Up
                                    </button>
                                    <button type="button" id="cat_module" class="btn btn-primary btn-xs"
                                            data-toggle="modal" data-target="#add-gauge-modal"><i
                                                class="fa fa-tachometer"></i> Gauge
                                    </button>
                                    <button type="button" id="cat_module" class="btn btn-primary btn-xs"
                                            data-toggle="modal" data-target="#add-private-modal"><i
                                                class="fa fa-tag"></i> Private Usage
                                    </button>


                                </div>
                            </div>
                            @include('Vehicles.FuelTanks.partials.topUp_modal')
                            @include('Vehicles.FuelTanks.partials.gauge_modal')
                            @include('Vehicles.FuelTanks.partials.privateUsage_modal')
                        </div>
                    </div>
                @endsection

                @section('page_script')
                    <!-- bootstrap datepicker -->
                        <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
                        <!-- InputMask -->
                        <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
                        <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
                        <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
                        <!-- DataTables -->
                        <script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
                        <script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
                        <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>

                        <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
                        <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js"
                                type="text/javascript"></script>
                        <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
                        <!-- iCheck -->
                        <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

                        <!-- Ajax dropdown options load -->
                        <script src="/custom_components/js/load_dropdown_options.js"></script>
                        <!-- Ajax form submit -->
                        <script src="/custom_components/js/modal_ajax_submit.js"></script>
                        <!-- End Bootstrap File input -->
                        <script>
                            function postData(id, data) {
                                if (data == 'actdeac') location.href = "/vehicle_management/vehicles_Act/" + id;
                            }

                            //Cancel button click event
                            document.getElementById("cancel").onclick = function () {
                                location.href = "/vehicle_management/create_request";
                            };
                            $(function () {
                                $('#example2').DataTable({
                                    "paging": true,
                                    "lengthChange": true,
                                    "searching": true,
                                    "ordering": true,
                                    "info": true,
                                    "autoWidth": true
                                });
                            });

                            $(function () {
                                $(".select2").select2();
                                $('.hours-field').hide();
                                $('.comp-field').hide();
                                var moduleId;
                                //Tooltip
                                $('[data-toggle="tooltip"]').tooltip();


                                //Vertically center modals on page

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

                            $(document).ready(function () {

                                $('.required_from').datepicker({
                                    format: 'dd/mm/yyyy',
                                    autoclose: true,
                                    todayHighlight: true
                                });

                                $('#document_date').datepicker({
                                    format: 'dd/mm/yyyy',
                                    autoclose: true,
                                    todayHighlight: true
                                });
                                $('#topup_date').datepicker({
                                    format: 'dd/mm/yyyy',
                                    autoclose: true,
                                    todayHighlight: true
                                });
                                $('#documents_date').datepicker({
                                    format: 'dd/mm/yyyy',
                                    autoclose: true,
                                    todayHighlight: true
                                });
                                $('#usage_date').datepicker({
                                    format: 'dd/mm/yyyy',
                                    autoclose: true,
                                    todayHighlight: true
                                });

                            });


                            $(document).ready(function () {
                                $('#litres_new').change(function () {
                                    var litres_new = $('#litres_new').val();
                                    var total_cost = $('#total_cost').val();
                                    var litre_cost = $('#cost_per_litre').val();

                                    if (litre_cost > 0 && litres_new > 0) {
                                        var total_cost = (litres_new * litre_cost).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                                        document.getElementById('total_cost').value = total_cost;
                                    }
                                    else if (litres_new > 0 && total_cost > 0) {
                                        var litre_cost = (total_cost / litres_new).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                                        document.getElementById('cost_per_litre').value = litre_cost;
                                    }
                                });

                                $('#cost_per_litre').change(function () {
                                    var litres_new = $('#litres_new').val();
                                    var total_cost = $('#total_cost').val();
                                    var litre_cost = $('#cost_per_litre').val();
                                    if (litre_cost > 0 && litres_new > 0) {
                                        var total_cost = (litres_new * litre_cost).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                                        document.getElementById('total_cost').value = total_cost;
                                    }
                                    else if (litre_cost > 0 && total_cost > 0) {
                                        var litres_new = (total_cost / litre_cost).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                                        document.getElementById('litres_new').value = litres_new;
                                    }
                                });

                                $('#total_cost').change(function () {
                                    var litres_new = $('#litres_new').val();
                                    var total_cost = $('#total_cost').val();
                                    var litre_cost = $('#cost_per_litre').val();
                                    if (litre_cost > 0 && total_cost) {
                                        var litres_new = (total_cost / litre_cost).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                                        document.getElementById('litres_new').value = litres_new;
                                    }
                                    else if (litres_new > 0 && total_cost) {
                                        var litre_cost = (total_cost / litres_new).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                                        document.getElementById('cost_per_litre').value = litre_cost;
                                    }
                                });

                            });

                            //
                            //save Fuel Tank
                            //Post module form to server using ajax (ADD)
                            $('#add-fueltank').on('click', function () {
                                var strUrl = '/vehicle_management/addfueltank';
                                var modalID = 'add-tank-modal';
                                var objData = {
                                    division_level_1: $('#' + modalID).find('#division_level_1').val(),
                                    division_level_2: $('#' + modalID).find('#division_level_2').val(),
                                    division_level_3: $('#' + modalID).find('#division_level_3').val(),
                                    division_level_4: $('#' + modalID).find('#division_level_4').val(),
                                    division_level_5: $('#' + modalID).find('#division_level_5').val(),
                                    tank_name: $('#' + modalID).find('#tank_name').val(),
                                    tank_location: $('#' + modalID).find('#tank_location').val(),
                                    tank_description: $('#' + modalID).find('#tank_description').val(),
                                    tank_capacity: $('#' + modalID).find('#tank_capacity').val(),
                                    tank_manager: $('#' + modalID).find('#tank_manager').val(),
                                    _token: $('#' + modalID).find('input[name=_token]').val()
                                };
                                var submitBtnID = 'add-fueltank';
                                var redirectUrl = '/vehicle_management/fuel_tank';
                                var successMsgTitle = 'Fuel Tank Added!';
                                var successMsg = 'The Fuel Tank has been added successfully.';
                                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                            });

                            // vehicle Tank Top Up
                            //Post Top Up form to server using ajax (add)
                            $('#add-tanktopUp').on('click', function () {
                                var strUrl = '/vehicle_management/tank_topup';
                                var formName = 'add-topUp-form';
                                var modalID = 'add-topUp-modal';
                                var submitBtnID = 'add-tanktopUp';
                                var redirectUrl = '/vehicle_management/incoming/{{ $ID }}';
                                var successMsgTitle = 'New Record  Added!';
                                var successMsg = 'The Tank Top Up Details has been updated successfully.';
                                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                            });

                            // vehicle Tank Private Usage
                            //Post Private Usageform to server using ajax (add)
                            $('#add_privateUse').on('click', function () {
                                var strUrl = '/vehicle_management/tank_privateuse';
                                var formName = 'add-private-form';
                                var modalID = 'add-private-modal';
                                var submitBtnID = 'add_privateUse';
                                var redirectUrl = '/vehicle_management/tanksearch/{{ $ID }}';
                                var successMsgTitle = 'New Record  Added!';
                                var successMsg = 'The Tank private Usage Details has been updated successfully.';
                                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                            });


                        </script>
@endsection