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
                    <h3 class="box-title"> Delivery Note</h3>
                </div>
                <div style="overflow-X:auto;">
                   <table class="table table-striped table-bordered">
						<tr>
							<td class="caption"><b>Date Requeted:</b></td>
							<td>{{ !empty($stock->date_created) ? date(' d M Y', $stock->date_created) : '' }}</b></td>
							<td class="caption"><b>Title:</b></td>
							<td>{{ !empty($stock->title_name) ? $stock->title_name : '' }}</b></td>
						</tr>
						<tr>
							<td class="caption"><b>Requested By:</b></td>
							<td>{{ (!empty($stock->employees)) ?  $stock->employees->first_name . ' ' .  $stock->employees->surname : ''}}</b></td>
							<td class="caption"><b>On Behalf Of:</td>
							<td>{{ (!empty($stock->employeeOnBehalf)) ?  $stock->employeeOnBehalf->first_name . ' ' .  $stock->employeeOnBehalf->surname : ''}}</b></td>
						</tr>
					</table>
					<table class="table table-striped table-bordered">
						<hr class="hr-text" data-content="Stock Items">
						<tr>
							<td>#</td>
							<td><b>Category</b></td>
							<td><b>Product</b></td>
							<td style="text-align:center"><b>Quantity</b></td>
						</tr>
						@if (count($stock->stockItems) > 0)
							@foreach ($stock->stockItems as $items)
								<tr>
									<td>{{ $loop->iteration }}</td>
									<td>{{ !empty($items->categories->name) ? $items->categories->name : '' }}</td>
									<td>{{ !empty($items->products->name) ? $items->products->name : '' }}</td>
									<td style="text-align:center">{{ !empty($items->quantity) ? $items->quantity : '' }}</td>
								</tr>
							@endforeach
						@else
							<tr><td colspan="3"></td></tr>
						@endif
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
	</script>
@endsection