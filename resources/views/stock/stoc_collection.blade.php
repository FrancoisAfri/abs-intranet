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
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/green.css">
@endsection
@section('content')
	<div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                    <h3 class="box-title"> Request Stock </h3>
                </div>
                <div style="overflow-X:auto;">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
							<tr>
								<th style="width: 5px; text-align: center;"></th>
								<th>Date Requeted</th>
								<th>Title</th>
								<th>Employee</th>
								<th>On Behalf Of</th>
								<th>Remarks</th>
								<th>Status</th>
							</tr>
                        </thead>
                        <tbody>
                        @if (count($stocks) > 0)
                            <ul class="products-list product-list-in-box">
                                @foreach ($stocks as $stock)
                                    <tr id="configuration-list">
                                        <td>
                                            <a href="{{ '/stock/viewcollection/' . $stock->id }}" id="edit_compan"
                                               class="btn btn-warning  btn-xs"><i class="fa fa-money"></i> View More</a></td>
                                        <td>{{ !empty($stock->date_created) ? date(' d M Y', $stock->date_created) : '' }}</td>
                                        <td>{{ !empty($stock->title_name) ? $stock->title_name : '' }}</td>
                                        <td>{{ (!empty($stock->employees)) ?  $stock->employees->first_name . ' ' .  $stock->employees->surname : ''}} </td>
                                        <td>{{ (!empty($stock->employeeOnBehalf)) ?  $stock->employeeOnBehalf->first_name . ' ' .  $stock->employeeOnBehalf->surname : ''}} </td>
                                        <td>{{ (!empty($stock->request_remarks)) ?  $stock->request_remarks : ''}} </td>
                                        <td>{{ !empty($stock->status) ? $stock->requestStatus->step_name : 'Rejected' }}</td>
                                    </tr>
                            @endforeach
                        @endif
                        </tbody>
                        <tfoot>
							<tr>
								<th style="width: 5px; text-align: center;"></th>
								<th>Date Requeted</th>
								<th>Title</th>
								<th>Employee</th>
								<th>On Behalf Of</th>
								<th>Remarks</th>
								<th>Status</th>
							</tr>                        
						</tfoot>
                    </table>
                    <!-- /.box-body -->
                    <div class="box-footer">
                    </div>
                </div>
            </div>
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

	<!-- InputMask -->
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
	<script type="text/javascript">
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

		$(".select2").select2();
		// call hide on_behalf_field
		$('.on_behalf_field').hide();
		$('input').iCheck({
			checkboxClass: 'icheckbox_square-green',
			radioClass: 'iradio_square-green',
			increaseArea: '20%' // optional
		});
		$('#on_behalf').on('ifChecked', function(event){
			$('.on_behalf_field').show();
		});
	});
	</script>
@endsection