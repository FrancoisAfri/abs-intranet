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
                    <h3 class="box-title">Search Results</h3>
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
											<th>Date Requeted</th>
											<th>Po Nunber</th>
											<th>Title</th>
											<th>Created By</th>
											<th>Status</th>
										</tr>
                                    </thead>
                                    <tbody>
                                    @if (count($procurements) > 0)
                                        @foreach ($procurements as $procurement)
                                            <tr id="categories-list">
                                                <td>
                                                    <a href="{{ '/procurement/viewrequest/' . $procurement->id.'/back' }}" id="edit_compan"
														class="btn btn-warning  btn-xs"><i class="fa fa-money"></i> View More</a>
												</td>
												<td>{{ !empty($procurement->date_created) ? date(' d M Y', $procurement->date_created) : '' }}</td>
												<td>{{ !empty($procurement->po_number) ? $procurement->po_number : '' }}</td>
												<td>{{ !empty($procurement->title_name) ? $procurement->title_name : '' }}</td>
												<td>{{ (!empty($procurement->firstname)) ?  $procurement->firstname . ' ' .  $procurement->surname : ''}} </td>
												<td>{{ !empty($procurement->status_name) ? $procurement->status_name : '' }}</td>
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
				location.href = "/procurement/seach_request";
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