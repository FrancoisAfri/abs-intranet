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
                    <h3 class="box-title">Educators Search Results</h3>
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
                  <th>Names</th>
                  <th>ID Number</th>
                  <th>Gender</th>
                  <th>Cell Number</th>
                  <th>Physical Address</th>
                </tr>
                </thead>
                <tbody>
				@if (count($publicsRegs) > 0)
					@foreach($publicsRegs as $publicsReg)
					<tr>
					  <td><a href="{{ '/contacts/public/'.$publicsReg->id.'/edit'}}" >{{ !empty($publicsReg->names) ? $publicsReg->names : '' }}</a></td>
					  <td>{{ !empty($publicsReg->id_number) ? $publicsReg->id_number : '' }}</td>
					  <td>{{ !empty($publicsReg->gender) ? 'Male' : 'Female' }}</td>
					  <td>{{ !empty($publicsReg->cell_number) ? $publicsReg->cell_number : '' }}</td>
					  <td>{{ !empty($publicsReg->phys_address) ? $publicsReg->phys_address : '' }}</td>
					</tr>
					@endforeach
				@endif
                </tbody>
                <tfoot>
                <tr>
                  <th>Names</th>
                  <th>ID Number</th>
                  <th>Gender</th>
                  <th>Cell Number</th>
                  <th>Physical Address</th>
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