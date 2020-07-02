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
                    <h3 class="box-title">KPi  Score Results for {{ $kpisArray->first_name . " ". $kpisArray->surname }} on KPA: {{$kpisArray->kpa_appraisal->name}}</h3
                </div>
                <!-- /.box-header -->
                <div class="box-body">
					<div style="overflow-X:auto;">
				  <table id="example2" class="table table-bordered table-hover">
					<thead>
					<tr>
					  <th></th>
					  <th>Measurement</th>
					  <th>Source Of Evidence</th>
					  <th>Indicator</th>
					  <th>Weight</th>
					  <th>Weighted Score</th>
					</tr>
					</thead>
					<tbody>
					@if (!empty($kpisArray->kpa_appraisal->kpi_appraisal))
						
						@foreach($kpisArray->kpa_appraisal->kpi_appraisal as $kpis)
							<tr>
								@if (!empty($kpis->upload_type) && ($kpis->upload_type == 3))
									<td><button type="button" id="view_more" onclick="postData({{$kpis->id}}, 'view_more');" class="btn btn-xs">View Details</button></td>
								@else
									<td></td>
								@endif
								<td>{{  $kpis->measurement }}</td>
								<td>{{  $kpis->source_of_evidence }}</td>
								<td>{{  $kpis->indicator }}</td>
								<td>{{  $kpis->weight }} %</td>
								<td>{{  round($kpis->appraisal_result, 2) }} %</td>
							</tr>	
						@endforeach
					@endif
					</tbody>
					<tfoot>
					<tr>
					  <th></th>
					  <th>Measurement</th>
					  <th>Source Of Evidence</th>
					  <th>Indicator</th>
					  <th>Weight</th>
					  <th>Weighted Score</th>
					</tr>
					</tfoot>
				  </table>
				</div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button id="back_to_kpa" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back to KPAs</button>
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
	document.getElementById("back_to_kpa").onclick = function () {
		location.href = "/appraisal/{{$kpisArray->id}}/{{$monthyear}}/kpas ";
	};
	function postData(id, data)
	{
		if (data == 'view_more')
	location.href = "/appraisal/kpi_view_more/{{$emp_id}}/{{$monthyear}}/" + id;
	}
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