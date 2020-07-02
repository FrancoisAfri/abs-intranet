@extends('layouts.main_layout')

<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
<!-- bootstrap file input -->
<link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css"/>
<!--  -->
@section('page_dependencies')
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-cart-arrow-down pull-right"></i>
                    <h3 class="box-title"> Search criteria</h3>
                    <p>Enter search Criteria:</p>
                </div>
                <form name="products-search-form" class="form-horizontal" method="POST" action=" "
                      enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="box-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
                                </button>
                                <h4><i class="icon fa fa-ban"></i> Invalid Input Data!</h4>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="form-group{{ $errors->has('search_type') ? ' has-error' : '' }}">
                            <label for="search_type" class="col-sm-2 control-label"> Product Type</label>
                            <div class="col-sm-9">
                                <label class="radio-inline" style="padding-left: 0px;"><input type="radio"
                                                                                              id="rdo_levTkn"
                                                                                              name="search_type"
                                                                                              value="1" checked> Product
                                </label>
                                <label class="radio-inline"><input type="radio" id="rdo_bal" name="search_type"
                                                                   value="2"> Category </label>
                                <label class="radio-inline"><input type="radio" id="rdo_po" name="search_type"
                                                                   value="3"> Package </label>
                                <label class="radio-inline"><input type="radio" id="rdo_all" name="search_type"
                                                                   value="4"> Promotion </label>
                            </div>
                            <!--  -->
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <div class="form-group prod-field {{ $errors->has('product_name') ? ' has-error' : '' }}">
                                <label for="product_name" class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user-circle"></i>
                                        </div>
                                        <select class="form-control select2" style="width: 100%;" id="product_name"
                                                name="product_name">
                                            <option value="">*** Select a product name ***</option>
                                            @foreach($products as $document)
                                                <option value="{{ $document->id }}">{{ $document->name  }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group prod-field">
                                <label for="product_description" class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <input ="text" class="form-control" id="product_description" name="
                                        product_description" value="{{ old('product_description') }}" placeholder="
                                        Search by Description...">
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="form-group prod-field {{ $errors->has('product_name') ? ' has-error' : '' }}">
                                <label for="product_price" class="col-sm-2 control-label">Price</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-money"></i>
                                        </div>
                                        <select class="form-control select2" style="width: 100%;" id="product_price"
                                                name="product_price">
                                            <option value="">*** Select a product Price ***</option>
                                            @foreach($products as $price)
                                                <option value="{{ $price->price }}">{{ $price->price  }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group prod-field {{ $errors->has('cat_id') ? ' has-error' : '' }}">
                                <label for="cat_id" class="col-sm-2 control-label">Category</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user-circle"></i>
                                        </div>
                                        <select class="form-control select2" style="width: 100%;" id="cat_id"
                                                name="cat_id">
                                            <option value="">*** Select a product name ***</option>
                                            @foreach($productss as $products)
                                                <option value="{{ $products->id }}">{{ $products->catName  }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
							<div class="form-group prod-field {{ $errors->has('stock_type') ? ' has-error' : '' }}">
								<label for="fuel_type" class="col-sm-2 control-label">Stock Type</label>
								<div class="col-sm-8">
									<select id="stock_type" name="stock_type" class="form-control">
										<option value="0">*** Select Stock Type ***</option>
										<option value="1"> Stock Item</option>
										<option value="2"> Non Stock Item</option>
										<option value="3"> Both </option>
									</select>
								</div>
                            </div>
                            <div class="form-group cat-field {{ $errors->has('category_name') ? ' has-error' : '' }}">
                                <label for="category_name" class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user-circle"></i>
                                        </div>
                                        <select class="form-control select2" style="width: 100%;" id="category_name"
                                                name="category_name">
                                            <option value="">*** Select a category name ***</option>
                                            @foreach($category as $documentType)
                                                <option value="{{ $documentType->id }}">{{ $documentType->name  }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group cat-field">
                                <label for="category_description" class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <input type="text" class="form-control" id="category_description"
                                               name="category_description" value="{{ old('category_description') }}"
                                               placeholder="Search by name...">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group pack-field {{ $errors->has('document_id') ? ' has-error' : '' }}">
                                <label for="package_name" class="col-sm-2 control-label"> Name</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user-circle"></i>
                                        </div>
                                        <select class="form-control select2" style="width: 100%;" id="package_name"
                                                name="package_name">
                                            <option value="">*** Select a package name ***</option>
                                            @foreach($packages as $documentType)
                                                <option value="{{ $documentType->id }}">{{ $documentType->name  }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group pack-field">
                                <label for="package_description" class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <input type="text" class="form-control" id="package_description"
                                               name="package_description" value="{{ old('package_description') }}"
                                               placeholder="Search by package description...">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group pack-field {{ $errors->has('document_id') ? ' has-error' : '' }}">
                                <label for="product_type" class="col-sm-2 control-label"> Product </label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user-circle"></i>
                                        </div>
                                        <select class="form-control select2" style="width: 100%;" id="product_type"
                                                name="product_type">
                                            <option value="">*** Select a Product Type ***</option>
                                            @foreach($productss as $product)
                                                <option value="{{ $product->id }}">{{ $product->name  }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group pack-field {{ $errors->has('product_name') ? ' has-error' : '' }}">
                                <label for="package_discount" class="col-sm-2 control-label">Discount</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-money"></i>
                                        </div>
                                        <select class="form-control select2" style="width: 100%;" id="package_discount"
                                                name="package_discount">
                                            <option value="">*** Select a product Price ***</option>
                                            @foreach($packages as $documentType)
                                                <option value="{{ $documentType->discount }}">{{ $documentType->discount  }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- promotion -->
                            <div class="form-group prom-field {{ $errors->has('document_id') ? ' has-error' : '' }}">
                                <label for="promotion_name" class="col-sm-2 control-label"> Name</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user-circle"></i>
                                        </div>
                                        <select class="form-control select2" style="width: 100%;" id="promotion_name"
                                                name="promotion_name">
                                            <option value="">*** Select a Product Type ***</option>
                                            @foreach($promotions as $documentType)
                                                <option value="{{ $documentType->id }}">{{ $documentType->name  }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group prom-field">
                                <label for="promotion_discription" class="col-sm-2 control-label">Discription</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <input type="number" class="form-control" id="promotion_discription"
                                               name="promotion_discription" value="{{ old('promotion_discription') }}"
                                               placeholder="Search by promotion discriptiont...">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group prom-field">
                                <label for="promo_date" class="col-sm-2 control-label">Promotion Date</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control daterangepicker" id="promo_date"
                                               name="promo_date" value="" placeholder="Select promo_date Date...">
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                        </div>
                        <!--  -->
                        <div class="box-footer">
                            <!--  <button type="button" id="cancel" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Cancel</button> -->
                            <button type="submit" id="gen-report" name="gen-report" class="btn btn-primary pull-right">
                                <i class="fa fa-check"></i>Search
                            </button>
                        </div>
                        <!-- /.box-footer -->
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- End new User Form-->
        <!-- Confirmation Modal -->
        @if(Session('success_add'))
            @include('contacts.partials.success_action', ['modal_title' => "Registration Successful!", 'modal_content' => session('success_add')])
        @endif
    </div>
@endsection

@section('page_script')
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <!-- Date rane picker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Date Picker -->
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
    <!-- Date picker -->
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>

    <!--        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>-->
    <!--    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->

    <script type="text/javascript">
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();

            function postData(id, data) {
                alert(id);
                //if (data == 'approval_id') location.href = "/leave/approval/" + id;
            }

            //Phone mask
            $("[data-mask]").inputmask();

            //Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
            hideFields();
            //Date Range picker
            $('.daterangepicker').daterangepicker({
                format: 'DD/MM/YYYY',
                endDate: '-1d',
                autoclose: true
            });
            //show/hide fields on radio button toggles (depending on registration type)

            $('#rdo_levTkn, #rdo_bal ,#rdo_po,#rdo_all').on('ifChecked', function () {
                var allType = hideFields();
            });

            function reposition() {
                var modal = $(this)
                    , dialog = modal.find('.modal-dialog');
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
        });

        //function to hide/show fields depending on the
        function hideFields() {
            var allType = $("input[name='search_type']:checked").val();
            if (allType == 1) { //adjsut leave
                //$('.hours-field').hide();
                $('.prod-field').show();
                $('.pack-field').hide();
                $('.cat-field').hide();
                $('.prom-field').hide();

                $('form[name="products-search-form"]').attr('action', '/product/product/Search');
                $('#gen-report').val("Submit");
            }
            else if (allType == 2) { //resert leave
                $('.prod-field').hide();
                $('.cat-field').show();
                $('.pack-field').hide();
                $('.user-field').hide();
                $('form[name="products-search-form"]').attr('action', '/product/category/Search');
                //$('form[name="products-search-form"]').attr('action', '/leave/print/bal');
                $('#gen-report').val("Submit");
            }
            else if (allType == 3) {
                $('.cat-field').hide();
                $('.prod-field').hide();
                $('.pack-field').show();
                $('.prom-field').hide();
                $('form[name="products-search-form"]').attr('action', '/product/package/Search');
                $('#gen-report').val("Submit");

            } else if (allType == 4) {
                $('.cat-field').hide();
                $('.prod-field').hide();
                $('.pack-field').hide();
                $('.prom-field').show();
                $('form[name="products-search-form"]').attr('action', '/product/promotion/Search');
                $('#gen-report').val("Submit");
            }

            return allType;
        }

        //Load divisions drop down


    </script>
@endsection