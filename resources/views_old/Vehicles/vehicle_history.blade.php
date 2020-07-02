@extends('layouts.main_layout')

@section('page_dependencies')
        <!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<!-- iCheck -->
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/green.css"> 
	<!--  -->
	 <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
		<div class="col-sm-12">
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">Vehicle History Fleet Number: {{  $fleet->fleet_number}}</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i
									class="fa fa-minus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i
									class="fa fa-remove"></i></button>
					</div>
				</div>
				<!-- /.box-header -->
				<div class="box-body" style="max-height: 190px; overflow-y: scroll;">
					<table id="example2" class="table table-bordered table-hover">
						<thead>
						<tr>
							<th>User</th>
							<th>Date</th>
							<th>Time</th>
							<th>Comment</th>
						</tr>
						</thead>
						<tbody>
							@if (count($fleet->vehicleHistory) > 0)
								@foreach ($fleet->vehicleHistory as $history)
									<tr>
										<td>{{ (!empty($history->userName->first_name)&& !empty($history->userName->surname)) ? $history->userName->first_name." ".$history->userName->surname: ''}}</td>
										<td>{{ (!empty($history->action_date)) ? date('d M Y', $history->action_date) : ''}} </td>
										<td>{{ (!empty($history->action_date)) ? date('H :s :i', $history->action_date) : ''}} </td>
										<td>{{ (!empty($history->comment)) ? $history->comment : 'No changes to display'}} </td>
									</tr>
								@endforeach
							@endif
						</tbody>
						<tfoot>
							<tr>
								<th>User</th>
								<th>Date</th>
								<th>Time</th>
								<th>Comment</th>
							</tr>
						</tfoot>
					</table>
                </div>
				<div class="box-footer">
                    <button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
					<a href="{{ '/vehicle_management/vehicle_history_print/'.$fleet->id.''}}" class="btn btn-success pull-right" target="_blank">Print History</a>
                </div>
			</div>
		</div>
    </div>
@endsection

@section('page_script')
<!-- Select2 -->
<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>

<!-- bootstrap datepicker -->
<script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>

<!-- Ajax form submit -->
<script src="/custom_components/js/modal_ajax_submit.js"></script>
<!-- iCheck -->
<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>

<script type="text/javascript">

$('#back_button').click(function () {
            location.href = '/vehicle_management/viewdetails/{{$fleet->id}}';
});
</script>
@endsection