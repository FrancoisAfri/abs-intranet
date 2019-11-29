<div id="leave-balance-modal" class="modal modal-default fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Leave Balance</h4>
			</div>
			<div class="modal-body">
				<table id="example2" class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>Employee Number </th>
							<th>Employee Name </th>
							<th>Leave Type</th>
							<th>Balance days(s)</th>
						</tr>
					</thead>
					<tbody>
					@if(count($surbodinateBalances) > 0)
						@foreach($surbodinateBalances as $surbodinateBalance)
							<tr>
								<td>{{ !empty($surbodinateBalance->hr_employee_number) ? $surbodinateBalance->hr_employee_number : '' }}</td>
								<td>{{ !empty($surbodinateBalance->hr_first_name) && !empty($surbodinateBalance->hr_surname) ? $surbodinateBalance->hr_first_name.' '.$surbodinateBalance->hr_surname : '' }}</td>
								<td>{{ !empty($surbodinateBalance->leave_types) ? $surbodinateBalance->leave_types : '' }}</td>
								<td>{{ !empty($surbodinateBalance->leave_balance) ? number_format($surbodinateBalance->leave_balance/8, 2) : '' }} days(s)</td>
							</tr>
						@endforeach
					@endif
					</tbody>
					<tfoot>
						<tr>
							<th>Employee Number </th>
							<th>Employee Name </th>
							<th>Leave Type</th>
							<th>Balance days(s)</th>
						</tr>
					</tfoot>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Close</button>
			</div>
        </div>
    </div>
</div>