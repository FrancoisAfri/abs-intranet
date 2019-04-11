@extends('layouts.main_layout')
@section('page_dependencies')
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
    <!-- Include Date Range Picker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap file input -->

    <!--Time Charger-->
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                    <h3 class="box-title">Fuel Search Resutls </h3>
                </div>
                <div class="box-body">
                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div style="overflow-X:auto;">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Transaction Date</th>
                                        <th>Transaction Type</th>
                                        <th>Fleet No.</th>
                                        <th>Reg. No.</th>
                                        <th>Supplier/Employee</th>
                                        <th>Reading before filling</th>
                                        <th>Reading after filling</th>
                                        <th> Litres</th>
                                        <th>Rate Per Litre</th>
                                        <th>Cost</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (count($tankResults) > 0)
                                        @foreach ($tankResults as $filling)
                                            <tr id="categories-list">
                                                <td>{{ (!empty( $filling->date)) ?   date(' d M Y', $filling->date) : ''}} </td>
                                                <td>{{ (!empty( $filling->type)) ?  $status[$filling->type] : ''}} </td>
                                                <td>{{ (!empty( $filling->fleet_number)) ?  $filling->fleet_number : ''}} </td>
                                                <td>{{ (!empty( $filling->vehicle_registration)) ?  $filling->vehicle_registration : ''}} </td>
                                                <td>{{ (!empty( $filling->Supplier)) ?  $filling->Supplier : ''}} </td>
                                                <td>{{ (!empty($filling->reading_before_filling)) ?  $filling->reading_before_filling : ''}}</td>
                                                <td>{{ (!empty($filling->current_fuel_litres)) ?  number_format($filling->current_fuel_litres, 2) : ''}}</td>
                                                <td>{{ (!empty( $filling->litres_new)) ?  number_format($filling->litres_new, 2) : ''}} </td>
                                                <td>{{ (!empty( $filling->cost_per_litre)) ? 'R'.number_format($filling->cost_per_litre, 2) : ''}} </td>
                                                <td>{{ (!empty( $filling->total_cost)) ? 'R'.number_format($filling->total_cost, 2) : ''}} </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Transaction Date</th>
                                        <th>Transaction Type</th>
                                        <th>Fleet No.</th>
                                        <th>Reg. No.</th>
                                        <th>Supplier/Employee</th>
                                        <th>Reading before filling</th>
                                        <th>Reading after filling</th>
                                        <th> Litres</th>
                                        <th>Rate Per Litre</th>
                                        <th>Cost</th>
                                    </tr>
                                    </tfoot>
                                </table>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{--<div class="row">--}}
        {{--<div class="col-md-12 col-md-offset-0">--}}
            {{--<div class="box box-primary">--}}
                {{--<div class="box-header with-border">--}}
                    {{--<i class="fa fa-truck pull-right"></i>--}}
                    {{--<h3 class="box-title"> Driver Details Report </h3>--}}
                {{--</div>--}}
                <div class="box-body">
                    <div class="box">

                            <h3 class="box-title">Station Fuel Search Resutls</h3>

                        <!-- /.box-header -->
                        <div class="box-body">
                            <div style="overflow-X:auto;">
                                <table id="example" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th style="width: 5px; text-align: center;"></th>
                                        <th style="width: 5px; text-align: center;">Date Taken</th>
                                        <th>Vehicle Fleet No. </th>
                                        <th>Vehicle Reg. No.</th>
                                        <th>Odometer Reading</th>
                                        <th>Hours Reading</th>
                                        <th>Captured by</th>
                                        <th>Service Station</th>
                                        <th> Litres</th>
                                        <th>Rate Per Litre</th>
                                        <th>Cost</th>
                                        <th style="width: 8px; text-align: center;">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (count($stationResukts) > 0)
                                        @foreach ($stationResukts as $filling)
                                            <tr id="categories-list">
                                                <td nowrap>
                                                    <button details="button" id="edit_compan"
                                                            class="btn btn-warning  btn-xs"
                                                            data-toggle="modal" data-target="#edit-fuelRecords-modal"
                                                            data-id="{{ $filling->id }}"><i
                                                                class="fa fa-pencil-square-o"></i> Edit
                                                    </button>
                                                </td>
                                                <td>{{ (!empty( $filling->date)) ?   date(' d M Y', $filling->date) : ''}} </td>
                                                <td>{{ (!empty( $filling->fleet_number)) ?  $filling->fleet_number : ''}} </td>
                                                <td>{{ (!empty( $filling->vehicle_registration)) ?  $filling->vehicle_registration : ''}} </td>
                                                <td>{{ (!empty( $filling->odometer_reading)) ?  $filling->odometer_reading : ''}} </td>
                                                <td>{{ (!empty( $filling->Hoursreading)) ?  $filling->Hoursreading : ''}} </td>
                                                <td>{{ (!empty( $filling->firstname . ' ' . $filling->surname )) ?  $filling->firstname . ' ' . $filling->surname  : ''}} </td>
                                                <td>{{ (!empty( $filling->Staion)) ?  $filling->Staion : ''}} </td>
                                                <td>{{ (!empty( $filling->litres_new)) ?  number_format($filling->litres_new, 2) : ''}} </td>
                                                <td>{{ (!empty( $filling->cost_per_litre)) ? 'R'.number_format($filling->cost_per_litre, 2) : ''}} </td>
                                                <td>{{ (!empty( $filling->total_cost)) ? 'R'.number_format($filling->total_cost, 2) : ''}} </td>
                                                <td>{{ (!empty( $filling->iStatus)) ?  $booking[$filling->iStatus] : ''}} </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th style="width: 5px; text-align: center;"></th>
                                        <th style="width: 5px; text-align: center;">Date Taken</th>
                                        <th>Vehicle Fleet No. </th>
                                        <th>Vehicle Reg. No.</th>
                                        <th>Odometer Reading</th>
                                        <th>Hours Reading</th>
                                        <th>Captured by</th>
                                        <th>Service Station</th>
                                        <th> Litres</th>
                                        <th>Rate Per Litre</th>
                                        <th>Cost</th>
                                        <th style="width: 5px; text-align: center;">Status</th>
                                    </tr>
                                    </tfoot>
                                </table>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
      @include('Vehicles.FuelTanks.partials.edit_vehicleFuelRecords_modal')
        </div>
    {{--</div>--}}
