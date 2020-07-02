@extends('layouts.main_layout')
@section('page_dependencies')
   
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">

            <!-- HR PEOPLE LIST -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">KPA Score Results for {{ $kpasArray->first_name . " ". $kpasArray->surname }}</h3
                </div>
                <!-- /.box-header -->
                <div class="box-body">
					<div style="overflow-X:auto;">
				  <table id="example2" class="table table-bordered table-hover">
					<thead>
					<tr>
					  <th>Name</th>
					  <th>Weight</th>
					  <th>Weighted Score</th>
					</tr>
					</thead>
					<tbody>
					@if (!empty($kpasArray->kpa_appraisal))
						
						@foreach($kpasArray->kpa_appraisal as $kpas)
							<tr>
								<td><a href="{{'/appraisal/' . $emp_id . '/' . $kpas->id . '/' . $monthyear . '/kpis'}}" class="product-title">{{  $kpas->name }}</a></td>
								<td>{{  $kpas->weight }} %</td>
								<td>{{  round($kpas->appraisal_result, 2) }} %</td>
							</tr>	
						@endforeach
					@endif
					</tbody>
					<tfoot>
					<tr>
					 <th>Name</th>
					  <th>Weight</th>
					  <th>Weighted Score</th>
					</tr>
					</tfoot>
				  </table>
				</div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button id="back_to_appraisal" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back to Employee Score</button>
                </div>
                <!-- /.box-footer -->
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection

@section('page_script')
	<!-- DataTables -->
<script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- End Bootstrap File input -->

    <script type="text/javascript">
	//Cancel button click event
	document.getElementById("back_to_appraisal").onclick = function () {
		location.href = "/appraisal/{{$kpasArray->id}}/viewappraisal ";
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