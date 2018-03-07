@extends('layouts.main_layout')
@section('page_dependencies')
   
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12 col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user pull-right"></i>
                    <h3 class="box-title">Programmes Search Results</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
            <!-- /.box-header -->
					<div class="box-body">
						<div style="overflow-X:auto;">
						  <table id="example2" class="table table-bordered table-hover">
							<thead>
							<tr>
							  <th>Employee Name</th>
							  <th>Jan</th>
							  <th>Feb</th>
							  <th>Mar</th>
							  <th>Apr</th>
							  <th>May</th>
							  <th>Jun</th>
							  <th>Jul</th>
							  <th>Aug</th>
							  <th>Sept</th>
							  <th>Oct</th>
							  <th>Nov</th>
							  <th>Dec</th>
							</tr>
							</thead>
							<tbody>
							@if (!empty($scoresArray))
								@foreach($scoresArray as $emp)
								<tr>
									<td>{{ $emp->first_name . ' ' . $emp->surname }}</td>
									@foreach($emp->year_appraisal as $key => $appraisal)
										@if (!empty($appraisal))
											<td><a href="{{ '/appraisal/' . $emp->id . '/' . $key. ' ' .  $year. '/kpas' }}" class="product-title">{{ round($appraisal, 2)}} % </a></td>
										@else
											<td></td>
										@endif
									@endforeach
								</tr>
								@endforeach
							@endif
							</tbody>
							<tfoot>
							<tr>
							  <th>Employee Name</th>
							  <th>Jan</th>
							  <th>Feb</th>
							  <th>Mar</th>
							  <th>Apr</th>
							  <th>May</th>
							  <th>Jun</th>
							  <th>Jul</th>
							  <th>Aug</th>
							  <th>Sept</th>
							  <th>Oct</th>
							  <th>Nov</th>
							  <th>Dec</th>
							</tr>
							</tfoot>
						  </table>
						</div>
					</div>
            <!-- /.box-body --> 
                
            </div>
			<div class="box-footer">
                    <button id="back_to_search" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back to Search</button>
                </div>
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
	document.getElementById("back_to_search").onclick = function () {
		location.href = "/appraisal/search ";
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