@extends('layouts.main_layout')
@section('page_dependencies')
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                    <h3 class="box-title">Internal Diesel Log Report</h3>
                </div>
                <div class="box-body">
                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div style="overflow-X:auto;">
                            <form class="form-horizontal" method="POST" action="/fleet/reports/extOil/print">
                                <input type="hidden" name="vehicle_id" value="{{!empty($vehicle_id) ? $vehicle_id : 0}}">
                                <input type="hidden" name="report_type" value="{{!empty($report_type) ? $report_type : ''}}">
                                <input type="hidden" name="vehicle_type" value="{{!empty($vehicle_type) ? $vehicle_type : ''}}">
                                <input type="hidden" name="driver_id" value="{{!empty($driver_id) ? $driver_id : ''}}">
                                <input type="hidden" name="action_date" value="{{!empty($action_date) ? $action_date : ''}}">               
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <td> Date</td> 
					<td> Time</td> 
					<td colspan="3" style="text-align:center;">Driver Details</td> 
					<td colspan="3" style="text-align:center;" > Supplier Details</td> 
					<td colspan="3" style="text-align:center;" >Voucher </td> 
                                    </tr>
                                <tr class="caption">
                                        <td >Date </td> 
					<td >Fuel Consumption Type</td> 
					<td >Previous Odo </td> 
					<td > Current Odo</td> 
					<td > Fuel Consumption</td> 
					<td > Previous Hours</td> 
					<td > Current Hours</td> 
					<td > Fuel Consumption</td> 
					<td > Litres</td> 
					<td >Price Per Litre </td> 
					<td >Amount </td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (count($fuel_tank_topUp) > 0)
                                        @foreach ($fuel_tank_topUp as $externallog)
                                            <tr>
<!--                                              <td>{{ (!empty( $externallog->fleet_number)) ?  $externallog->fleet_number : ''}} </td> 
                                   <td>{{ (!empty( $externallog->Supplier)) ?  $externallog->Supplier : ''}} </td> 
                                                <td> External </td> 
                                                <td>{{ (!empty( $externallog->Odometer_reading)) ?  $externallog->Odometer_reading : 0}}  Km</td> 
                                                <td>{{ (!empty( $externallog->Hoursreading)) ?  $externallog->Hoursreading : 0}} Hrs</td> 
                                                <td style="text-align: center">{{ !empty($externallog->litres) ? number_format($externallog->litres, 2) : 0 }}</td>
                                                <td>{{ (!empty( $externallog->Odometer_reading)) ?  number_format($externallog->Odometer_reading/$externallog->litres, 2) : 0}} </td>
                                                <td>{{ (!empty( $externallog->Hoursreading)) ?  number_format($externallog->Hoursreading/$externallog->litres, 2) : 0}} </td>
                                                <td> R {{ (!empty( $externallog->litres)) ?  number_format($externallog->total_cost/$externallog->litres, 2) : 0}} </td>
                                                <td style="text-align: center"> R {{ !empty($externallog->total_cost) ? number_format($externallog->total_cost, 2) : 0 }}</td>-->
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    
                                <tr class="caption">
                                        <td >Full </td> 
					<td >Fuel Consumption Type</td> 
					<td >Previous Odo </td> 
					<td > Current Odo</td> 
					<td > Fuel Consumption</td> 
					<td > Previous Hours</td> 
					<td > Current Hours</td> 
					<td > Fuel Consumption</td> 
					<td > Litres</td> 
					<td >Price Per Litre </td> 
					<td >Amount </td>
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
                    </div>
                @endsection

                @section('page_script')
                    <!-- DataTables -->
                        <script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
                        <script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
                        <!-- End Bootstrap File input -->
                        <script>
                            function postData(id, data) {
                                if (data == 'actdeac') location.href = "/vehicle_management/vehicles_Act/" + id;
                            }

                            //Cancel button click event
                            document.getElementById("cancel").onclick = function () {
                                location.href = "/vehicle_management/vehicle_reports";
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

                        </script>
@endsection