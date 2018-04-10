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
                    <h3 class="box-title">Vehicle Expired Documments Report</h3>
                </div>
                <div class="box-body">
                    <div class="box">
                        <h3 class="box-title">Expired Vehicle Documents </h3>
                        <!-- /.box-header -->
                        <form class="form-horizontal" method="POST" action="/fleet/reports/expdocs/print">
                        <div class="box-body">
                            <div style="overflow-X:auto;">
                                    <input type="hidden" name="vehicle_id" value="{{!empty($vehicle_id) ? $vehicle_id : 0}}">
                                    <input type="hidden" name="report_type" value="{{!empty($report_type) ? $report_type : ''}}">
                                    <input type="hidden" name="vehicle_type" value="{{!empty($vehicle_type) ? $vehicle_type : ''}}">
                                    <input type="hidden" name="driver_id" value="{{!empty($driver_id) ? $driver_id : ''}}">
                                    <input type="hidden" name="action_date" value="{{!empty($action_date) ? $action_date : ''}}">

                                    <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>		
                                        <th>Fleet Number Type</th>
                                        <th>Fuel Supplier</th>
                                        <th>km Reading</th>
                                        <th>Hour Reading</th>
                                        <th>Litres</th>
                                        <th>Avg Cons (Odo)</th>
                                        <th>Avg Cons (Hrs)</th>
                                        <th>Avg price per Litre </th>
                                        <th>Amount </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (count($externalFuelLog) > 0)
                                        @foreach ($externalFuelLog as $filling)
                                            <tr id="categories-list">
                                               <!--  <td>{{ (!empty( $filling->date)) ?   date(' d M Y', $filling->date) : ''}} </td> -->
