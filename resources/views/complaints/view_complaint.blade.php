@extends('layouts.main_layout')
@section('page_dependencies')
        <!-- bootstrap file input -->
<link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
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
                    <p>Details:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/contacts/complaint">
                    {{ csrf_field() }}
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
                        <div class="form-group">
                            <label for="date_complaint_compliment" class="col-sm-2 control-label">Date</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="date_complaint_compliment" name="date_complaint_compliment" value="{{ !empty($complaint->date_complaint_compliment) ? date('d M Y ', $complaint->date_complaint_compliment) : '' }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="office" class="col-sm-2 control-label">Office</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="office" name="office" value="{{ !empty($complaint->office) ? $complaint->office : '' }}" readonly>
                                </div>
                            </div>
                        </div>
						<div class="form-group">
							<label for="company_id" class="col-sm-2 control-label">Company</label>
							<div class="col-sm-10">
								<div class="input-group">
									<input type="text" class="form-control" id="company_id" name="company_id" value="{{ !empty($complaint->company) ? $complaint->company->name : '' }}" readonly>
								</div>
							</div>
						</div>
                        <div class="form-group">
                            <label for="contact_person_id" class="col-sm-2 control-label">Traveller</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="contact_person_id" name="contact_person_id" value="{{!empty($complaint->client->first_name) && !empty($complaint->client->surname) ? $complaint->client->first_name." ". $complaint->client->surname : ''}}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="type" class="col-sm-2 control-label">Type</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="type" name="type" value="{{ ($complaint->type == 1) ? 'Complaint' : 'Compliment' }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="responsible_party" class="col-sm-2 control-label">Responsible Party</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="responsible_party" name="responsible_party" value="{{ !empty($complaint->type_complaint_compliment) ? $reponsible[$complaint->type_complaint_compliment] : '' }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="employee_id" class="col-sm-2 control-label">Employee</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="employee_id" name="employee_id" value="{{$complaint->employees->first_name." ".$complaint->employees->surname}}" readonly>
                                </div>
                            </div>
                        </div>
						<div class="form-group">
							<label for="summary_complaint_compliment" class="col-sm-2 control-label">Summary</label>
							<div class="col-sm-10">
								<div class="input-group">
									<textarea class="form-control" rows="3" cols="70" id="summary_complaint_compliment" name="summary_complaint_compliment"
									readonly>{{ !empty($complaint->summary_complaint_compliment) ? $complaint->summary_complaint_compliment : '' }}</textarea>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="pending_reason" class="col-sm-2 control-label">Pending Reason</label>
							<div class="col-sm-10">
								<div class="input-group">
									<textarea class="form-control" rows="3" cols="70" id="pending_reason" name="pending_reason"
									readonly>{{ !empty($complaint->pending_reason) ? $complaint->pending_reason : '' }}</textarea>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="summary_corrective_measure" class="col-sm-2 control-label">Summary of Corrective Measure</label>
							<div class="col-sm-10">
								<div class="input-group">
									<textarea class="form-control" rows="3" cols="70" id="summary_corrective_measure" name="summary_corrective_measure"
									readonly>{{ !empty($complaint->summary_corrective_measure) ? $complaint->summary_corrective_measure : '' }}</textarea>
								</div>
							</div>
						</div>
						<div class="form-group{{ $errors->has('type_complaint_compliment') ? ' has-error' : '' }}">
                            <label for="type_complaint_compliment" class="col-sm-2 control-label">Type Of Complaint/Compliment</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="type_complaint_compliment" name="type_complaint_compliment" value="{{ !empty($complaint->type_complaint_compliment) ? $typeComplaints[$complaint->type_complaint_compliment] : '' }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
							<label for="error_type" class="col-sm-2 control-label">Error Type</label>
							<div class="col-sm-10">
								<div class="input-group">
									<textarea class="form-control" rows="3" cols="70" id="error_type" name="error_type"
									readonly>{{ !empty($complaint->error_type) ? $complaint->error_type : '' }}</textarea>
								</div>
							</div>
						</div>
						<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                            <label for="status" class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="status" name="status" value="{{ !empty($complaint->status) ? $statuses[$complaint->status] : '' }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer" style="text-align: center;">
						<button type="button" id="cancel" class="btn btn-default pull-left"><i class="fa fa-arrow-left"></i> Cancel</button>
						@if ($complaint->status == 1)
                        <a href="/complaint/edit/{{ $complaint->id }}" class="btn btn-primary pull-right"><i class="fa fa-pencil-square-o"></i> Edit</a>
						@endif
					</div>
                    <!-- /.box-footer -->
                </form>
            </div>
        </div>
        <!-- End Column -->
    </div>
@endsection
@section('page_script')
            <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <!-- Start Bootstrap File input -->
    <!-- canvas-to-blob.min.js is only needed if you wish to resize images before upload. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/canvas-to-blob.min.js" type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
    <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
    <!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
    <script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
    <!-- End Bootstrap File input -->

    <script type="text/javascript">
        //Cancel button click event
	document.getElementById("cancel").onclick = function () {
		location.href = "/complaints/search";
	};
    </script>
@endsection