@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Loan Search Results</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
				@if(!(count($loans) > 0))
					<div class="callout callout-danger">
						<h4><i class="fa fa-database"></i> No Records found</h4>
						<p>No result matching your search in the database. Please make sure there are loans application in the system and refine your search.</p>
					</div>
				@endif
				<ul class="products-list product-list-in-box">
                <!-- item -->
					@foreach($loans as $loan)
					@if ($loan->loan_id > 0)
						<li class="item">
							<div class="product-info">
								<a href="{{ '/loan/view/'.$loan->loan_id.''}}" class="product-title">{{ $applications[$loan->applicant_type]}}</a>
								<span class="product-description">
								  {{ ($loan->applicant_type == 1) ? $loan->company_name : $loan->ind_name }}
								</span>
								<span class="product-description">
								  {{ ($loan->applicant_type == 1) ? $loan->contact_person : $loan->ind_id_no }}
								</span>
								<span class="product-description">
								  {{ ($loan->applicant_type == 1) ? $loan->vat_number : $loan->marital_status }}
								</span>
								<span class="product-description">
								  {{ ($loan->applicant_type == 1) ? $loan->work_number : $loan->how_married }}
								</span>
								<span class="product-description">
								  {{ ($loan->applicant_type == 1) ? $loan->physical_address : $loan->residential_address }}
								</span>
								<span class="product-description">
								  {{ ($loan->applicant_type == 1) ? $loan->postal_address : $loan->number_dep }}
								</span>
								<span class="product-description">
								@if($loan->status == 4)
								  <button type="button" id="Summary" class="btn btn-primary  btn-sm" onclick="postData({{$loan->id}}, 'Summary');">Summary</button>
								@endif
								</span>
							</div>
						</li>
						@endif
						@endforeach
                       <!-- /.item -->
                    </ul>
                 </div>
				<div class="box-footer">
                    <button id="back_to_contact_search" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back to search</button>
                </div>
                <!-- /.box-footer -->
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection

@section('page_script')
    <script type="text/javascript">
        //Cancel button click event
        document.getElementById("back_to_contact_search").onclick = function () {
            location.href = "/loan/search";
        };
		function postData(id, data)
		{
			if (data == 'Summary')
				location.href = "/loan/" + id + "/summary" ;
		}
    </script>
@endsection