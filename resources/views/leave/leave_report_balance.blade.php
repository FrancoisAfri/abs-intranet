@extends('layouts.main_layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Leave Report  Balance</h3>
                </div>
                <!-- /.box-header -->
				<form class="form-horizontal" method="POST" action="/leave/bal">
				<input type="hidden" name="userID" value="{{!empty($userID) ? $userID : ''}}">
				<input type="hidden" name="LevTypID" value="{{!empty($LevTypID) ? $LevTypID : ''}}">
               <!-- -->
					{{ csrf_field() }}
                <div class="box-body">
                    <!-- Collapsible section containing the amortization schedule -->
                    <div class="box-group" id="accordion">
                        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                        <div class="panel box box-primary">
                            <div class="box-body">
								<table class="table table-striped">
									<tr>
										<th>Employee Number </th>
										<th>Employee Name </th>
										<th>Leave Type</th>
										<th>Balance</th>
										<!-- <th>Family</th>
										<th>Maternity</th>
										<th>Other/Special</th>
										<th>Sick</th>
										<th>Study</th>
										<th>Unpaid</th> -->
									<!-- 	<th>Annual</th> -->
								<!-- 		
										<th>Balance</th> -->
										<!-- <th>Current Transaction</th> -->
										<!-- <th>Previous Balance</th>
										<th>Previous Balance</th>
										<th>Previous Balance</th> -->
									</tr>
									@if(count($credit) > 0)
										@foreach($credit as $audit)
											<tr>
											<!-- <td>{{ !empty($audit->module_name) ? $audit->module_name : '' }}</td> -->
												<!-- <td>{{ !empty($audit->firstname) && !empty($audit->surname) ? $audit->firstname.' '.$audit->surname : '' }}</td> -->
											<td>{{ !empty($audit->employee_number) ? $audit->employee_number : '' }}</td>
											<td>{{ !empty($audit->first_name) && !empty($audit->surname) ? $audit->first_name.' '.$audit->surname : '' }}</td>
											<td>{{ !empty($audit->leaveType) ? $audit->leaveType : '' }}</td>
												<!-- <td>{{ !empty($audit->action_date) ? date('Y M d : H : i : s', $audit->action_date) : '' }}</td> -->
											<td>{{ !empty($audit->Balance) ? $audit->Balance : '' }}</td>
												<!-- <td>{{ !empty($audit->transcation) ? $audit->transcation : '' }}</td> -->
											</tr>
										@endforeach
									@endif
								</table>
								<div class="row no-print">
									<div class="col-xs-12">
										<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i>Print report</button>
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