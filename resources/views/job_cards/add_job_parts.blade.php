@extends('layouts.main_layout')
@section('page_dependencies')
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<!-- iCheck -->
 <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Add Part(s)</h3>
                    <p id="box-subtitle"></p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/jobcard/addjobparts/{{$jobcardpart->id}}">
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
						<div class="form-group{{ $errors->has('part_type') ? ' has-error' : '' }}">
                            <label for="part_type" class="col-sm-2 control-label">Type</label>
                            <div class="col-sm-10">
                                <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_prod" name="part_type" value="1" checked> Items</label>
                                <label class="radio-inline"><input type="radio" id="rdo_kit" name="part_type" value="2"> Kit</label>
                            </div>
                        </div>
						<div class="form-group products-field {{ $errors->has('category_id') ? ' has-error' : '' }}">
							<label for="category_id" class="col-sm-2 control-label">Category</label>
							<div class="col-sm-10">
								<select id="category_id" name="category_id" class="form-control select2" style="width: 100%;"
									onchange="productcategoryDDOnChange(this)">
									<option value="">*** Please Select a Category ***</option>
									<option value="0"></option>
									@foreach($productCategories as $category)
										<option value="{{ $category->id }}">{{ $category->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
                        <div class="form-group products-field {{ $errors->has('product_id') ? ' has-error' : '' }}">
                            <label for="product_id" class="col-sm-2 control-label">Product</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <select id="product_id" name="product_id" class="form-control select2"
										style="width: 100%;">
										<option value="">*** Please Select a Category First ***</option>
									</select>
                                </div>
                            </div>
                        </div>
						<div class="form-group products-field{{ $errors->has('project_id') ? ' has-error' : '' }}">
							<label for="no_of_parts_used" class="col-sm-2 control-label">Number</label>
							<div class="col-sm-8">
								<input type="number" class="form-control" id="no_of_parts_used" name="no_of_parts_used"
									   value=""
									   placeholder="Enter a Number">
							</div>
						</div>
						<div class="form-group kit-field {{ $errors->has('category_id') ? ' has-error' : '' }}">
							<label for="kit_id" class="col-sm-2 control-label">Kit</label>
							<div class="col-sm-10">
								<select id="kit_id" name="kit_id" class="form-control select2" style="width: 100%;">
									<option value="">*** Please Select a kit ***</option>
									<option value="0"></option>
									@foreach($kits as $kit)
										<option value="{{ $kit->id }}">{{ $kit->name }}</option>
									@endforeach
								</select>
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
			 //Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
            //Cancel button click event
            $('#cancel').click(function () {
                location.href = '/jobcard/parts/{{$jobcardpart->id}}';
            });
			//call hide/show fields functions on doc ready
            hideFields();

            //show/hide file upload or manual fields on radio checked
            $('#rdo_kit, #rdo_prod').on('ifChecked', function(){
                hideFields();
            });
		});
		
		//function to hide/show security fields
        function hideFields() {
			
            var part = $("input[name='part_type']:checked").val();
            if (part == 1) { //yes
                $('.products-field').show();
				$('.kit-field').hide();
            }
            else if (part == 2) { //no
                $('.kit-field').show();
				$('.products-field').hide();
            }
            return part;
        }
    </script>
@endsection