<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $page_title or "PDF View" }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap 3.3.6 -->
    @include('layouts.printables.partials.bootstrap_3_3_6css')
    <!-- Font Awesome -->
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">-->
    <!-- Ionicons --
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">-->
    <!-- Theme style -->
    @include('layouts.printables.partials.adminltecss')

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- custom style -->
    @include('layouts.printables.partials.custom_style')

    <!-- Page dependencies -->
    @yield('page_dependencies')
</head>
<body>
<div class="wrapper">
    <!-- Main content -->
    <!--<section class="invoice">-->
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12 no-padding">
                @if(!empty($quoteProfile->letterhead_url))
					<h2 class="page-header">
                        <img width="100%" height="100" src="{{ public_path() . $quoteProfile->letterhead_url }}" alt="letterhead">
                        <!--<img width="196" height="60" src="{{ $quoteProfile->letterhead_url }}" alt="letterhead">-->
                    </h2>
                @else
					  <table class="table table-hover" >
						<thead>
							<tr style="border: 0;">
								<td class="col-md-6 invoice-col"  style="border: 0;">
									<img width="270" height="110" src="{{ $company_logo }}" alt="logo">
								</td>
								<td class="col-md-6 invoice-col"  style="border: 0;">
									<address>
										<strong>{{ $quoteProfile->divisionLevelGroup->name }}</strong><br>
										{{ $quoteProfile->phys_address }}<br>
										{{ $quoteProfile->phys_city }}, {{ $quoteProfile->phys_postal_code }}<br>
										Reg.: {{ $quoteProfile->registration_number }}<br>
										VAT: {{ $quoteProfile->vat_number }}<br>
										Phone: {{ $quoteProfile->phone_number }}<br>
										Email: {{ $quoteProfile->email }}
									</address>
								</td>
							</tr>
						</thead>
					</table>
                @endif
            </div>
            <!-- /.col -->
        </div>
        <!-- /.title row -->

        <!-- Yield the report content row here -->
        @yield('content')
    <!--</section>-->
    <!-- /.section -->
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.2.3 -->
<script src="/bower_components/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="/bower_components/AdminLTE/bootstrap/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="/bower_components/AdminLTE/dist/js/app.min.js"></script>
<!-- Additional page script -->
@yield('page_script')
</body>
</html>