<!--                                                <td>{{ (!empty( $filling->fleet_number)) ?  $filling->fleet_number : ''}} </td>
                                                <td>{{ (!empty( $filling->VehicleMake)) ?  $filling->VehicleMake : ''}} </td>
                                                <td>{{ (!empty( $filling->VehicleModel)) ?  $filling->VehicleModel : ''}} </td>
                                                <td>{{ (!empty( $filling->vehicle_registration)) ?  $filling->vehicle_registration : ''}} </td>
                                                <td>{{ (!empty( $filling->company)) ?  $filling->company : ''}} </td>
                                                <td>{{ (!empty( $filling->Department)) ?  $filling->Department : ''}} </td>
                                                <td>{{ (!empty( $filling->exp_date)) ?   date(' d M Y', $filling->exp_date) : ''}} </td>
                                                <td bgcolor="red"> Expired </td>-->

                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Fleet Number Type</th>
                                        <th>Fuel Supplier</th>
                                        <th>km Reading</th>
                                        <th>Hour Reading</th>
                                        <th>Litres</th>
                                        <th>Avg Cons (Odo)</th>
                                        <th>Avg Cons (Hrs)</th>
                                        <th>Avg price per Litre </th>
                                        <th>Amount </th>
                                    </tr>
                                    </tfoot>
                                </table>
                                        <div class="box-footer">
                                    <div class="row no-print">
                                        <button type="button" id="cancel" class="btn btn-default pull-left"><i
                                                    class="fa fa-arrow-left"></i> Back to Search Page
                                        </button>
                                            <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print report</button>
                                    </div>
                                </div>
                            </div>
                          
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>




                <div class="box-body">
                    <div class="box">
                        <h3 class="box-title">Vehicle Licence Report</h3>
                        <!-- /.box-header -->
                        <form class="form-horizontal" method="POST" action="/fleet/reports/expLic/print">
                        <div class="box-body">
                            <div style="overflow-X:auto;">
                                <table id="example" class="table table-bordered table-hover">

                                        <input type="hidden" name="vehicle_id" value="{{!empty($vehicle_id) ? $vehicle_id : 0}}">
                                        <input type="hidden" name="report_type" value="{{!empty($report_type) ? $report_type : ''}}">
                                        <input type="hidden" name="vehicle_type" value="{{!empty($vehicle_type) ? $vehicle_type : ''}}">
                                        <input type="hidden" name="driver_id" value="{{!empty($driver_id) ? $driver_id : ''}}">
                                        <input type="hidden" name="action_date" value="{{!empty($action_date) ? $action_date : ''}}">

                                    <thead>
                                    <tr>
                                        <th>Fleet Number</th>
                                        <th>Make</th>
                                        <th>Model</th>
                                        <th>Registration</th>
                                        <th>Division</th>
                                        <th>Department</th>
                                        <th>Supplier</th>
                                        <th>Captured By</th>
                                        <th>Date Expired</th>
                                        <th style="width: 8px; text-align: center;">Days Remaining</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (count($VehicleLicences) > 0)
                                        @foreach ($VehicleLicences as $filling)
                                            <tr id="categories-list">
                                                <td>{{ (!empty( $filling->fleet_number)) ?  $filling->fleet_number : ''}} </td>
                                                <td>{{ (!empty( $filling->VehicleMake)) ?  $filling->VehicleMake : ''}} </td>
                                                <td>{{ (!empty( $filling->VehicleModel)) ?  $filling->VehicleModel : ''}} </td>
                                                <td>{{ (!empty( $filling->vehicle_registration)) ?  $filling->vehicle_registration : ''}} </td>
                                                <td>{{ (!empty( $filling->company)) ?  $filling->company : ''}} </td>
                                                <td>{{ (!empty( $filling->Department)) ?  $filling->Department : ''}} </td>
                                                <td>{{ (!empty( $filling->supplier)) ?  $filling->supplier : ''}}</td>
                                                <td>{{ (!empty( $filling->captured_by)) ?  $filling->captured_by : ''}} </td>
                                                <td>{{ (!empty( $filling->exp_date)) ?   date(' d M Y', $filling->exp_date) : ''}} </td>
                                                <td bgcolor="red"> Expired </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Fleet Number</th>
                                        <th>Make</th>
                                        <th>Model</th>
                                        <th>Registration</th>
                                        <th>Division</th>
                                        <th>Department</th>
                                        <th>Supplier</th>
                                        <th>Captured By</th>
                                        <th>Date Expired</th>
                                        <th style="width: 8px; text-align: center;">Days Remaining</th>
                                    </tr>
                                    </tfoot>
                                </table>
                                <div class="box-footer">
                                    
                                    <div class="row no-print">
                                        <button type="button" id="canceled" class="btn btn-default pull-left"><i
                                                    class="fa fa-arrow-left"></i> Back to Search Page
                                        </button>
                                            <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print report</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
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
           if (data == 'actdeac') location.href = "/vehicle_management/vehicle_reports";
         }

        document.getElementById("cancel").onclick = function () 
        {
             location.href = "/vehicle_management/vehicle_reports";
        };
        
        document.getElementById("canceled").onclick = function () {
                                location.href = "/vehicle_management/vehicle_reports";
        };


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
            $('#litres').change(function () {
                var litres = $('#litres').val();
                var total_cost = $('#total_cost').val();
                var litre_cost = $('#cost_per_litre').val();

                if (litre_cost > 0 && litres > 0) {
                    var total_cost = (litres * litre_cost).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                    document.getElementById('total_cost').value = total_cost;
                }
                else if (litres > 0 && total_cost > 0) {
                    var litre_cost = (total_cost / litres).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                    document.getElementById('cost_per_litre').value = litre_cost;
                }
            });

            $('#cost_per_litre').change(function () {
                var litres = $('#litres').val();
                var total_cost = $('#total_cost').val();
                var litre_cost = $('#cost_per_litre').val();
                if (litre_cost > 0 && litres > 0) {
                    var total_cost = (litres * litre_cost).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                    document.getElementById('total_cost').value = total_cost;
                }
                else if (litre_cost > 0 && total_cost > 0) {
                    var litres = (total_cost / litre_cost).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                    document.getElementById('litres').value = litres;
                }
            });

            $('#total_cost').change(function () {
                var litres = $('#litres').val();
                var total_cost = $('#total_cost').val();
                var litre_cost = $('#cost_per_litre').val();
                if (litre_cost > 0 && total_cost) {
                    var litres = (total_cost / litre_cost).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                    document.getElementById('litres').value = litres;
                }
                else if (litres > 0 && total_cost) {
                    var litre_cost = (total_cost / litres).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
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