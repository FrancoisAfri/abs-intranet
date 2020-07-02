@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap file input -->
    <!-- iCheck -->
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/green.css">
	    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/buttons.dataTables.min.css">
@endsection
@section('content')
	<div class="row">
		@if($activeModules->whereIn('code_name', 'screening')->first())
			<div class="col-md-12 box box-default ">
				<div class="box-header">
					<h3 class="box-title"><i class="fa fa-hourglass"></i> Dialy Screening</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
					</div>
				</div>
				<!-- /.box-header -->
				<div class="box box-primary" style="max-height: 300px; overflow-y: scroll;">
					<!-- form start -->
					<form class="form-horizontal" method="POST" action="/employee/screening">
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
								<label for="question_1" class="col-sm-3 control-label">Does anyone in your household work in a facility where COVID-19 patients are being treated?</label>
									<div class="col-sm-9">
										<label class="radio-inline rdo-iCheck" style="padding-left: 0px;"><input type="radio" id="rdo_yes" name="question_1" value="1"> Yes</label>
										<label class="radio-inline rdo-iCheck"><input type="radio" id="rdo_no" name="question_1" value="2" checked>  No</label>
									</div>
							</div>
							<div class="form-group">
								<label for="question_2" class="col-sm-3 control-label">In the past 14 days have you come into contact with a COVID-19 positive or suspected positive person?</label>
									<div class="col-sm-9">
										<label class="radio-inline rdo-iCheck" style="padding-left: 0px;"><input type="radio" id="rdo_yes2" name="question_2" value="1"> Yes</label>
										<label class="radio-inline rdo-iCheck"><input type="radio" id="rdo_no2" name="question_2" value="2" checked>  No</label>
									</div>
							</div>
							<div class="form-group">
								<label for="question_3" class="col-sm-3 control-label">In the past 14 days, have you travelled or been in contact with someone who has travelled to an area with local transmission of COVID-19?</label>
									<div class="col-sm-9">
										<label class="radio-inline rdo-iCheck" style="padding-left: 0px;"><input type="radio" id="rdo_yes3" name="question_3" value="1"> Yes</label>
										<label class="radio-inline rdo-iCheck"><input type="radio" id="rdo_no3" name="question_3" value="2" checked>  No</label>
									</div>
							</div>
							<div class="form-group">
								<label for="question_4" class="col-sm-3 control-label">Has you or anyone in your household been tested for COVID-19 in the past 3 days?</label>
									<div class="col-sm-9">
										<label class="radio-inline rdo-iCheck" style="padding-left: 0px;"><input type="radio" id="rdo_yes4" name="question_4" value="1"> Yes</label>
										<label class="radio-inline rdo-iCheck"><input type="radio" id="rdo_no4" name="question_4" value="2" checked>  No</label>
									</div>
							</div>
							<div class="form-group">
								<label for="question_5" class="col-sm-3 control-label">Do you have any illness in the past 14 days?</label>
									<div class="col-sm-9">
										<label class="radio-inline rdo-iCheck" style="padding-left: 0px;"><input type="radio" id="rdo_yes5" name="question_5" value="1"> Yes</label>
										<label class="radio-inline rdo-iCheck"><input type="radio" id="rdo_no5" name="question_5" value="2" checked>  No</label>
									</div>
							</div>
							<div class="form-group">
								<label for="question_6" class="col-sm-3 control-label">Did you have any of these in the last 14 days:
										*	Cough
										*	Sore throat
										*	Fever
										*	Chills
										*	Headache
										*	Shortness of breath
										*	Muscle or joint pain
										*	Sinusitis
										*	Diarrhoea
										</label>
									<div class="col-sm-9">
										<label class="radio-inline rdo-iCheck" style="padding-left: 0px;"><input type="radio" id="rdo_yes6" name="question_6" value="1"> Yes</label>
										<label class="radio-inline rdo-iCheck"><input type="radio" id="rdo_no6" name="question_6" value="2" checked>  No</label>
									</div>
							</div>
							<div class="form-group">
								<label for="question_7" class="col-sm-3 control-label">Have you lost your sense of smell and or appetite</label>
									<div class="col-sm-9">
										<label class="radio-inline rdo-iCheck" style="padding-left: 0px;"><input type="radio" id="rdo_yes7" name="question_7" value="1"> Yes</label>
										<label class="radio-inline rdo-iCheck"><input type="radio" id="rdo_no7" name="question_7" value="2" checked>  No</label>
									</div>
							</div>
							<div class="form-group">
								<label for="question_8" class="col-sm-3 control-label">Have you lost your sense of smell and or appetite</label>
									<div class="col-sm-9">
										<label class="radio-inline rdo-iCheck" style="padding-left: 0px;"><input type="radio" id="rdo_yes8" name="question_8" value="1"> Yes</label>
										<label class="radio-inline rdo-iCheck"><input type="radio" id="rdo_no8" name="question_8" value="2" checked>  No</label>
									</div>
									<input type="hidden" id="employee_number" name="employee_number" value="{{!empty($user->person->employee_number) ? $user->person->employee_number : 0}}"> 
									<input type="hidden" id="screening_done" name="screening_done" value=""> 
							</div>
						</div>
						<!-- /.box-body -->
						<div class="box-footer">
							<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-user-plus"></i> Submit</button>
						</div>
						<!-- /.box-footer -->
					</form>
				</div>
				<div class="box-footer clearfix">
				</div>
				<!-- /.box-body -->
            </div>
		@endif
		@if(Session('success_add'))
			@include('contacts.partials.success_action', ['modal_title' => "Record Added!", 'modal_content' => session('success_add')])
		@endif
		@if($activeModules->whereIn('code_name', 'screening')->first())
			@if($adminAccess)
				<div class="col-md-12 box box-default collapsed-box">
					<div class="box-header">
						<h3 class="box-title"><i class="fa fa-hourglass"></i> Dialy Employees Screening Results</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body" style="max-height: 274px; overflow-y: scroll;">
						<div class="table-responsive">
							<table id="example2" class="table table-bordered table-hover">
								<thead>
									<tr>
										<th>{{$levelsFour->name}}</th>
										<th>Emp No</th>
										<th>Names</th>
										<th>Admin</th>
										<th>Temp</th>
										<th>Screening Status</th>
										<th>Clock In </th>
										<th>Comment</th>
									</tr>
								</thead>
								<tbody>
									@if(!empty($screenings))
										@foreach($screenings as $screening)
											<tr bgcolor="{{!empty($screening->background_temp) ? $screening->background_temp : ''}}"  nowrap>
												<td>{{ !empty($screening->site->name) ? $screening->site->name : '' }}</td>
												<td>{{ !empty($screening->employee_number) ? $screening->employee_number : '' }}</td>
												<td>{{ !empty($screening->employee->first_name) && !empty($screening->employee->surname) ? $screening->employee->first_name.' '.$screening->employee->surname : '' }}</td>
												<td>{{ !empty($screening->administrtor->first_name) && !empty($screening->administrtor->surname) ? $screening->administrtor->first_name.' '.$screening->administrtor->surname : '' }}</td>
												<td>{{ !empty($screening->temperature) ? $screening->temperature : '' }}</td>
												<td>{{ empty($screening->background) ? "Good" : "Not Good" }}</td>
												<td>{{ !empty($screening->clockin_time) ? $screening->clockin_time : '' }}</td>
												@if(empty($screening->comment))
													<td><button id="add-comment" class="btn btn-xs btn-flat"
													data-toggle="modal" data-target="#add-employee-comment-modal"
													data-id="{{ $screening->id }}"><i class="fa fa-stop">
													</i> Add Comment</button></td>
												@else
													<td>{{ !empty($screening->comment) ? $screening->comment : "" }}</td>
												@endif
											</tr>
										@endforeach
									@endif
								</tbody>
								<tfoot>
									<tr>
										<th>{{$levelsFour->name}}</th>
										<th>Emp No</th>
										<th>Names</th>
										<th>Admin</th>
										<th>Temp</th>
										<th>Screening Status</th>
										<th>Clock In </th>
										<th>Comment</th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
					<!-- /.box-body -->
					<div class="box-footer clearfix">
					</div>
					<!-- Include cancellation reason modal -->
					@include('dashboard.partials.add_employee_screening_comment_modal')
				</div>
				<div class="col-md-12 box box-default collapsed-box">
					<div class="box-header">
						<h3 class="box-title"><i class="fa fa-hourglass"></i> Dialy Visitors Screening Results</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body" style="max-height: 274px; overflow-y: scroll;">
						<div class="table-responsive">
							<table id="example3" class="table table-bordered table-hover">
								<thead>
									<tr>
										<th>{{$levelsFour->name}}</th>
										<th>Names</th>
										<th>ID Number</th>
										<th>Cell Number</th>
										<th>Admin</th>
										<th>Temp</th>
										<th>Screening Status</th>
										<th>Clock In </th>
										<th>Comment</th>
									</tr>
								</thead>
								<tbody>
									@if(!empty($clientScreenings))
										@foreach($clientScreenings as $client)
											<tr bgcolor="{{!empty($client->background_temp) ? $client->background_temp : ''}}">
												<td>{{ !empty($client->site->name) ? $client->site->name : '' }}</td>
												<td>{{ !empty($client->client_name) ? $client->client_name : '' }}</td>
												<td>{{ !empty($client->new_id_number) ? $client->new_id_number : '' }}</td>
												<td>{{ !empty($client->cell_number) ? $client->cell_number : '' }}</td>
												<td>{{ !empty($client->administrtor->first_name) && !empty($client->administrtor->surname) ? $client->administrtor->first_name.' '.$client->administrtor->surname : '' }}</td>
												<td>{{ !empty($client->temperature) ? $client->temperature : '' }}</td>
												<td>{{ empty($client->background) ? "Good" : "Not Good" }}</td>
												<td>{{ !empty($client->clockin_time) ? $client->clockin_time : '' }}</td>
												<td>{{ !empty($client->comment) ? $client->comment : '' }}</td>
											</tr>
										@endforeach
									@endif
								</tbody>
								<tfoot>
									<tr>
										<th>{{$levelsFour->name}}</th>
										<th>Names</th>
										<th>ID Number</th>
										<th>Cell Number</th>
										<th>Admin</th>
										<th>Temp</th>
										<th>Screening Status</th>
										<th>Clock In </th>
										<th>Comment</th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
					<!-- /.box-body -->
					<div class="box-footer clearfix">
					</div>
					<!-- Include cancellation reason modal -->
				</div>
			@endif
		@endif
    </div>
