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
                    <h3 class="box-title">Internal Vehicle Management </h3>
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
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Expiry Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (count($Cmsnews) > 0)
                                        @foreach ($Cmsnews as $cms)
                                            <tr id="categories-list">
                                                <td>
                                                    <a href="{{ '/cms/cms_newsrankings/' . $cms->id }}"
                                                       id="edit_compan" class="btn btn-default  btn-xs"
                                                       data-id="{{ $cms->id }}">select</a>

                                                </td>
                                                <td>{{ !empty($cms->name) ? $cms->name : ''}}</td>
                                                <td>{{ !empty($cms->description) ? $cms->description : ''}}</td>
                                                <td>{{ !empty($cms->expirydate ) ?  date("y F  Y, g:i a", $cms->expirydate)  : ''}}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th style="width: 5px; text-align: center;"></th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Expiry Date</th>
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

                        </script>
@endsection