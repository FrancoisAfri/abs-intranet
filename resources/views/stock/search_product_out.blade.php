@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- Include Date Range Picker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
    <!--Time Charger-->
    <!--  -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css"
          rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                </div>
                <form class="form-horizontal" method="POST" action="/stock/stock_outsearch">
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
                        <div class="col-md-8 col-md-offset-2">
                            <div>
                                <div class="box-header with-border" align="center">
                                    <h3 class="box-title">Products Search</h3>
                                </div>
                                <div class="box-body" id="vehicle_details">

                                    <div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
                                        <label for="{{ 'category_id' }}" class="col-sm-2 control-label">Categories </label>

                                        <div class="col-sm-8">
                                            <select id="category_id" name="category_id" class="form-control select2"
                                                    style="width: 100%;" onchange="productcategoryDDOnChange(this)">
                                                <option value="">*** Please Select a Category ***</option>
                                                <option value="0"></option>
                                                @foreach($productCategories as $product)
                                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('product_id') ? ' has-error' : '' }}">
                                        <label for="{{ 'product_id' }}"
                                               class="col-sm-2 control-label">Products </label>

                                        <div class="col-sm-8">
                                            <select id="product_id" name="product_id[]" class="form-control select2"
                                                    multiple="multiple" style="width: 100%;">
                                                <option value="">*** Please Select a Category First ***</option>
                                            </select>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label for="fuel_type" class="col-sm-2 control-label">Stock Type</label>
										<div class="col-sm-8">
											<select id="stock_type" name="stock_type" class="form-control">
												<option value="0">*** Select Stock Type ***</option>
												<option value="1" selected> Stock Item</option>
												<option value="3"> Both </option>
											</select>
										</div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary pull-right"><i
                                                class="fa fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
		 @if(Session('success_stock'))
            @include('contacts.partials.success_action', ['modal_title' => "Stock's Item(s) Updated!", 'modal_content' => session('success_stock')])
        @endif
    </div>
@endsection

@section('page_script')
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- bootstrap datepicker -->
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>

    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js"
            type="text/javascript"></script>
    <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->

    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>

    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script type="text/javascript">
        $(function () {
            $(".select2").select2();
            $('.hours-field').hide();
            $('.comp-field').hide();
            var moduleId;
            //Tooltip
            $('[data-toggle="tooltip"]').tooltip();

            //Vertically center modals on page

            //Phone mask
            $("[data-mask]").inputmask();

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
        });


    </script>
@endsection
