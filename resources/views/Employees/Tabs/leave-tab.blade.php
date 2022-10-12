<div class="row">
	<div class="col-md-12 col-md-offset-0">
		<div class="box box-default">
			<div class="box-header with-border">
				<i class="fa fa-barcode pull-right"></i>
				<h3 class="box-title"> Leave Balance </h3>
			</div>
			<div class="box-body" style="max-height: 274px; overflow-y: scroll;">
				<div class="table-responsive">
					<table class="table no-margin">
						<thead>
							<tr>
								<th>Leave Type</th>
								<th style="text-align: right;">Balance</th>
							</tr>
						</thead>
						<tbody>
							@if (!empty($balances))
								@foreach($balances as $balance)
									<tr>
										<td>{{ (!empty($balance->leavetype)) ?  $balance->leavetype : ''}}</td>
										<td style="text-align: right;">{{ (!empty($balance->leave_balance)) ?  $balance->leave_balance / 8: 0}}</td>
									</tr>
								@endforeach
							@else
								<tr>
									<td colspan="2"> No leave balance</td>
								</tr>
							@endif
						</tbody>
					</table>
					<div class="box-footer">
						@if (!empty($surbs))
							<button type="button" id="leave-balance" class="btn btn-primary pull-left"
							data-toggle="modal" data-target="#leave-balance-modal"
									>Subordinates Balances</button>
						@endif
						<button id="Apply" class="btn btn-primary pull-right"><i
									class="fa fa-cloud-download"></i> Apply For Leave
						</button>
					</div>
				</div>
			@if (!empty($surbs))
				@include('Employees.partials.widgets.leave_balance')
			@endif
			</div>
			<div class="box-footer clearfix">
			</div>
		</div>
	</div>
	<div class="col-md-12 col-md-offset-0">
		<div class="box box-default">
			<div class="box-header with-border">
				<i class="fa fa-barcode pull-right"></i>
				<h3 class="box-title"> Applications History</h3>
			</div>
			<div class="box-body" style="max-height: 274px; overflow-y: scroll;">
				<div class="table-responsive">
					<table class="table no-margin">
						<thead>
							<tr>
								<th><i class="material-icons">shop_two</i> Leave Type</th>
								<th><i class="fa fa-calendar-o"></i> Date From</th>
								<th><i class="fa fa-calendar-o"></i> Date To</th>
								<th style="text-align: right;"><i class="fa fa-info-circle"></i> Status</th>
								<th style="text-align: right;"><i class="fa fa-info-circle"></i> Rejection/Cancellation Reason
								</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@if (!empty($application))
								@foreach($application as $app)
									<tr>
										<td style="vertical-align: middle;">{{ (!empty($app->leavetype)) ?  $app->leavetype : ''}}</td>
										<td style="vertical-align: middle;">
											{{ !empty($app->start_date) ? date('d M Y ', $app->start_date) : '' }}
										</td>
										<td style="vertical-align: middle;">{{ !empty($app->end_date) ? date('d M Y ', $app->end_date) : '' }}</td>
										<td style="text-align: right; vertical-align: middle;">
											{{ (!empty($app->status) && $app->status > 0) ? $leaveStatusNames[$app->status]." ".$app->reject_reason  : ''}}
										</td>
										<td style="text-align: right; vertical-align: middle;">
											@if ($app->status == 10)
												{{ !empty($app->cancellation_reason) ? $app->cancellation_reason  : ''}}
											@else
												{{ !empty($app->reject_reason) ? $app->reject_reason  : ''}}
											@endif
										</td>
										<td class="text-right" style="vertical-align: middle;">
											@if(in_array($app->status, [2, 3, 4, 5]))
												<button class="btn btn-xs btn-warning"
														title="Cancel Leave Application" data-toggle="modal"
														data-target="#cancel-leave-application-modal"
														data-leave_application_id="{{ $app->id }}"><i
															class="fa fa-times"></i></button>
											@endif
										</td>
									</tr>
								@endforeach
							@endif
						</tbody>
					</table>
				</div>
			</div>
			<div class="box-footer clearfix">
			</div>
			<!-- Include cancellation reason modal -->
			@include('Employees.partials.cancel_leave_application_modal')
		</div>
	</div>
</div>