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
                    <h3 class="box-title">Quote Report</h3>
                </div>
    <div class="box-body">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <div style="overflow-X:auto;">
                <form class="form-horizontal" method="POST" action="/quote-history/reports/print">
				<input type="hidden" name="contact_person_id" value="{{ !empty($contactpersonID) ? $contactpersonID : ''  }}">
				<input type="hidden" name="action_date" value="{{ !empty($actionDate) ? $actionDate : ''  }}">
				<input type="hidden" name="company_id" value="{{ !empty($CompanyID) ? $CompanyID : ''  }}">
				<input type="hidden" name="division_id" value="{{ !empty($DivisionID) ? $DivisionID : ''  }}">
                                  
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Quote Number</th>
                            <th>Quote title</th>
                            <th>Company</th>
                            <th>Contact person</th>
                            <th>Perform By</th>
                            <th>Date Perfomed</th>
                            <th>Comment</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($quotationsAudits) > 0)
                            @foreach ($quotationsAudits as $quotationsAudit)
                                <tr>
                                    <td>{{!empty($quotationsAudit->quote_number  ) ? $quotationsAudit->quote_number  : ''}}</td>
                                    <td>{{!empty($quotationsAudit->quote_title  ) ? $quotationsAudit->quote_title  : ''}}</td>
                                    <td>{{!empty($quotationsAudit->companyname  ) ? $quotationsAudit->companyname  : ''}}</td>
                                    <td>{{ !empty($quotationsAudit->firstname . ' ' . $quotationsAudit->surname ) ? $quotationsAudit->firstname . ' ' . $quotationsAudit->surname : ''}}</td>
                                    <td>{{ !empty($quotationsAudit->approverfirstname . ' ' . $quotationsAudit->approversurname ) ? $quotationsAudit->approverfirstname . ' ' . $quotationsAudit->approversurname : ''}}</td>
                                    <td>{{ !empty($quotationsAudit->approvaldate ) ? date("d/m/Y",  $quotationsAudit->approvaldate) : ''}}</td>
                                    <td>{{!empty($quotationsAudit->Comment) ? $quotationsAudit->Comment  : ''}}</td>
                                    <td>{{!empty($quotationsAudit->quoteStatus) ? $quoteStatuses[$quotationsAudit->quoteStatus] : ''}} </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>Quote Number</th>
                            <th>Quote title</th>
                            <th>Company</th>
                            <th>Contact person</th>
                            <th>Perform By</th>
                            <th>Date Perfomed</th>
                            <th>Comment</th>
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