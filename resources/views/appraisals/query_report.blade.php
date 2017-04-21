@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Query Report</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
				<div style="overflow-X:auto;">
				<table class="table table-bordered">
					 <tr><th>Query Code</th><th>Voucher Verification Code</th><th>Query Type</th>
					 <th>Query Date</th><th>Consultant Name</th><th>Account No</th><th>Account Name</th>
					 <th>Traveller Name</th><th>Departure Date</th><th>Supplier Name</th>
					 <th>Created By</th><th>Voucher Number</th><th>Invoice Date</th>
					 <th>Order Number</th><th>Invoice Amount</th><th>Query Comments</th>
					 </tr>
                    @if (!empty($querReports))
						@foreach($querReports as $querReport)
						 <tr id="kpis-list">
						  <td>{{!empty($querReport->query_code) ? $querReport->query_code : ''}}</td>
						  <td>{{!empty($querReport->voucher_verification_code) ? $querReport->kpa_name : ''}}</td>
						  <td>{{!empty($querReport->query_type) ? $querReport->query_type : ''}}</td>
						  <td>{{!empty($querReport->query_date) ? date('Y M d', $querReport->query_date) : ''}}</td>
						  <td>{{!empty($querReport->account_no) ? $querReport->source_of_evidence : ''}}</td>
						  <td>{{!empty($querReport->account_name) ? $querReport->account_name : ''}}</td>
						  <td>{{!empty($querReport->traveller_name) ? $querReport->traveller_name : ''}}</td>
						  <td>{{!empty($querReport->departure_date) ? date('Y M d', $querReport->departure_date) : ''}}</td>
						  <td>{{!empty($querReport->supplier_name) ? $querReport->supplier_name : ''}}</td>
						  <td>{{!empty($querReport->supplier_invoice_number) ? $querReport->supplier_invoice_number : ''}}</td>
						  <td>{{!empty($querReport->created_by) ? $querReport->created_by : ''}}</td>
						  <td>{{!empty($querReport->voucher_number) ? $querReport->voucher_number : ''}}</td>
						  <td>{{!empty($querReport->invoice_date) ? date('Y M d', $querReport->invoice_date) : ''}}</td>
						  <td>{{!empty($querReport->order_umber) ? $querReport->order_umber : ''}}</td>
						  <td>{{!empty($querReport->invoice_amount) ? $querReport->invoice_amount : ''}}</td>
						  <td>{{!empty($querReport->comment) ? $querReport->comment : ''}}</td>
						 </tr>
						@endforeach
                    @else
						<tr id="kpis-list">
						<td colspan="5">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            No querReport to display, please start by adding a new querReport.
                        </div>
						</td>
						</tr>
                    @endif
				</table>
                </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
				<button type="button" class="btn btn-default pull-left" id="back_kpis">Back to KPI</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_script')

	<!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
	
    <script>
		document.getElementById("back_kpis").onclick = function () {
		location.href = "/appraisal/{{$emp_id}}/{{$kpis->kpa_id}}/{{$monthyear}}/kpis ";
	};		
    </script>
@endsection