<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
            body { background-image: url(<?php echo \App\CompanyIdentity::systemSettings('login_background_image_url'); ?>); background-repeat: no-repeat; background-position: center; background-size: cover; height: 100%;}
    </style>
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
    <![endif]
	login-page-->
</head>
<body class="hold-transition">
<div class="login-box">
    <div class="login-logo">
        <img src="{{ \App\CompanyIdentity::systemSettings('company_logo_url') }}" width="100%" class="img-responsive" alt="Company Logo">
        <!-- <a href="/"><b>NU-LAXMI</b> LEASING</a> -->
    </div>
    <!-- /.login-logo -->
	
    <div class="login-box-body">
        <p class="login-box-msg">Please sign in with your email and password</p>

        @if ($errors->has('email') || $errors->has('password'))
            <div id="failed-login-alert" class="alert alert-danger alert-dismissible fade in">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Invalid Login!</h4>
                {{ $errors->first('email') }}
                {{ $errors->first('password') }}
            </div>
        @endif
        <form role="form" method="POST" action="{{ url('/login') }}">
           {{ csrf_field() }}
            <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                <input id="email" type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}" required autofocus>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                <input id="password" type="password" class="form-control" placeholder="Password" name="password" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox"> Remember Me
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        <a href="#" data-toggle="modal" data-target="#forgot-password-modal"><font data-toggle="tooltip" title="Click here to reset your password.">I forgot my password</font></a><br>
        <!-- New Client? Click <a href="/register" class="text-center">here to register</a> -->
    </div>
    <!-- /.login-box-body -->

    <!-- Success registration modal-->
</div>
<!-- /.login-box -->

<!-- include forgot password modal -->
@include('security.partials.forgot_password')

<!-- include successful registration modal -->
@if (session('success_modal'))
    @include('loan.partials.success_action', ['modal_title' => 'Registration Successful!', 'modal_content' => session('success_modal')])
@endif

<!-- jQuery 2.2.3 -->
<script src="/bower_components/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="/bower_components/AdminLTE/bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
<!-- Ajax form submit -->
<script src="/custom_components/js/modal_ajax_submit.js"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
        //autoclose alert after 7 seconds
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
            $(window).on('resize', function() {
                $('.modal:visible').each(reposition);
            });
		/// new reset password code
		//Post perk form to server using ajax (add)
		$('#reset-password').on('click', function() {
			var strUrl = '/users/recoverpw';
			var formName = 'reset-password-form';
			var modalID = 'forgot-password-modal';
			var submitBtnID = 'reset-password';
			var redirectUrl = '/login';
			$('#reset-password').prop('disabled', true);
			var successMsgTitle = 'Password Changed!';
			var successMsg = 'The password has been successfully reset. A reset password has been sent to your email address, use it to login and go to your profile to change your password.!';
			modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
		});
		
    });
</script>
</body>
</html>