@extends('layouts.main_layout')
@section('page_dependencies')
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12 col-md-offset-0">
            <!-- Horizontal Form -->
			<form class="form-horizontal">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user pull-right"></i>
                    <h3 class="box-title">Search Results</h3>
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
                  <th>#</th>
                  <th>Date</th>
				  <th>Office</th>
                  <th>Company</th>
                  <th>Traveller</th>
                  <th>Supplier</th>
                  <th>Type</th>
                  <th>Employee</th>
                  <th>Summary</th>
                </tr>
                </thead>
                <tbody>
				@if (count($complaints) > 0)
					@foreach($complaints as $complaint)
					<tr>
					  <td><a href="{{ '/complaints/view/'.$complaint->id}}" >View More</a></td>
					  <td>{{ !empty($complaint->date_complaint_compliment) ? date('d M Y ', $complaint->date_complaint_compliment) : '' }}</td>
					  <td>{{ !empty($complaint->office) ? $complaint->office : '' }}</td>
					  <td>{{ !empty($complaint->company) ? $complaint->company->name : '' }}</td>
					  <td>{{!empty($complaint->client->first_name) && !empty($complaint->client->surname) ? $complaint->client->first_name." ". $complaint->client->surname : ''}}</td>
					  <td>{{!empty($complaint->supplier) ? $complaint->supplier : ''}}</td>
					  <td>{{ ($complaint->type == 1) ? 'Complaint' : 'Compliment' }}</td>
					  <td>{{$complaint->employees->first_name." ".$complaint->employees->surname}}</td>
					  <td>{{ !empty($complaint->summary_complaint_compliment) ? $complaint->summary_complaint_compliment : '' }}</td>
					  </tr>
					@endforeach
				@endif
                </tbody>
                <tfoot>
                <tr>
                  <th>#</th>
                  <th>Date</th>
				  <th>Office</th>
                  <th>Company</th>
                  <th>Traveller</th>
				  <th>Supplier</th>
                  <th>Type</th>
                  <th>Employee</th>
                  <th>Summary</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
                     </div>   
                    <!-- /.box-body -->
                    <div class="box-footer">
						<button type="button" id="cancel" class="btn btn-default pull-left"><i class="fa fa-arrow-left"></i> Cancel</button>
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
	//Cancel button click event
	document.getElementById("cancel").onclick = function () {
		location.href = "/complaints/search";
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