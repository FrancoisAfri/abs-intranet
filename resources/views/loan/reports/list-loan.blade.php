@extends('layouts.main_layout')
@section('page_dependencies')

    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">

@stop
@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-barcode pull-right"></i>
                    <h3 class="box-title">Loan Reports </h3>
                </div>
                <div class="box-body">
                    <div style="overflow-X:auto;">
                        <div class="form-group">
                            <form class="form-horizontal" method="get" action="{{ route('loan.reports') }}">
                                {{ csrf_field() }}
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <div class="col-sm-4">
                                            <label>Employees</label>
                                            <select class="form-control select2 " style="width: 100%;"
                                                    id="employee_id" name="employee_id" data-select2-id="1" tabindex="-1"
                                                    aria-hidden="true">
                                                <option value="all">** Select an Employee **</option>
                                                @foreach ($employees as  $employee)
                                                    <option value="{{ $employee->id }}">{{ $employee->first_name.' '.$employee->surname}}</option>
                                                @endforeach
                                            </select>
                                        </div>
										<div class="col-sm-4">
                                            <label>Loan Status</label>
                                            <select class="form-control select2 " style="width: 100%;"
                                                    id="status_id" name="status_id" data-select2-id="1" tabindex="-1"
                                                    aria-hidden="true">
                                                <option value="all">** Select a Status **</option>
                                                @foreach (\App\Models\StaffLoan::STATUS_SELECT as  $key => $status)
                                                    <option value="{{ $key }}">{{ $status }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Application Types</label>
                                            <select class="form-control select2 " style="width: 100%;"
                                                    id="type" name="type" data-select2-id="1"
                                                    tabindex="-1" aria-hidden="true">
                                                <option value="0">** Select Type **</option>
                                                    <option value="1">Advance</option>
                                                    <option value="2">Loan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <br>
                                        <button type="submit" class="btn btn-primary pull-left">Submit</button>
                                        <br>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <br>
                        <table class="table table-bordered user_datatable">
                            <thead>
								<tr>
									<th>Employee</th>
									<th>Type</th>
									<th>Date Applied</th>
									<th>Amount</th>
									<th>Repayment Month(s)</th>
									<th nowrap>Notes</th>
									<th nowrap>First Approval</th>
									<th nowrap>First Approval Date</th>
									<th nowrap>Second Approval</th>
									<th nowrap>Second Approval Date</th>
									<th nowrap>Rejection Reason</th>
									<th nowrap>Rejected BY</th>
									<th nowrap>Rejection Date</th>
									<th>Status</th>
								</tr>
                            </thead>
                            <tbody>
								@if (count(array($loans)) > 0)
									@foreach ($loans as  $loan)
										<tr>
											<td>{{ !empty($loan->users->first_name) && !empty($loan->users->surname) ? $loan->users->first_name.' '.$loan->users->surname : '' }}</td>
											<td>{{ ((!empty($loan->type)) && $loan->type == 1)  ?  'Advance' : 'Loan'}} </td>
											<td>{{ !empty($loan->created_at) ? $loan->created_at : '' }}</td>
											<td>{{ !empty($loan->amount) ? 'R ' .number_format($loan->amount, 2) : '' }}</td>
											<td style="width: 10px; text-align: center;">{{ (!empty( $loan->repayment_month)) ?  $loan->repayment_month : ''}} </td>
											<td>{{ !empty($loan->reason) ? $loan->reason : '' }}</td>
											<td>{{!empty($loan->firstUsers->first_name) && !empty($loan->firstUsers->surname) ? $loan->firstUsers->first_name.' '.$loan->firstUsers->surname : '' }}</td>
											<td>{{ !empty($loan->first_approval_date) ? date('d M Y ', $loan->first_approval_date) : '' }}</td>
											<td>{{!empty($loan->secondUsers->first_name) && !empty($loan->secondUsers->surname) ? $loan->secondUsers->first_name.' '.$loan->secondUsers->surname : '' }}</td>
											<td>{{ !empty($loan->second_approval_date) ? date('d M Y ', $loan->second_approval_date) : '' }}</td>
											<td>{{!empty($loan->rejection_reason) ? $loan->rejection_reason : '' }}</td>
											<td>{{!empty($loan->rejectedUsers->first_name) && !empty($loan->rejectedUsers->surname) ? $loan->rejectedUsers->first_name.' '.$loan->rejectedUsers->surname : '' }}</td>
											<td>{{ !empty($loan->rejected_date) ? date('d M Y ', $loan->rejected_date) : '' }}</td>
											<td>{{ (!empty( $loan->status)) ?  $statuses[$loan->status] : ''}} </td>
										</tr>
									@endforeach
								@endif
                            </tbody>
                            <tfoot>
								<tr>
									<th>Employee</th>
									<th>Type</th>
									<th>Date Applied</th>
									<th>Amount</th>
									<th>Repayment Month(s)</th>
									<th nowrap>Notes</th>
									<th nowrap>First Approval</th>
									<th nowrap>First Approval Date</th>
									<th nowrap>Second Approval</th>
									<th nowrap>Second Approval Date</th>
									<th nowrap>Rejection Reason</th>
									<th nowrap>Rejected BY</th>
									<th nowrap>Rejection Date</th>
									<th>Status</th>
								</tr>
                            </tfoot>
                        </table>
                        <!-- /.box-body -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('page_script')
    <!-- DataTables -->
    {{--    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js"') }}"></script>--}}
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('custom_components/js/modal_ajax_submit.js') }}"></script>
    <script src="{{ asset('custom_components/js/deleteAlert.js') }}"></script>

    <script src="{{ asset('bower_components/bootstrap_fileinput/js/fileinput.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>


    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
    {{--    <script src="{{ asset('custom_components/js/dataTable.js') }}"></script>--}}

    <!-- End Bootstrap File input -->
    <script type="text/javascript">


        $(function () {

            $('.user_datatable').DataTable({

                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: true,
                dom: 'Bfrtip',
                buttons: [
                    // 'copy', 'csv', 'excel',
                    {
                        extend: 'print',
                        title: 'Staff Loan Management Reports',
                        exportOptions: {
                            stripHtml: false,
                            columns: ':visible:not(.not-export-col)'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Asset list Report',
                        //download: 'open',
                        exportOptions: {
                            stripHtml: true,
                            columns: ':visible:not(.not-export-col)'
                        },
                        orientation: 'landscape',
                        pageSize: 'LEGAL'
                    },
                    {extend: 'copyHtml5', exportOptions: {columns: ':visible'}},
                    {extend: 'csvHtml5', title: 'CSV', exportOptions: {columns: ':visible'}},
                    // { extend: 'excelHtml5', title: 'Excel', exportOptions: { columns: ':visible' } },
                    {
                        text: 'excel',
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        }
                    },

                ]

            });

        });


    </script>
@stop

