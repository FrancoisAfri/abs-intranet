@extends('layouts.main_layout')
@section('page_dependencies')
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<!-- iCheck -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/green.css">
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <i class="fa fa-graduation-cap pull-right"></i>
                    <h3 class="box-title">Job Card Completion</h3>
                    <p id="box-subtitle"></p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/jobcard/conclude-jobparts/{{$card->id}}">
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
						<div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
							<label for="category_id" class="col-sm-2 control-label">Category</label>
							<div class="col-sm-10">
								<select id="product_id" name="product_id" class="form-control select2" style="width: 100%;"
									onchange="productcategoryDDOnChange(this)">
									<option value="">*** Please Select a Category ***</option>
									<option value="0"></option>
									@foreach($productCategories as $category)
										<option value="{{ $category->id }}">{{ $category->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
                        <div class="form-group{{ $errors->has('product_id') ? ' has-error' : '' }}">
                            <label for="product_id" class="col-sm-2 control-label">Product</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <select id="category_id" name="category_id" class="form-control select2"
										style="width: 100%;">
										<option value="">*** Please Select a Category First ***</option>
									</select>
                                </div>
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('project_id') ? ' has-error' : '' }}">
							<label for="no_of_parts_used" class="col-sm-2 control-label">Number</label>
							<div class="col-sm-8">
								<input type="number" class="form-control" id="no_of_parts_used" name="no_of_parts_used"
									   value=""
									   placeholder="Enter a Number">
							</div>
						</div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" id="cancel" class="btn btn-default"><i class="fa fa-arrow-left"></i> Cancel</button>
                        <button type="submit" class="btn btn-success pull-right"><i class="fa fa-database"></i> Submit</button>
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
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>

    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
	<!-- Ajax dropdown options load -->
	<script src="/custom_components/js/load_dropdown_options.js"></script>
	<!-- Ajax form submit -->
	<script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script type="text/javascript">
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
            //Cancel button click event
            $('#cancel').click(function () {
                location.href = '/jobcard/parts/{{$card->id}}';
            });
		});
    </script>
@endsection