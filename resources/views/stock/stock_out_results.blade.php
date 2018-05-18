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
                    <i class="fa fa-truck pull-right"></i>
                    <h3 class="box-title">Stock History Report</h3>
                </div>
	<div class="box-body">
		<div class="box">
			<!-- /.box-header -->
			<div class="box-body">
				<div style="overflow-X:auto;">
				<form class="form-horizontal" method="POST" action="/stock/stock_history/print">
<!--					<input type="hidden" name="category_id" value="{{!empty($CategoryID) ? $CategoryID : 0}}">-->
					<input type="hidden" name="product_id" value="{{!empty($product) ? $product : ''}}">
					<input type="hidden" name="action_date" value="{{!empty($actionDate) ? $actionDate : ''}}">				
					<input type="hidden" name="productID" value="{{!empty($productID) ? $productID : ''}}">				
					<table id="example2" class="table table-bordered table-hover">
						<thead>
						<tr>
							<th>Product name</th>
							<th>Date </th>
							<th>Action Performed</th>
							<th>Performed By</th>
							<th>Available balance Before </th>
							<th>Available balance After </th>
<!--							<th>Purpose</th>
							<th>Destination</th>
							<th>Starting Kms</th>
							<th>Ending Kms</th>
							<th>Kms Travelled</th>-->
						</tr>
						</thead>
						<tbody>
						@if (count($stock) > 0)
							@foreach ($stock as $booking)
								<tr>
									<td>{{ (!empty($booking->product_name)) ? $booking->product_name : ''}} </td>
									<td>{{ (!empty($booking->action_date)) ? date(' d M Y', $booking->action_date) : ''}} </td>
									<td>{{ (!empty($booking->action)) ? $booking->action : ''}} </td>
									<td>{{ (!empty($booking->name)&& !empty($booking->surname)) ? $booking->name." ".$booking->surname: ''}} </td>
									<td>{{ (!empty($booking->apr_firstname)&& !empty($booking->apr_surname)) ? $booking->apr_firstname." ".$booking->apr_surname: ''}} </td>
									<td style="text-align: center">{{ (!empty( $booking->start_mileage_id)) ?  $booking->start_mileage_id : ''}} </td>
								</tr>
							@endforeach
						@endif
						</tbody>
						<tfoot>
						<tr>
							<th>Product name</th>
							<th>Date </th>
							<th>Action Performed</th>
							<th>Performed By</th>
							<th>Available balance Before </th>
							<th>Available balance After </th>
<!--							<th>Purpose</th>
							<th>Destination</th>
							<th>Starting Kms</th>
							<th>Ending Kms</th>
							<th>Kms Travelled</th>-->
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
				location.href = "/stock/reports";
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