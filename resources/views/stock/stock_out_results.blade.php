@extends('layouts.main_layout')
@section('page_dependencies')
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                    <h3 class="box-title">Stock History Report</h3>
                </div>
                <div class="box-body">
                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div style="overflow-X:auto;">
                                <form class="form-horizontal" method="POST" action="/stock/stock_history/print">
                                    <input type="hidden" name="category_id"
                                           value="{{!empty($CategoryID) ? $CategoryID : 0}}">
                                    <input type="hidden" name="product_id"
                                           value="{{!empty($productsID) ? $productsID : ''}}">
                                    <input type="hidden" name="action_date"
                                           value="{{!empty($actionDate) ? $actionDate : ''}}">

                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>Product name</th>
                                            <th>Date</th>
                                            <th>Action Performed</th>
                                            <th>Performed By</th>
                                            <th>Allocated to</th>
                                            <th style="text-align: center;">Balance Before</th>
                                            <th style="text-align: center;">Balance After</th>
                                            <th style="text-align: center;">Available Balance</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if (count($stock) > 0)
                                            @foreach ($stock as $stocktake)
                                                <tr>
                                                    <td>{{ (!empty($stocktake->product_name)) ? $stocktake->product_name : ''}} </td>
                                                    <td>{{ (!empty($stocktake->action_date)) ? date(' d M Y', $stocktake->action_date) : ''}} </td>
                                                    <td>{{ (!empty($stocktake->action)) ? $stocktake->action : ''}} </td>
                                                    <td>{{ (!empty($stocktake->name)&& !empty($stocktake->surname)) ? $stocktake->name." ".$stocktake->surname: ''}} </td>
                                                    <td>{{ (!empty($stocktake->allocated_firstname) && !empty($stocktake->allocated_surname)) ? $stocktake->allocated_firstname." ".$stocktake->allocated_surname: $stocktake->fleet_number." ".$stocktake->vehicle_registration }} </td>
                                                    <td style="text-align: center;">{{ (!empty($stocktake->balance_before)) ? $stocktake->balance_before : 0}} </td>
                                                    <td style="text-align: center;">{{ (!empty($stocktake->balance_after)) ? $stocktake->balance_after : 0}} </td>
                                                    <td style="text-align: center;">{{ (!empty($stocktake->avalaible_stock)) ? $stocktake->avalaible_stock : 0}} </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>Product name</th>
                                            <th>Date</th>
                                            <th>Action Performed</th>
                                            <th>Performed By</th>
                                            <th>Allocated to</th>
                                            <th style="text-align: center;">Balance Before</th>
                                            <th style="text-align: center;">Balance After</th>
                                            <th style="text-align: center;">Available Balance</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                    <div class="box-footer">

                                        <div class="row no-print">
                                            <button type="button" id="cancel" class="btn btn-default pull-left"><i
                                                        class="fa fa-arrow-left"></i> Back to Search Page
                                            </button>
                                            <button type="submit" class="btn btn-primary pull-right"><i
                                                        class="fa fa-print"></i> Print report
                                            </button>
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
                        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
                        <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
                        <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
                        <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
                        <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
                        <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
                        <!-- End Bootstrap File input -->
                        <script>
                            function postData(id, data) {
                                if (data == 'actdeac') location.href = "/vehicle_management/vehicles_Act/" + id;
                            }

                            //Cancel button click event
                            document.getElementById("cancel").onclick = function () {
                                location.href = "/stock/reports";
                            };
                            $(function () {
                                $('#example2').DataTable({
                                    "paging": true,
                                    "lengthChange": true,
                                    "searching": true,
                                    "ordering": true,
                                    "info": true,
                                    "autoWidth": true,
                                    dom: 'Bfrtip',
                                    buttons: [
                                        'copy', 'csv', 'excel'
                                    ]
                                });
                            });
                        </script>
@endsection