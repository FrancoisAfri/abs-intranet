@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
@endsection
@section('content')
    <div class="row">
        <!-- Search User Form -->
        <div class="col-md-8 col-md-offset-2">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-search pull-right"></i>
                    <h3 class="box-title">Search Criteria</h3>
                    <p>Make Selection:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/loan/search_results">
                    {{ csrf_field() }}
                    <div class="box-body">
						 <div class="form-group">
						  <label for="applicant_type" class="col-sm-3 control-label">Applicant Type</label>
						  <div class="col-sm-9">
						  <select class="form-control" name="applicant_type" id="applicant_type" placeholder="Select Applicant Type" onchange="changeApplicant(this.value)" required>
							<option value="1">Company</option>
							<option value="2">Close corporation</option>
							<option value="3">Sole proprietor</option>
							<option value="4">Individual</option>
							<option value="5">Surety</option>
						  </select>
						  </div>
						</div>
						<div class="form-group" >
							<label for="date_applied" class="col-sm-3 control-label">Date Applied</label>
							<div class="col-sm-9">
								<div class="input-group">
								  <div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								  </div>
								  <input type="text" class="form-control daterangepicker" name="date_applied">
								</div>
							<!-- /.input group -->
							</div>
						</div>
						<div class="form-group">
							<label for="date_approved" class="col-sm-3 control-label">Date Approved</label>
							<div class="col-sm-9">
								<div class="input-group">
								 <div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								  </div>
								  <input type="text" class="form-control daterangepicker" name="date_approved">
								</div>
							</div>
							<!-- /.input group -->
					  </div>
					  <!-- Status -->
						<div class="form-group">
						  <label for="status" class="col-sm-3 control-label">Status</label>
						  <div class="col-sm-9">
						  <select class="form-control" name="status" id="status" placeholder="Select Status">
							<option value="0">Incomplete</option>
							<option value="1">Admin Approval</option>
							<option value="3">Directors Approval</option>
							<option value="4">Approved</option>
							<option value="-1">Admin Rejected</option>
							<option value="-2">Directors Rejected</option>
						  </select>
						  </div>
						</div>
						<!-- Applicant -->
						<div class="form-group">
						  <label for="applicant_name" class="col-sm-3 control-label">Applicant Name</label>
						  <div class="col-sm-9">
						   <input type="text" class="form-control" id="applicant_name" name="applicant_name" placeholder="Please enter applicant name">
						  </div>
						</div><!-- Loan Number -->
						<div class="form-group">
						  <label for="loan_number" class="col-sm-3 control-label">Loan Number</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control" id="loan_number" name="loan_number" placeholder="Please enter loan number">
						  </div>
						</div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Search</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- End new User Form-->
    </div>
@endsection

@section('page_script')
<script src="/bower_components/AdminLTE/plugins/daterangepicker/moment.min.js"></script>
<script src="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.js"></script>
<script>
$(function () {
	//Date Range picker
	$('.daterangepicker').daterangepicker({
		format: 'dd/mm/yyyy',
        endDate: '-1d'
	});
});
</script>
@endsection