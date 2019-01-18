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
                        <table class="table table-striped">
							<tr>
								<th>Employee Number </th>
								<th>Employee Name </th>
								<th>Leave Type</th>
								<th>Balance days(s)</th>
							</tr>
							@if(count($credit) > 0)
								@foreach($credit as $audit)
									<tr>
										<td>{{ !empty($audit->employee_number) ? $audit->employee_number : '' }}</td>
										<td>{{ !empty($audit->first_name) && !empty($audit->surname) ? $audit->first_name.' '.$audit->surname : '' }}</td>
										<td>{{ !empty($audit->leaveType) ? $audit->leaveType : '' }}</td>
										<td>{{ !empty($audit->Balance) ? number_format($audit->Balance/8, 2) : '' }} days(s)</td>
									</tr>
								@endforeach
							@endif
						</table>
						<div class="row no-print">
							<div class="col-xs-12">
								<button type="button" id="cancel" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</button>
								<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i>Print report</button>
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
   <script type="text/javascript">
        $(function () {
            $('#cancel').click(function () {
                location.href = '/leave/reports';
            });
		})
    </script>
@endsection