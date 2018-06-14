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
                    <i class="fa fa-file-text pull-right"></i>
                    <h3 class="box-title">Converted into Invoices Report</h3>
                </div>
    <div class="box-body">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <div style="overflow-X:auto;">
                <form class="form-horizontal" method="POST" action="/fleet/reports/fleet_card/print">
                                  
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Quote Number</th>
                            <th>Quote title</th>
                            <th>Client - Company</th>
                            <th>Client - Contact person</th>
                            <th>Quoter name</th>
                            <th>Quote Approved By</th>
                            <th>Date Approved</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($quotationsAudit) > 0)
                            @foreach ($quotationsAudit as $Quote)
                                <tr>
                                    <td>{{!empty($Quote->quote_number  ) ? $Quote->quote_number  : ''}}</td>
                                    <td>{{!empty($Quote->quote_title  ) ? $Quote->quote_title  : ''}}</td>
                                    <td>{{!empty($Quote->companyname  ) ? $Quote->companyname  : ''}}</td>
                                    <td>{{ !empty($Quote->firstname . ' ' . $Quote->surname ) ? $Quote->firstname . ' ' . $Quote->surname : ''}}</td>
                                    <td>{{ !empty($Quote->quotefirstname . ' ' . $Quote->quotesurname ) ? $Quote->quotefirstname . ' ' . $Quote->quotesurname : ''}}</td>
                                    <td>{{ !empty($Quote->approverfirstname . ' ' . $Quote->approversurname ) ? $Quote->approverfirstname . ' ' . $Quote->approversurname : ''}}</td>
                                    <td>{{ !empty($Quote->approvaldate ) ? date("d/m/Y",  $Quote->approvaldate) : ''}}</td>
                                    <td>{{!empty($Quote->Comment  ) ? $Quote->Comment  : ''}}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>Quote Number</th>
                            <th>Quote title</th>
                            <th>Client - Company</th>
                            <th>Client - Contact person</th>
                            <th>Quoter name</th>
                            <th>Quote Approved By</th>
                            <th>Date Approved</th>
                            <th>Status</th>
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
                location.href = "/quote/reports";
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