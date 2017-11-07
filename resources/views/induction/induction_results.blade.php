@extends('layouts.main_layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Induction Search Results</h3>
                </div>
                <!-- /.box-header -->
				<!--<form class="form-horizontal" method="POST" action="/audits/print">-->
					{{ csrf_field() }}
                <div class="box-body">
                    <!-- Collapsible section containing the amortization schedule -->
                     <table class="table table-striped">
						<tr>
							<th></th>
							<th>Induction Title</th>
							<th>Compamy</th>
							<th>Date Created</th>
							<th>Created By</th>
						</tr>
						@if(count($inductions) > 0)
							@foreach($inductions as $induction)
								<tr>
									<td><button type="button" class="btn btn-warning" id="delete_button" name="command"
										onclick="if(confirm('Are you sure you want to delete this Induction ?')){ deleteRecord({{$induction->id}})} else {return false;}"
										value="Delete"><i class="fa fa-trash"></i> Delete Induction
										</button>
									</td>
									<td><a href="{{ '/induction/' . $induction->id . '/view' }}" class="product-title">{{ !empty($induction->induction_title) ? $induction->induction_title : '' }}</a></td>
									<td>{{ !empty($induction->comp_name) ? $induction->comp_name : '' }}</td>
									<td>{{ !empty($induction->created_at) ? $induction->created_at : '' }}</td>
									<td>{{ !empty($induction->firstname) && !empty($induction->surname) ? $induction->firstname.' '.$induction->surname : '' }}</td>
									
								</tr>
							@endforeach
						<tr>
							<th></th>
							<th>Induction Title</th>
							<th>Compamy</th>
							<th>Date Created</th>
							<th>Created By</th>
						</tr>
						@endif
								</table>
								<div class="row no-print">
									<div class="col-xs-12">
									<button type="button" class="btn btn-default pull-left" id="back_button"><i class="fa fa-arrow-left"></i> Back</button>
									</div>
								</div>
								<!-- End amortization /table -->
						
                    <!-- /. End Collapsible section containing the amortization schedule -->
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page_script')
    <script>
        $(function () {
            //Cancel button click event
            $('#back_button').click(function () {
                location.href = '/induction/search';
            });
        });
		function deleteRecord(induction_id) {
			location.href = "/induction/delete/" + induction_id;
		}
    </script>
@endsection