@endsection
@section('page_script')
    <!-- DataTables -->
    <script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- bootstrap datepicker -->
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>

    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js"
            type="text/javascript"></script>
    <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->

    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>

    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <!-- End Bootstrap File input -->
    <script>


        //Cancel button click event

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
            $('#example').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true
            });
        });

        function postData(id, data) {
            if (data == 'actdeac') location.href = "/vehicle_management/policy_act/" + id;

        }

        $('#back_button').click(function () {
            location.href = '/vehicle_management/viewdetails/';
        });


        var moduleId;
        //Initialize Select2 Elements
        $(".select2").select2();
        $('.zip-field').hide();
        $('.transaction-field').hide();


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


        $(document).ready(function () {

            $('#date').datepicker({
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


        //
        $('#rdo_transaction, #rdo_Other').on('ifChecked', function () {
            var allType = hideFields();

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
        //
        //
        //   $('#rdo_transaction, #rdo_Other').on('ifChecked', function () {
        //     var allType = hideFields();

        // });

        // function hideFields() {
        //     var allType = $("input[name='transactions']:checked").val();
        //     if (allType == 1) {
        //         $('.transaction-field').hide();
        //         $('.Tanks-field').show();
        //     }
        //     else if (allType == 2) {
        //         $('.transaction-field').show();
        //         $('.Tanks-field').hide();
        //     }
        //     return allType;
        // }


        //Post perk form to server using ajax (add)
        $('#add_vehiclefuellog').on('click', function () {
            var strUrl = '/vehicle_management/addvehiclefuellog';
            var formName = 'add-fuel-form';
            var modalID = 'add-fuel-modal';
            var submitBtnID = 'add_vehiclefuellog';
            var redirectUrl = '/vehicle_management/fuel_log/';
            var successMsgTitle = 'New Record Added!';
            var successMsg = 'The Record  has been updated successfully.';
            modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });


        var incidentID;
        $('#edit-incidents-modal').on('show.bs.modal', function (e) {
            var btnEdit = $(e.relatedTarget);
            fineID = btnEdit.data('id');
            var date_of_incident = btnEdit.data('dateofincident');
            var incident_type = btnEdit.data('incident_type');
            var severity = btnEdit.data('severity');
            var reported_by = btnEdit.data('reported_by');
            var odometer_reading = btnEdit.data('odometer_reading');
            var status = btnEdit.data('status');
            var description = btnEdit.data('description');
            var claim_number = btnEdit.data('claim_number');
            var Cost = btnEdit.data('cost');
            var documents = btnEdit.data('documents');
            var documents1 = btnEdit.data('documents1');
            var valueID = btnEdit.data('valueID');
            var name = btnEdit.data('name');
            var modal = $(this);
            modal.find('#date_of_incident').val(date_of_incident);
            modal.find('#name').val(name);
            modal.find('#incident_type').val(incident_type);
            modal.find('#severity').val(severity);
            modal.find('#reported_by').val(reported_by);
            modal.find('#odometer_reading').val(odometer_reading);
            modal.find('#status').val(status);
            modal.find('#description').val(description);
            modal.find('#claim_number').val(claim_number);
            modal.find('#Cost').val(Cost);
            modal.find('#documents').val(documents);
            modal.find('#documents1').val(documents1);
            modal.find('#valueID').val(valueID);
        });

        $('#edit_vehicleincidents').on('click', function () {
            var strUrl = '/vehicle_management/edit_vehicleincidents/' + incidentID;
            var formName = 'edit-incidents-form';
            var modalID = 'edit-incidents-modal';
            var submitBtnID = 'edit_fines';
            var redirectUrl = '/vehicle_management/incidents/';
            var successMsgTitle = 'New Record Added!';
            var successMsg = 'The Record  has been updated successfully.';
            var Method = 'PATCH'
            modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
        });





    </script>
@endsection