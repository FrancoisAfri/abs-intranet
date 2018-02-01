@extends('layouts.guest_main_layout')

@section('page_dependencies')
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">

    <!-- Star Ratting Plugin -->
    <!-- default styles -->
    <link href="/bower_components/kartik-v-bootstrap-star-rating-3642656/css/star-rating.css" media="all" rel="stylesheet" type="text/css" />
    <!-- optionally if you need to use a theme, then include the theme CSS file as mentioned below -->
    <link href="/bower_components/kartik-v-bootstrap-star-rating-3642656/themes/krajee-svg/theme.css" media="all" rel="stylesheet" type="text/css" />
    <!-- /Star Ratting Plugin -->
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
            <div class="box box-default">
                <div class="box-header with-border">
                    <i class="fa fa-file-text pull-right"></i>
                    <h3 class="box-title">Client Voucher</h3>
                    <p>Search client vouchers.</p>
                </div>
                <!-- /.box-header -->

                <!-- Form Start -->
                <form id="voucher_form" class="form-horizontal" method="POST">
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
                        @if (session('success_add'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-check"></i> Thanks for your feedback!</h4>
                                {{ session('success_add') }}
                            </div>
                        @endif
						<div class="form-group">
                            <label for="search_type" class="col-sm-2 control-label">Voucher Type</label>
                            <div class="col-sm-10">
                                <div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-user"></i>
								</div>
								<select class="form-control" name="search_type" id="search_type" placeholder="Select Appliction Type"  onchange="changetype(this.value)"  required>
									<option value="1">Accomodation</option>
									<option value="2">Car</option>
								</select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('clnt_name') ? ' has-error' : '' }}">
                            <label for="clnt_name" class="col-sm-2 control-label">Full Name</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" id="clnt_name" name="clnt_name" value="{{ old('client_name') }}" placeholder="Client Full Name">
                                </div>
                            </div>
                        </div>
						<div class="form-group {{ $errors->has('sup_ref') ? ' has-error' : '' }} voucher">
                            <label for="sup_ref" class="col-sm-2 control-label">Ref No</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" id="sup_ref" name="sup_ref" value="{{ old('sup_ref') }}" placeholder="Ref No">
                                </div>
                            </div>
                        </div>
						<div class="form-group {{ $errors->has('c_sup_vouch_no') ? ' has-error' : '' }} car">
                            <label for="c_sup_vouch_no" class="col-sm-2 control-label">Voucher No</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" id="c_sup_vouch_no" name="c_sup_vouch_no" value="{{ old('c_sup_vouch_no') }}" placeholder="Voucher No">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" id="get_voucher" name="get_voucher" class="btn btn-primary btn-flat pull-right"><i class="fa fa-search"></i> Search Voucher</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Include add new modal -->
    </div>
@endsection

@section('page_script')
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

    <!-- Star Ratting Plugin -->
    <!-- default styles -->
    <script src="/bower_components/kartik-v-bootstrap-star-rating-3642656/js/star-rating.js" type="text/javascript"></script>
    <!-- optionally if you need to use a theme, then include the theme JS file as mentioned below -->
    <script src="/bower_components/kartik-v-bootstrap-star-rating-3642656/themes/krajee-svg/theme.js"></script>
    <!-- optionally if you need translation for your language then include locale file as mentioned below -->
    <!-- <script src="path/to/js/locales/<lang>.js"></script> -->
    <!-- /Star Ratting Plugin -->

    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
			$('.voucher').show();
			$('.car').hide();
        });
		function changetype(type)
		{
			if (type == 1)
			{
				$('.car').hide();
				$('.voucher').show();
				$('#voucher_form').attr('action', '/vouchers/get-voucher');
			}
			else if (type == 2)
			{
				$('.voucher').hide();
				$('.car').show();
				$('#voucher_form').attr('action', '/vouchers/get-car-voucher');
			}
				
		}
    </script>
@endsection