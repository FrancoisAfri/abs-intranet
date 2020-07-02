@extends('layouts.main_layout')
@section('page_dependencies')
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                    <h3 class="box-title">Job Card Search</h3>
                </div>
                <div class="box-body">
                <div class="box-body">
                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div style="overflow-X:auto;">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th style="width: 5px; text-align: center;"></th>
                                        <th>Store</th>
                                        <th>Date Requeted</th>
										<th>Title</th>
										<th>Employee</th>
										<th>On Behalf Of</th>
										<th>Remarks</th>
										<th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (count($stocks) > 0)
                                        @foreach ($stocks as $stock)
                                            <tr id="categories-list">
                                                <td>
                                                    <a href="{{ '/stock/viewrequest/' . $stock->id.'/back' }}" id="edit_compan"
														class="btn btn-warning  btn-xs"><i class="fa fa-money"></i> View More</a>
												</td>
												<td>{{ !empty($stock->store_name) ? $stock->store_name : '' }}</td>
												<td>{{ !empty($stock->date_created) ? date(' d M Y', $stock->date_created) : '' }}</td>
												<td>{{ !empty($stock->title_name) ? $stock->title_name : '' }}</td>
												<td>{{ (!empty($stock->firstname)) ?  $stock->firstname . ' ' .  $stock->surname : ''}} </td>
												<td>{{ (!empty($stock->hp_first_name)) ?  $stock->hp_first_name . ' ' .  $stock->hp_surname : ''}} </td>
												<td>{{ (!empty($stock->request_remarks)) ?  $stock->request_remarks : ''}} </td>
												<td>{{ !empty($stock->status_name) ? $stock->status_name : '' }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th style="width: 5px; text-align: center;"></th>
                                        <th>Store</th>
                                        <th>Date Requeted</th>
										<th>Title</th>
										<th>Employee</th>
										<th>On Behalf Of</th>
										<th>Remarks</th>
										<th>Status</th>
                                    </tr>
                                    </tfoot>
                                </table>
                                <div class="box-footer">
                                    <button type="button" id="cancel" class="btn btn-default pull-left"><i
                                                class="fa fa-arrow-left"></i> Back
                                    </button>
                                </div>
                            </div>
                        </div>
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
		<!-- End Bootstrap File input -->
		<script>
			
			//Cancel button click event
			document.getElementById("cancel").onclick = function () {
				location.href = "/stock/seach_request";
			};
			$(function () {
				$('#example2').DataTable({
					"paging": true,
					"lengthChange": true,
					"searching": true,
					"ordering": true,
					"info": true,
					"autoWidth": true
				});
			});

		</script>
@endsection