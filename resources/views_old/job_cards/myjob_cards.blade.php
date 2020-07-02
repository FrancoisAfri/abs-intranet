@extends('layouts.main_layout')
@section('page_dependencies')
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!--  -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css"
          rel="stylesheet">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                    <h3 class="box-title"> Job Cards </h3>
                </div>
                <div style="overflow-X:auto;">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th style="width: 5px; text-align: center;"></th>
                            <th>Job Card #</th>
                            <th>Vehicle Name</th>
                            <th>Registration</th>
                            <th>Job Card Date</th>
                            <th>Completion Date</th>
                            <th>Mechanic</th>
                            <th>Service Type</th>
                            <th>Supplier</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($jobcardmaintanance) > 0)
                            <ul class="products-list product-list-in-box">
                                @foreach ($jobcardmaintanance as $jobcard)
                                    <tr id="configuration-list">
                                        <td>
                                            <a href="{{ '/jobcards/viewcard/' . $jobcard->id }}" id="edit_compan"
                                               class="btn btn-warning  btn-xs"><i class="fa fa-money"></i> View</a></td>
                                        <td>{{ !empty($jobcard->jobcard_number) ? $jobcard->jobcard_number : '' }}</td>
                                        <td>{{ (!empty( $jobcard->fleet_number . ' ' .  $jobcard->vehicle_registration . ' ' . $jobcard->vehicle_make . ' ' . $jobcard->vehicle_model))
											?  $jobcard->fleet_number . ' ' .  $jobcard->vehicle_registration . ' ' . $jobcard->vehicle_make . ' ' . $jobcard->vehicle_model : ''}} </td>
                                        <td>{{ (!empty( $jobcard->vehicle_registration)) ?  $jobcard->vehicle_registration : ''}} </td>
                                        <td>{{ !empty($jobcard->card_date) ? date(' d M Y', $jobcard->card_date) : '' }}</td>
                                        <td>{{ !empty($jobcard->completion_date ) ? date(' d M Y', $jobcard->completion_date) : '' }}</td>
                                        <td>{{ !empty($jobcard->firstname . ' ' . $jobcard->surname) ? $jobcard->firstname . ' ' . $jobcard->surname : '' }}</td>
                                        <td>{{ !empty($jobcard->servicetype) ? $jobcard->servicetype : '' }}</td>
                                        <td>{{ !empty($jobcard->Supplier) ? $jobcard->Supplier : '' }}</td>
                                        <td>{{ !empty($jobcard->aStatus) ? $jobcard->aStatus : '' }}</td>
                                    </tr>
                            @endforeach
                        @endif
                        </tbody>
                        <tfoot>
                        <tr>
                            <th style="width: 5px; text-align: center;"></th>
                            <th>Job Card #</th>
                            <th>Vehicle Name</th>
                            <th>Registration</th>
                            <th>Job Card Date</th>
                            <th>Completion Date</th>
                            <th>Mechanic</th>
                            <th>Service Type</th>
                            <th>Supplier</th>
                            <th>Status</th>
                        </tr>
                        </tfoot>
                    </table>
                    <!-- /.box-body -->
                    <div class="box-footer">
						<a href="/jobcards/create-job-card" id="edit_compan"
                                               class="btn btn-warning pull-right"> Create Job Card</a>
                    </div>
                </div>
            </div>
        <!-- Include delete warning Modal form-->
            <!-- Confirmation Modal -->
            @if(Session('success_sent'))
                @include('job_cards.partials.success_action', ['modal_title' => "Job Card Created!", 'modal_content' => session('success_sent')])
            @endif
        </div>
    </div>
@endsection
@section('page_script')
<!-- DataTables -->
	<script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
	<script src="/custom_components/js/modal_ajax_submit.js"></script>
	<!-- time picker -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
	<!-- Select2 -->
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<!-- End Bootstrap File input -->
	<script src="/custom_components/js/modal_ajax_submit.js"></script>
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
	<!-- iCheck -->
	<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
	<script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js"
			type="text/javascript"></script>
	<!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
	<script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js"
			type="text/javascript"></script>
	<!-- the main fileinput plugin file -->
	<script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
	<!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
	<script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

	<!-- InputMask -->
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<script>
		//Cancel button click event
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

	$(function () {

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
		$(window).on('resize', function () {
			$('.modal:visible').each(reposition);
		});

		//Show success action modal
		$('#success-action-modal').modal('show');

		$(".js-example-basic-multiple").select2();

		//Cancell booking

		//Post form to server using ajax (add)
		$('#add_jobcardtypes').on('click', function () {
			var strUrl = '/jobcards/addjobcard';
			var formName = 'add-jobcard-form';
			var modalID = 'add-jobcard-modal';
			var submitBtnID = 'add_jobcardtypes';
			var redirectUrl = '/jobcards/mycards';
			var successMsgTitle = 'New Record Added!';
			var successMsg = 'The Record  has been updated successfully.';
			modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
		});
	});
	</script>
@endsection