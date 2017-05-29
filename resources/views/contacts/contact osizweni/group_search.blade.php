@extends('layouts.main_layout')
@section('page_dependencies')
   
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-8 col-md-offset-2">
            <!-- Horizontal Form -->
			<form class="form-horizontal">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user pull-right"></i>
                    <h3 class="box-title">Group Learner Search Results</h3>
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
                  <th>School</th>
                  <th>School Tel</th>
                  <th>School Email</th>
                  <th>School Address</th>
                  <th>Date Attended</th>
                </tr>
                </thead>
                <tbody>
				@if (count($groups) > 0)
					@foreach($groups as $group)
					<tr>
					  <td><a href="{{ '/education/nsw/'.$group->id.'/edit'}}" >{{ !empty($group->name)  ? $group->name  : '' }}</a></td>
					  <td>{{ !empty($group->phone_number) ? $group->phone_number : '' }}</td>
					  <td>{{ !empty($group->email) ? $group->email : '' }}</td>
					  <td>{{ !empty($group->phys_address) ? $group->phys_address : '' }}</td>
					  <td>{{ !empty($group->date_attended) ? date('Y M d', $group->date_attended) : '' }}</td>
					  </tr>
					@endforeach
				@endif
                </tbody>
                <tfoot>
                <tr>
                  <th>School</th>
                  <th>School Tel</th>
                  <th>School Email</th>
                  <th>School Address</th>
                  <th>Date Attended</th>
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
		location.href = "/contacts/general_search";
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