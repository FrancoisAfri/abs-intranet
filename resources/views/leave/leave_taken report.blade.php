@extends('layouts.main_layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Leave Taken Report</h3>
                </div>
                <!-- /.box-header -->
				<!-- <form class="form-horizontal" method="POST" action="/leave/print">
                 <input type="hidden" name="actionDate" value="{{!empty($actionDate) ? $actionDate : ''}}">
                 <input type="hidden" name="userID" value="{{!empty($userID) ? $userID : ''}}">
                 <input type="hidden" name="report" value="{{!empty($report) ? $report : ''}}">
                 <input type="hidden" name="action" value="{{!empty($action) ? $action : ''}}"> -->
					{{ csrf_field() }}
                <div class="box-body">
                    <!-- Collapsible section containing the amortization schedule -->
                    <div class="box-group" id="accordion">
                        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                        <div class="panel box box-primary">
                            <div class="box-body">
								<table class="table table-striped">
									<tr>
										<!-- <th>Module Name</th> -->
										<th>Employee Number </th>
										<th>Employee Name </th>
					                    <th>Leave Type</th>
					                    <th>Date taken</th>
					                    <!-- <th>Leave Type</th> -->
										<!-- <th>Previous Balance</th>
										<th>Previous Balance</th>
										<th>Previous Balance</th> -->


									</tr>
									@if(count($custom) > 0)
										@foreach($custom as $audit)
											<tr>
											   <td>{{ !empty($audit->employee_number) ? $audit->employee_number : '' }}</td>
												<td>{{ !empty($audit->first_name) && !empty($audit->surname) ? $audit->first_name.' '.$audit->surname : '' }}</td>
												<td>{{ !empty($audit->leaveTypename) ? $audit->leaveTypename : '' }}</td>
												<td>{{ !empty($audit->created_at) ? $audit->created_at : '' }}</td>
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
                        </div>
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
