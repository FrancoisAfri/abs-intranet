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
                    <h3 class="box-title"></h3>
                    <p></p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/contacts/complaint">
                    {{ csrf_field() }}
                    <div class="box-body">
						<table class="table table-bordered">
                        <tr>
                            <th style="text-align: center;" colspan="2">{{$text}} Details:</th>
                        </tr>
						<tr><td><b>Date</b></td><td>{{ !empty($complaint->date_complaint_compliment) ? date('d M Y ', $complaint->date_complaint_compliment) : '' }}</td></tr>
						<tr><td><b>Office</b></td><td>{{ !empty($complaint->office) ? $complaint->office : '' }}</td></tr>
						<tr><td><b>Company</b></td><td>{{ !empty($complaint->company) ? $complaint->company->name : '' }}</td></tr>
						<tr><td><b>Traveller</b></td><td>{{!empty($complaint->client->first_name) && !empty($complaint->client->surname) ? $complaint->client->first_name." ". $complaint->client->surname : ''}}</td></tr>
						<tr><td><b>Supplier</b></td><td>{{ !empty($complaint->supplier) ? $complaint->supplier : '' }}</td></tr>
						<tr><td><b>Type</b></td><td>{{ ($complaint->type == 1) ? 'Complaint' : 'Compliment' }}</td></tr>
						<tr><td><b>Responsible Party</b></td><td>{{ !empty($complaint->type_complaint_compliment) ? $reponsible[$complaint->type_complaint_compliment] : '' }}</td></tr>
						<tr><td><b>Employee</b></td><td>{{$complaint->employees->first_name." ".$complaint->employees->surname}}</td></tr>
						<tr><td><b>Summary</b></td><td>{{ !empty($complaint->summary_complaint_compliment) ? $complaint->summary_complaint_compliment : '' }}</td></tr>
						<tr><td><b>Pending Reason</b></td><td>{{ !empty($complaint->pending_reason) ? $complaint->pending_reason : '' }}</td></tr>
						<tr><td><b>Summary of Corrective Measure</b></td><td>{{ !empty($complaint->summary_corrective_measure) ? $complaint->summary_corrective_measure : '' }}</td></tr>
						<tr><td><b>Type Of Complaint/Compliment</b></td><td>{{ !empty($complaint->type_complaint_compliment) ? $typeComplaints[$complaint->type_complaint_compliment] : '' }}</td></tr>
						<tr><td><b>Error Type</b></td><td>{{ !empty($complaint->error_type) ? $complaint->error_type : '' }}</td></tr>
						<tr><td><b>Status</b></td><td>{{ !empty($complaint->status) ? $statuses[$complaint->status] : '' }}</td></tr>
						<tr><td><b>Closing Comment</b></td><td>{{ !empty($complaint->closing_comment) ? $complaint->closing_comment : '' }}</td></tr>
						<tr><td><b>Document</b></td><td><a class="btn btn-default btn-flat btn-block pull-right btn-xs"
													   href="{{ Storage::disk('local')->url("complaints/$complaint->document_upload") }}"
													   target="_blank"><i class="fa fa-file-pdf-o"></i> View Document</a></td></tr>
                    </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer" style="text-align: center;">
						<button type="button" id="cancel" class="btn btn-default pull-left"><i class="fa fa-arrow-left"></i> Cancel</button>
						@if ($complaint->status == 1)
							<a href="/complaint/edit/{{ $complaint->id }}" class="btn btn-primary pull-right"><i class="fa fa-pencil-square-o"></i> Edit</a>
						@elseif ($complaint->status == 2 && !empty($userAccess))
							<a href="/complaint/edit/{{ $complaint->id }}" class="btn btn-warning pull-right"><i class="fa fa-pencil-square-o"></i> Edit</a>
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