@endsection
@section('page_script')
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>

    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
	<!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
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
    <!-- optionally if you need translation for your language then include locale file as mentioned below
    <script src="/bower_components/bootstrap_fileinput/js/locales/<lang>.js"></script>-->
    <!-- End Bootstrap File input -->
	<!-- DataTables -->
	<script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/dataTables.buttons.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/buttons.flash.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/jszip.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/pdfmake.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/vfs_fonts.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/buttons.html5.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/buttons.print.min.js"></script>
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>

    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>

    <script>
        $(function () {
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
            //Phone mask
            $("[data-mask]").inputmask();

            // [bootstrap file input] initialize with defaults
            $("#input-1").fileinput();
            // with plugin options
            //$("#input-id").fileinput({'showUpload':false, 'previewFileType':'any'});

            //Tooltip
            $('[data-toggle="tooltip"]').tooltip();

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

            //Show success action modal
            $('#success-action-modal').modal('show');
			$('#example2').DataTable({
				"paging": true,
				"lengthChange": true,
				"lengthMenu": [ 50, 75, 100, 150, 200, 250 ],
				"pageLength": 50,
				"searching": true,
				"ordering": true,
				"info": true,
				"autoWidth": true,
				dom: 'lfrtipB',
				buttons: [
					{
						extend: 'excelHtml5',
						title: 'Employees Screening Report'
					},
					{
						extend: 'csvHtml5',
						title: 'Employees Screening Report'
					},
					{
						extend: 'copyHtml5',
						title: 'Employees Screening Report'
					}
				]
			});
			$('#example3').DataTable({
				"paging": true,
				"lengthChange": true,
				"lengthMenu": [ 50, 75, 100, 150, 200, 250 ],
				"pageLength": 50,
				"searching": true,
				"ordering": true,
				"info": true,
				"autoWidth": true,
				dom: 'lfrtipB',
				buttons: [
					{
						extend: 'excelHtml5',
						title: 'Visitors Screening Report'
					},
					{
						extend: 'csvHtml5',
						title: 'Visitors Screening Report'
					},
					{
						extend: 'copyHtml5',
						title: 'Visitors Screening Report'
					}
				]
			});
			var commentId;
			// call comment form
			$('#add-employee-comment-modal').on('show.bs.modal', function (e) {
                var btnEnd = $(e.relatedTarget);
                commentId = btnEnd.data('id');
                var modal = $(this);
            });
			//save comment 
			$('#save-comment').on('click', function () {
                var strUrl = '/screening/save-comment/' + commentId;
                var formName = 'add-comment-form';
                var modalID = 'add-employee-comment-modal';
                var submitBtnID = 'save-comment';
                var redirectUrl = '/';
                var successMsgTitle = 'Comment Added!';
                var successMsg = 'Comment has been Successfully added!';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
        });
    </script>
@endsection