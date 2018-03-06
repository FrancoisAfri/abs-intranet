<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ \App\CompanyIdentity::systemSettings('company_name') . 'online system'  }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<div class="col-md-8 col-md-offset-2">
<div class="login-logo">
        <img src="{{ \App\CompanyIdentity::systemSettings('company_logo_url') }}" width="100%" class="img-responsive" alt="Company Logo">
    </div>
<div class="panel panel-default">
	<div class="panel-heading">Reset Password</div>

	<div class="panel-body">
		@if (session('status'))
			<div class="alert alert-success">
				{{ session('status') }}
			</div>
			<a href="/">Return to homepage</a>
		@else
		<div class="alert alert-info">
			Your password has expired, please change it.
		</div>
		<form class="form-horizontal" method="POST" action="password/post_expired">
			{{ csrf_field() }}

			<div class="form-group{{ $errors->has('current_password') ? ' has-error' : '' }}">
				<label for="current_password" class="col-md-4 control-label">Current Password</label>

				<div class="col-md-6">
					<input id="current_password" type="password" class="form-control" name="current_password" required>

					@if ($errors->has('current_password'))
						<span class="help-block">
							<strong>{{ $errors->first('current_password') }}</strong>
						</span>
					@endif
				</div>
			</div>

			<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
				<label for="password" class="col-md-4 control-label">New Password</label>

				<div class="col-md-6">
					<input id="password" type="password" class="form-control" name="password" required>

					@if ($errors->has('password'))
						<span class="help-block">
							<strong>{{ $errors->first('password') }}</strong>
						</span>
					@endif
				</div>
			</div>

			<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
				<label for="password-confirm" class="col-md-4 control-label">Confirm New Password</label>
				<div class="col-md-6">
					<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

					@if ($errors->has('password_confirmation'))
						<span class="help-block">
							<strong>{{ $errors->first('password_confirmation') }}</strong>
						</span>
					@endif
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-6 col-md-offset-4">
					<button type="submit" class="btn btn-primary">
						Reset Password
					</button>
				</div>
			</div>
		</form>
		@endif
	</div>
</div>
</div>
<!-- /.register-box -->

<!-- jQuery 2.2.3 -->
<script src="/bower_components/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="/bower_components/AdminLTE/bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
<!-- InputMask -->
<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<script>
    $(function () {
        //iCheck
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });

        //Phone mask
        $("[data-mask]").inputmask();

        //autoclose alert after 7 seconds
        $("#invalid-input").alert();
        window.setTimeout(function() { $("#invalid-input").fadeOut('slow'); }, 7000);
    });
</script>
</body>
</html>
