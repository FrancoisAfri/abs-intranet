@extends('layouts.main_layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Leave History Report</h3>
                </div>
                <!-- /.box-header -->
				<form class="form-horizontal" method="POST" action="/leave/print">
                 <input type="hidden" name="actionDate" value="{{!empty($actionDate) ? $actionDate : ''}}">
                 <input type="hidden" name="userID" value="{{!empty($userID) ? $userID : ''}}">
                 <input type="hidden" name="report" value="{{!empty($report) ? $report : ''}}">
                 <input type="hidden" name="action" value="{{!empty($action) ? $action : ''}}">
					{{ csrf_field() }}
                <div class="box-body">
                    <!-- Collapsible section containing the amortization schedule -->
                    <div class="box-group" id="accordion">
                        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                       <table class="table table-striped">
							<tr>
								<th>Employee Number </th>
								<th>Names</th>
								<th>Action</th>
								<th>Action Date</th>
								<th>Leave Type</th>
								<th>Previous Balance</th>
								<th>Transaction</th>
								<th>Current Balance</th>
								<th>Added By</th>
							</tr>
							@if(count($historyAudit) > 0)
								@foreach($historyAudit as $audit)
									<tr>
									   <td>{{ !empty($audit->employee_number) ? $audit->employee_number : '' }}</td>
										<td>{{ !empty($audit->firstname) && !empty($audit->surname) ? $audit->firstname.' '.$audit->surname : '' }}</td>
										<td>{{ !empty($audit->action) ? $audit->action : '' }}</td>
										<td>{{ !empty($audit->action_date) ? date('Y M d : H : i : s', $audit->action_date) : '' }}</td>
										<td>{{ !empty($audit->leave_type) ? $audit->leave_type : '' }}</td>
										<td>{{ !empty($audit->previous_balance) ? $audit->previous_balance / 8 : '' }}</td>
										<td>{{ !empty($audit->transcation) ? $audit->transcation / 8 : '' }}</td>
										<td>{{ !empty($audit->current_balance) ? $audit->current_balance / 8 : '' }}</td>
										<td>{{ !empty($audit->added_by_name) ? $audit->added_by_name : '' }}</td>
									</tr>
								@endforeach
							@endif
						</table>
						<div class="row no-print">
							<div class="col-xs-12">
								<button type="submit" id="cancel" class="btn btn-primary pull-right"><i class="fa fa-print"></i>Print report</button>
								 <!-- <button type="button" id="cancel" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Cancel</button> -->
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
