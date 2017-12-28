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
                    <h3 class="box-title"> Fleet Cards Report </h3>
                </div>
                <div class="box-body">
                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div style="overflow-X:auto;">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th style="width: 5px; text-align: center;"></th>
                                    <th>Vehicle Fleet Number</th>
                                    <th>Holder</th>
                                    <th>Card Number</th>
                                    <th>CVS Number </th>
                                    <th>Issued By</th>
                                    <th>Issued Date</th>
                                    <th>Expiry Date</th>
                                    <th>Active</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (count($fleetcard) > 0)
                                    @foreach ($fleetcard as $booking)
                                        <tr id="categories-list">
                                            <td>
                                                <a href="{{ '/vehicle_management/bookingdetails/' . $booking->id }}"
                                                   id="edit_compan" class="btn btn-default  btn-xs"
                                                   data-id="{{ $booking->id }}">Edit</a>
                                            </td>
                                            <td>{{ !empty($booking->fleet_number ) ? $booking->fleet_number : '' }}</td>
                                            <td>{{ !empty($booking->first_name . '' . $booking->surname ) ? $booking->first_name . '' . $booking->surname : ''}}</td>
                                            <td>{{ !empty($booking->card_number) ? $booking->card_number : ''}}</td>
                                            <td>{{ !empty($booking->cvs_number) ? $booking->cvs_number : ''}}</td>
                                            <td>{{ !empty($booking->Vehicle_Owner) ? $booking->Vehicle_Owner : ''}}</td>
                                            <td>{{ !empty($booking->issued_date ) ? date("y F  Y, g:i a", $booking->issued_date) : ''}}</td>
                                            <td>{{ !empty($booking->expiry_date ) ? date("y F  Y, g:i a",  $booking->expiry_date) : ''}}</td>
                                            <td>{{ !empty($booking->status) ? $status[$booking->status] : ''}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th style="width: 5px; text-align: center;"></th>
                                    <th>Vehicle Fleet Number</th>
                                    <th>Holder</th>
                                    <th>Card Number</th>
                                    <th>CVS Number </th>
                                    <th>Issued By</th>
                                    <th>Issued Date</th>
                                    <th>Expiry Date</th>
                                    <th>Active</th>
                                </tr>
                                </tfoot>
                            </table>
                            <div class="box-footer">
                                <button type="button" id="cancel" class="btn btn-default pull-left"><i
                                            class="fa fa-arrow-left"></i> Back
                                </button>
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
                       

                        //Cancel button click event
                        document.getElementById("cancel").onclick = function () {
                            location.href = "/vehicle_management/fleet_cards";
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