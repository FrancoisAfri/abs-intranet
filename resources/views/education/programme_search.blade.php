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
                    <h3 class="box-title">Programmes Search Results</h3>
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
                  <th>Budget Expenditure</th>
                  <th>Budget Income</th>
                </tr>
                </thead>
                <tbody>
				@if (count($programmes) > 0)
					@foreach($programmes as $programme)
					<tr>
					  <td><a href="{{ '/education/programme/'.$programme->id.'/view'}}" >{{ !empty($programme->name) ? $programme->name : '' }}</a></td>
            <td>{{ !empty($programme->status) ? $status_strings[$programme->status] : '' }}</td>
					  <td>{{ !empty($programme->code) ? $programme->code : '' }}</td>
					  <td>{{ !empty($programme->start_date) ? date('Y M d', $programme->start_date) : '' }}</td>
					  <td>{{ !empty($programme->end_date) ? date('Y M d', $programme->end_date) : '' }}</td>
					  <td>{{ !empty($programme->budget_expenditure) ? number_format($programme->budget_expenditure, 2) : '' }}</td>
					  <td>{{ !empty($programme->budget_income) ? number_format($programme->budget_income, 2) : '' }}</td>
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
                  <th>Budget Expenditure</th>
                  <th>Budget Income</th>
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