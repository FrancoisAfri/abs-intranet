@extends('layouts.main_layout')
@section('page_dependencies')
   
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12 col-md-12">
            <!-- Horizontal Form -->
			<form class="form-horizontal" method="get" action="/education/search">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user pull-right"></i>
                    <h3 class="box-title">Activity Search Results</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                <div class="box-body">
					<div class="box">
					<!-- /.box-header -->
					<div class="box-body">
					  <table id="example2" class="table table-bordered table-hover">
						<thead>
						<tr>
						  <th>Name</th>
						  <th>Status</th>
						  <th>Code</th>
						  <th>Start Date</th>
						  <th>End Date</th>
						  <th>Sponsor</th>
						  <th>Budget</th>
						</tr>
						</thead>
						<tbody>
						@if (count($activities) > 0)
							@foreach($activities as $activity)
							<tr>
							  <td><a href="{{ '/education/activity/'.$activity->id.'/view'}}" >{{ !empty($activity->name) ? $activity->name : '' }}</a></td>
							  <td>{{ !empty($activity->status) ? $status_strings[$activity->status] : '' }}</td>
							  <td>{{ !empty($activity->code) ? $activity->code : '' }}</td>
							  <td>{{ !empty($activity->start_date) ? date('Y M d', $activity->start_date) : '' }}</td>
							  <td>{{ !empty($activity->end_date) ? date('Y M d', $activity->end_date) : '' }}</td>
							  <td>{{ !empty($activity->sponsor) ? $activity->sponsor : '' }}</td>
							  <td>{{ !empty($activity->budget) ? number_format($activity->budget, 2) : '' }}</td>
							</tr>
							@endforeach
						@endif
						</tbody>
						<tfoot>
						<tr>
						  <th>Name</th>
						  <th>Status</th>
						  <th>Code</th>
						  <th>Start Date</th>
						  <th>End Date</th>
						  <th>Sponsor</th>
						  <th>Budget</th>
						</tr>
						</tfoot>
					  </table>
					</div>
					<!-- /.box-body -->
					</div>
				</div>   
                    <!-- /.box-body -->
                    <div class="box-footer">
					  <button id="cancel" class="btn btn-default pull-left"><i class="fa fa-arrow-left"></i> Back</button>
                    </div>
                    <!-- /.box-footer -->
            </div>
			</form>
            <!-- /.box -->
        </div>
        <!-- End new User Form-->
    </div>
    @endsection

    @section('page_script')
	<!-- DataTables -->
<script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- End Bootstrap File input -->

    <script type="text/javascript">
        
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