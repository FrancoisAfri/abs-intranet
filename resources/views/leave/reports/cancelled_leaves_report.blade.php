@extends('layouts.main_layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Cancelled Leave Applications Report</h3>
                </div>
                <!-- /.box-header -->
                <form class="form-horizontal" method="POST" action="/leave/reports/cancelled-leaves/print" target="_blank">
                    <input type="hidden" name="hr_person_id" value="{{ $employeeID }}">
                    <input type="hidden" name="leave_types_id" value="{{ $leaveTypeID }}">
                    <input type="hidden" name="date_from" value="{{ $dateFrom }}">
                    <input type="hidden" name="date_to" value="{{ $dateTo }}">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <!-- Collapsible section containing the amortization schedule -->
                        <div class="box-group" id="accordion">
                            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                            <table class="table table-striped">
                                <tr>
                                    <th class="text-center" width="5px">#</th>
                                    <th>Employee Number</th>
                                    <th>Employee Name</th>
                                    <th>Leave Type</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Cancelled By</th>
                                    <th>Cancellation Reason</th>
                                </tr>
                                @if(count($leaveApplications) > 0)
                                    @foreach($leaveApplications as $leaveApplication)
                                        <td class="text-center" nowrap>{{ $loop->iteration }}</td>
                                        <td nowrap>{{ ($leaveApplication->person) ? $leaveApplication->person->employee_number : '' }}</td>
                                        <td nowrap>{{ ($leaveApplication->person) ? $leaveApplication->person->full_name : '' }}</td>
                                        <td>{{ ($leaveApplication->leavetpe) ? $leaveApplication->leavetpe->name : '' }}</td>
                                        <td nowrap>{{ ($leaveApplication->start_time) ? date('d M Y H:i', $leaveApplication->start_time) : (($leaveApplication->start_date) ? date('d M Y', $leaveApplication->start_date) : '') }}</td>
                                        <td nowrap>{{ ($leaveApplication->end_time) ? date('d M Y H:i', $leaveApplication->end_time) : (($leaveApplication->end_date) ? date('d M Y', $leaveApplication->end_date) : '') }}</td>
                                        <td nowrap>{{ ($leaveApplication->canceller) ? $leaveApplication->canceller->full_name : '' }}</td>
                                        <td>{{ $leaveApplication->cancellation_reason }}</td>
                                    @endforeach
                                @endif
                            </table>
                            <div class="row no-print">
                                <div class="col-xs-12">
                                    <a href="/leave/reports" id="cancel" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</a>
                                    <button type="submit" id="cancel" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print</button>
                                </div>
                            </div>
                            <!-- End amortization /table -->
                        </div>
                        <!-- /. End Collapsible section containing the amortization schedule -->
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('page_script')
    <!--  -->

    <!--  -->
    <script type="text/javascript">
        //
    </script>
@endsection
