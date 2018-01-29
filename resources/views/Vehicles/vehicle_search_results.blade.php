@extends('layouts.main_layout')
@section('page_dependencies')

    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">

@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12 col-md-offset-0">
            <!-- Horizontal Form -->
            <!-- <form class="form-horizontal" method="get" action="/leave/approval"> -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                    <h3 class="box-title">Internal Vehicle Management </h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                <div class="box-body">
                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div style="overflow-X:auto;">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th style="width: 5px; text-align: center;">Image</th>
                                        <th>Vehicle Model/Year</th>
                                        <th>Fleet Number</th>
                                        <th>Vehicle Registration</th>
                                        <th>VIN Numberr</th>
                                        <th>Engine Number</th>
                                        <th>Odometer/Hours</th>
                                        <th>Company</th>
                                        <th>Department</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (count($vehiclemaintenance) > 0)
                                        @foreach ($vehiclemaintenance as $card)
                                            <tr id="categories-list">
                                                <td>
                                                    <a href="{{ '/vehicle_management/viewdetails/' . $card->id }}"
                                                       id="edit_compan" class="btn btn-default pull-left"
                                                       data-id="{{ $card->id }}">View</a>

                                                    <div id="my_div" class="hidden">
                                                        <a href="{{ '/vehicle_management/viewImage/' . $card->id }}"
                                                        >image</a>
                                                    </div>
                                                    <div id="my_div" class="hidden">
                                                        <a href="{{ '/vehicle_management/keys/' . $card->id }}"
                                                        >Keys </a>
                                                    </div>
                                                    <div id="my_div" class="hidden">
                                                        <a href="{{ '/vehicle_management/permits_licences/' . $card->id }}">Permits/Licences </a>
                                                    </div>
                                                    <div id="my_div" class="hidden">
                                                        <a href="{{ '/vehicle_management/document/' . $card->id }}"
                                                        >document </a>
                                                    </div>
                                                    <div id="my_div" class="hidden">
                                                        <a href="{{ '/vehicle_management/contracts/' . $card->id }}"
                                                        ">Contracts </a>
                                                    </div>
                                                    <div id="my_div" class="hidden">
                                                        <a href="{{ '/vehicle_management/notes /' . $card->id }}"
                                                        >Notes </a>
                                                    </div>
                                                    <div id="my_div" class="hidden">
                                                        <a href="{{ '/vehicle_management/reminders  /' . $card->id }}"
                                                        >Reminders </a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="product-img">
                                                        <img src="{{ (!empty($card->image)) ? Storage::disk('local')->url("Vehicle/images/$card->image") : 'http://placehold.it/60x50' }}"
                                                             alt="Product Image" width="50" height="50">
                                                    </div>
                                                </td>

                                                <td>{{ !empty($card->vehicle_model . ' ' . $card->year ) ? $card->vehicle_model  . ' ' . $card->year: ''}}</td>
                                                <td>{{ !empty($card->fleet_number) ? $card->fleet_number : ''}}</td>
                                                <td>{{ !empty($card->vehicle_registration) ? $card->vehicle_registration : ''}}</td>
                                                <td>{{ !empty($card->chassis_number) ? $card->chassis_number : ''}}</td>
                                                <td>{{ !empty($card->engine_number) ? $card->engine_number : ''}}</td>
                                                <td>{{ !empty($card->odometer_reading . ' ' . $card->hours_reading ) ? $card->odometer_reading  . ' ' . $card->hours_reading: ''}}</td>
                                                <td>{{ !empty($card->company) ? $card->company : ''}}</td>
                                                <td>{{ !empty($card->Department) ? $card->Department : ''}}</td>
                                            <!--  <td>
                                            
                                        <button card="button" id="view_ribbons" class="btn {{ (!empty($card->status) && $card->status == 1) ? " btn-danger " : "btn-success " }}
                                                    btn-xs" onclick="postData({{$card->id}}, 'actdeac');"><i class="fa {{ (!empty($card->status) && $card->status == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($card->status) && $card->status == 1) ? "De-Activate" : "Activate"}}</button>
                                                
                                            </td> -->
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td></td>
                                        <th style="width: 5px; text-align: center;">Image</th>
                                        <th>Vehicle Model/Year</th>
                                        <th>Fleet Number</th>
                                        <th>Vehicle Registration</th>
                                        <th>VIN Numberr</th>
                                        <th>Engine Number</th>
                                        <th>Odometer/Hours</th>
                                        <th>Company</th>
                                        <th>Department</th>

                                    </tr>
                                    </tfoot>
                                </table>

                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <button type="button" id="cancel" class="btn btn-default pull-left"><i
                                                class="fa fa-arrow-left"></i> Back
                                    </button>
                                </div>
                                @include('Vehicles.partials.upload_newImage_modal')
                            </div>
                        </div>
                        <!-- End new User Form-->
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
                                location.href = "/vehicle_management/manage_fleet";
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