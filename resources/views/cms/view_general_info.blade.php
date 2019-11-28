@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <!-- Include Date Range Picker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title"> General Information</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="text-align: center;" colspan="2">CONTACT DETAILS</th>
                        </tr>
						<tr><td><b>Head Office</b></td><td>011 486 9000</td></tr>
						<tr><td><b>Hatfield Office</b></td><td>012 342 1245</td></tr>
						<tr><td><b>DBE</b></td><td>012 357 3517</td></tr>
						<tr><td><b>STATSSA</b></td><td>012 337 6426</td></tr>
						<tr><td><b>ATNS</b></td><td>011 607 1311</td></tr>
						<tr><td><b>Cape Town</b></td><td>021 872 1994</td></tr>
						<tr><td><b>Durban</b></td><td>031 303 4341</td></tr>
						<tr><td><b>Sassa</b></td><td>012 400 2594</td></tr>
						<tr><td><b>Extension List</b></td><td><a class="btn btn-default btn-flat btn-block pull-right btn-xs"
													   href="{{ Storage::disk('local')->url("general/Extension List - Sep 2019.xlsx") }}"
													   target="_blank"><i class="fa fa-file-pdf-o"></i> Extension List</a></td></tr>
                    </table>
                    <!--   </div> -->
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_script')
	<script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
	<!-- iCheck -->
	<!-- Start Bootstrap File input -->
	<!-- canvas-to-blob.min.js is only needed if you wish to resize images before upload. This must be loaded before fileinput.min.js -->
	<script src="/bower_components/bootstrap_fileinput/js/plugins/canvas-to-blob.min.js"
			type="text/javascript"></script>
	<!-- the main fileinput plugin file -->
	<!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
	<script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js"
			type="text/javascript"></script>
	<!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
	<script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js"
			type="text/javascript"></script>
	<!-- the main fileinput plugin file -->
	<script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
	<!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
	<script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
	<!-- optionally if you need translation for your language then include locale file as mentioned below
	<script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
	<!-- Select2 -->
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<!-- Ajax dropdown options load -->
	<script src="/custom_components/js/load_dropdown_options.js"></script>
	<!-- CK Editor -->
	<script src="https://cdn.ckeditor.com/4.7.1/standard/ckeditor.js"></script>
	<!-- Ajax form submit -->
	<script src="/custom_components/js/modal_ajax_submit.js"></script>
	<script>
	//Cancel button click event
	document.getElementById("back_button").onclick = function () {
		location.href = "/";
	};
	</script>
@endsection
