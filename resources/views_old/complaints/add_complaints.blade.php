@extends('layouts.main_layout')

@section('page_dependencies')
	<!-- bootstrap datepicker -->
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/green.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
    <!-- DataTables -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <form class="form-horizontal" method="POST" action="/complaints/add">
				<input type="hidden" name="contact_email" id="contact_email" value="">
                    {{ csrf_field() }}
                    <div class="box-header with-border">
                        <h3 class="box-title">New Complaints / Compliments</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-ban"></i> Invalid Input Data!</h4>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
						<div class="form-group{{ $errors->has('date_complaint_compliment') ? ' has-error' : '' }}">
							<label for="date_complaint_compliment" class="col-sm-2 control-label">Date</label>
							<div class="col-sm-10">
								<div class="input-group">
									<input type="text" class="form-control" id="date_complaint_compliment" name="date_complaint_compliment" value=""  placeholder="" data-mask>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="office" class="col-sm-2 control-label">Office</label>
							<div class="col-sm-10">
								<div class="input-group">
									<input type="text" class="form-control" id="office" name="office" value="" placeholder="Enter Office">
								</div>
							</div>
						</div>
						<div class="form-group{{ $errors->has('company_id') ? ' has-error' : '' }}">
                            <label for="{{ 'company_id' }}" class="col-sm-2 control-label">Company</label>
                            <div class="col-sm-10">
                                <select id="company_id" name="company_id" class="form-control select2" style="width: 100%;" onchange="contactCompanyDDOnChange(this)">
                                    <option value="">*** Please Select a Company ***</option>
                                    <option value="0">[Individual Clients]</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('contact_person_id') ? ' has-error' : '' }}">
                            <label for="{{ 'contact_person_id' }}" class="col-sm-2 control-label">Traveller</label>
                            <div class="col-sm-10">
                                <select id="contact_person_id" name="contact_person_id" class="form-control select2" style="width: 100%;">
                                    <option value="">*** Please Select a Company First ***</option>
                                </select>
                            </div>
                        </div>
						<div class="form-group">
							<label for="supplier" class="col-sm-2 control-label">Supplier</label>
							<div class="col-sm-10">
								<div class="input-group">
									<input type="text" class="form-control" id="supplier" name="supplier" value="" placeholder="Enter Supplier">
								</div>
							</div>
						</div>
						<div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                            <label for="type" class="col-sm-2 control-label">Type</label>
                            <div class="col-sm-9">
                                <label class="radio-inline rdo-iCheck" style="padding-left: 0px;"><input type="radio" id="rdo_products" name="type" value="1" checked> Complaints</label>
                                <label class="radio-inline rdo-iCheck"><input type="radio" id="rdo_services" name="type" value="2">  Compliments</label>
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('type_complaint_compliment') ? ' has-error' : '' }}">
                            <label for="type_complaint_compliment" class="col-sm-2 control-label">Type Of Complaint/Compliment</label>
                            <div class="col-sm-10">
                                <select id="type_complaint_compliment" name="type_complaint_compliment" class="form-control select2" style="width: 100%;">
                                    <option value="0">*** Please Select Type Of Complaint/Compliment***</option>
									<option value="1">Client</option>
									<option value="2">Supplier</option>
									<option value="3">Internal</option>
                                </select>
                            </div>
                        </div>
						<div class="form-group {{ $errors->has('employee_id') ? ' has-error' : '' }}">
							<label for="employee_id" class="col-sm-2 control-label">Employees</label>
							<div class="col-sm-10">
								<div class="input-group">
									<select class="form-control select2" style="width: 100%;" id="employee_id" name="employee_id">
										<option value="">*** Select an Employee ***</option>
										@foreach($employees as $employee)
										<option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->surname }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="summary_complaint_compliment" class="col-sm-2 control-label">Summary</label>
							<div class="col-sm-10">
								<div class="input-group">
									<textarea class="form-control" rows="3" cols="70" id="summary_complaint_compliment" name="summary_complaint_compliment"
											  placeholder="Enter Summary"></textarea>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="pending_reason" class="col-sm-2 control-label">Pending Reason (if applicable)</label>
							<div class="col-sm-10">
								<div class="input-group">
									<textarea class="form-control" rows="3" cols="70" id="pending_reason" name="pending_reason"
											  placeholder="Enter Pending Reason"></textarea>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="summary_corrective_measure" class="col-sm-2 control-label">Summary of Corrective Measure</label>
							<div class="col-sm-10">
								<div class="input-group">
									<textarea class="form-control" rows="3" cols="70" id="summary_corrective_measure" name="summary_corrective_measure"
											  placeholder="Enter Summary of Corrective Measure"></textarea>
								</div>
							</div>
						</div>
                        <div class="form-group {{ $errors->has('responsible_party') ? ' has-error' : '' }}">
							<label for="responsible_party" class="col-sm-2 control-label">Responsible Party</label>
							<div class="col-sm-10">
								<div class="input-group">
									<select class="form-control select2" style="width: 100%;" id="responsible_party" name="responsible_party">
										<option value="0">*** Select Responsible ***</option>
										<option value="1">Employee</option>
										<option value="2">Supplier</option>
										<option value="3">Client</option>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="error_type" class="col-sm-2 control-label">Error Type</label>
							<div class="col-sm-10">
								<div class="input-group">
									<textarea class="form-control" rows="3" cols="70" id="error_type" name="error_type"
											  placeholder="Enter Error Type"></textarea>
								</div>
							</div>
						</div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right">Save <i class="fa fa-arrow-right"></i></button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
        </div>

        <!-- Include modal -->
        @if(Session('changes_saved'))
            @include('contacts.partials.success_action', ['modal_title' => "Users Access Updated!", 'modal_content' => session('changes_saved')])
        @endif
    </div>
@endsection

@section('page_script')
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- DataTables -->
    <script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
	<!-- bootstrap datepicker -->
	<script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();

            //Tooltip
            $('[data-toggle="tooltip"]').tooltip();
            //Initialize iCheck/iRadio Elements
            $('.rdo-iCheck').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
			$('input').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green',
				increaseArea: '20%' // optional
			});
            //Vertically center modals on page
            function reposition() {
                var modal = $(this),
                    dialog = modal.find('.modal-dialog');
                modal.css('display', 'block');

                // Dividing by two centers the modal exactly, but dividing by three
                // or four works better for larger screens.
                dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 2));
            }
            // Reposition when a modal is shown
            $('.modal').on('show.bs.modal', reposition);
            // Reposition when the window is resized
            $(window).on('resize', function() {
                $('.modal:visible').each(reposition);
            });
			$(document).ready(function () {
                $('#date_complaint_compliment').datepicker({
                        format: 'dd/mm/yyyy',
						endDate: '-0d',
                        autoclose: true,
                        todayHighlight: true
                });
            });
        });
    </script>
@endsection
