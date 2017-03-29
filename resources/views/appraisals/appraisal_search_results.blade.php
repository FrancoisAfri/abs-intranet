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
				<div style="overflow-X:auto;">
				  <table id="example2" class="table table-bordered table-hover">
					<thead>
					<tr>
					  <th>Employee Name</th>
					  <th>January</th>
					  <th>February</th>
					  <th>March</th>
					  <th>May</th>
					  <th>June</th>
					  <th>July</th>
					  <th>August</th>
					  <th>September</th>
					  <th>October</th>
					  <th>November</th>
					  <th>December</th>
					</tr>
					</thead>
					<tbody>
					@if (!empty($scoresArray))
						@foreach($scoresArray as $key => $scoreArray)
						<tr>
						<th>{{$key}}</td>
							
							
						</tr>
						@endforeach
					@endif
					</tbody>
					<tfoot>
					<tr>
					  <th>Employee Name</th>
					  <th>January</th>
					  <th>February</th>
					  <th>March</th>
					  <th>May</th>
					  <th>June</th>
					  <th>July</th>
					  <th>August</th>
					  <th>September</th>
					  <th>October</th>
					  <th>November</th>
					  <th>December</th>
					</tr>
					</tfoot>
				  </table>
				</div>
            </div>
            <!-- /.box-body -->
          </div>
                     </div>   
                    <!-- 
                    <div class="box-footer">
					  <button id="cancel" class="btn btn-default pull-left"><i class="fa fa-arrow-left"></i> Back</button>
                    </div>
                     -->
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
			
			//Cancel button click event
            $('#back_button').click(function () {
                location.href = '/appraisal/search/';
            });
        });
		
    </script>
@endsection