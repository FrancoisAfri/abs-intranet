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
        <!-- New User Form -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-files-o pull-right"></i>
                    <h3 class="box-title">{{$text}}</h3>
                    <p>Update {{$text}} details:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/complaint/update/{{ $complaint->id }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}

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
                        <div class="form-group {{ $errors->has('date_complaint_compliment') ? ' has-error' : '' }}">
                            <label for="date_complaint_compliment" class="col-sm-2 control-label">Date</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="date_complaint_compliment" name="date_complaint_compliment" value="{{ !empty($complaint->date_complaint_compliment) ? date('d M Y ', $complaint->date_complaint_compliment) : '' }}" >
                                </div>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('office') ? ' has-error' : '' }}">
                            <label for="office" class="col-sm-2 control-label">Office</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="office" name="office" value="{{ !empty($complaint->office) ? $complaint->office : '' }}" >
                                </div>
                            </div>
                        </div>
						<div class="form-group {{ $errors->has('company_id') ? ' has-error' : '' }}">
							<label for="company_id" class="col-sm-2 control-label">Company</label>
							<div class="col-sm-10">
								<div class="input-group">
									<select id="company_id" name="company_id" class="form-control select2" style="width: 100%;" onchange="contactCompanyDDOnChange(this, null, parseInt('{{ $complaint->client_id }}'))">
                                    <option value="0">[Individual Clients]</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ ($company->id == $complaint->company_id) ? ' selected' : '' }}>{{ $company->name }}</option>
                                    @endforeach
                                </select>
								</div>
							</div>
						</div>
                        <div class="form-group {{ $errors->has('contact_person_id') ? ' has-error' : '' }}">
                            <label for="contact_person_id" class="col-sm-2 control-label">Traveller</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <select id="contact_person_id" name="contact_person_id" class="form-control select2" style="width: 100%;">
                                    <option value="">*** Please Select a Company First ***</option>
                                </select>
                                </div>
                            </div>
                        </div>
						<div class="form-group">
							<label for="supplier" class="col-sm-2 control-label">Supplier</label>
							<div class="col-sm-10">
								<div class="input-group">
									<input type="text" class="form-control" id="supplier" name="supplier" value="{{ !empty($complaint->supplier) ? $complaint->supplier : '' }}" placeholder="Enter Supplier">
								</div>
							</div>
						</div>
                        <div class="form-group {{ $errors->has('type') ? ' has-error' : '' }}">
                            <label for="type" class="col-sm-2 control-label">Type</label>
                            <div class="col-sm-10">
                                <div class="input-group">
									<label class="radio-inline rdo-iCheck" style="padding-left: 0px;"><input type="radio" id="rdo_complaints" name="type" value="1" {{ ($complaint->type === 1) ? ' checked' : '' }}> Complaints</label>
									<label class="radio-inline rdo-iCheck"><input type="radio" id="rdo_compliments" name="type" value="2" {{ ($complaint->type === 2) ? ' checked' : '' }}>  Compliments</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('type_complaint_compliment') ? ' has-error' : '' }}">
                            <label for="responsible_party" class="col-sm-2 control-label">Responsible Party</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <select id="type_complaint_compliment" name="type_complaint_compliment" class="form-control select2" style="width: 100%;">
                                    <option value="0">*** Please Select Type Of Complaint/Compliment***</option>
									<option value="1" {{ ($complaint->type_complaint_compliment == 1) ? ' selected' : '' }}>Client</option>
									<option value="2" {{ ($complaint->type_complaint_compliment == 2) ? ' selected' : '' }}>Supplier</option>
									<option value="3" {{ ($complaint->type_complaint_compliment == 3) ? ' selected' : '' }}>Internal</option>
                                </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('employee_id') ? ' has-error' : '' }}">
                            <label for="employee_id" class="col-sm-2 control-label">Employee</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <select class="form-control select2" style="width: 100%;" id="employee_id" name="employee_id">
										<option value="">*** Select an Employee ***</option>
										@foreach($employees as $employee)
										<option value="{{ $employee->id }}"{{ ($complaint->employee_id == $employee->id) ? ' selected' : '' }}>{{ $employee->first_name . ' ' . $employee->surname }}</option>
										@endforeach
									</select>
                                </div>
                            </div>
                        </div>
						<div class="form-group {{ $errors->has('summary_complaint_compliment') ? ' has-error' : '' }}">
							<label for="summary_complaint_compliment" class="col-sm-2 control-label">Summary</label>
							<div class="col-sm-10">
								<div class="input-group">
									<textarea class="form-control" rows="3" cols="70" id="summary_complaint_compliment" name="summary_complaint_compliment"
									>{{ !empty($complaint->summary_complaint_compliment) ? $complaint->summary_complaint_compliment : '' }}</textarea>
								</div>
							</div>
						</div>
						<div class="form-group {{ $errors->has('pending_reason') ? ' has-error' : '' }}">
							<label for="pending_reason" class="col-sm-2 control-label">Pending Reason</label>
							<div class="col-sm-10">
								<div class="input-group">
									<textarea class="form-control" rows="3" cols="70" id="pending_reason" name="pending_reason"
									>{{ !empty($complaint->pending_reason) ? $complaint->pending_reason : '' }}</textarea>
								</div>
							</div>
						</div>
						<div class="form-group {{ $errors->has('summary_corrective_measure') ? ' has-error' : '' }}">
							<label for="summary_corrective_measure" class="col-sm-2 control-label">Summary of Corrective Measure</label>
							<div class="col-sm-10">
								<div class="input-group">
									<textarea class="form-control" rows="3" cols="70" id="summary_corrective_measure" name="summary_corrective_measure"
									>{{ !empty($complaint->summary_corrective_measure) ? $complaint->summary_corrective_measure : '' }}</textarea>
								</div>
							</div>
						</div>
						<div class="form-group {{ $errors->has('responsible_party') ? ' has-error' : '' }}">
                            <label for="responsible_party" class="col-sm-2 control-label">Responsible Party</label>
                            <div class="col-sm-10">
                                <div class="input-group">
									<select class="form-control select2" style="width: 100%;" id="responsible_party" name="responsible_party">
										<option value="0">*** Select Responsible ***</option>
										<option value="1"{{ ($complaint->responsible_party == 1) ? ' selected' : '' }}>Employee</option>
										<option value="2"{{ ($complaint->responsible_party == 2) ? ' selected' : '' }}>Supplier</option>
										<option value="3"{{ ($complaint->responsible_party == 3) ? ' selected' : '' }}>Client</option>
									</select>
								</div>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('error_type') ? ' has-error' : '' }}">
							<label for="error_type" class="col-sm-2 control-label">Error Type</label>
							<div class="col-sm-10">
								<div class="input-group">
									<textarea class="form-control" rows="3" cols="70" id="error_type" name="error_type"
									>{{ !empty($complaint->error_type) ? $complaint->error_type : '' }}</textarea>
								</div>
							</div>
						</div>
						<div class="form-group {{ $errors->has('status') ? ' has-error' : '' }}">
                            <label for="status" class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <select id="status" name="status" class="form-control select2" style="width: 100%;">
										<option value="0">*** Please Select A Status***</option>
										<option value="1" {{ ($complaint->status == 1) ? ' selected' : '' }}> Open</option>
										<option value="2" {{ ($complaint->status == 2) ? ' selected' : '' }}> Closed</option>
									</select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button id="cancel" class="btn btn-default"><i class="fa fa-arrow-left"></i> Cancel</button>
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-upload"></i> Update</button>
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

    <script type="text/javascript">
        //Cancel button click event
        document.getElementById("cancel").onclick = function () {
            location.href = "/contacts";
        };

        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
			//select the contact person
            $('#company_id').trigger('change');

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