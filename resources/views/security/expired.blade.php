@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-8 col-md-offset-2">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user pull-right"></i>
                    <h3 class="box-title">Password Change</h3>
                    <p>Please Fill In The Form:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/password/post_expired/{{ $user->id }}">
					{{ csrf_field() }}

                    <div class="box-body">
					@if (session('password_updated'))
                        <div class="alert alert-success">
                            {{ session('password_updated') }}
                        </div>
                        <a href="/">Return to Dashboard</a>
					@else
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
							<div class="form-group{{ $errors->has('current_password') ? ' has-error' : '' }}">
								<label for="current_password" class="col-sm-3 control-label">Current Password</label>
								<div class="col-sm-9">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-lock"></i>
										</div>
										<input id="current_password" type="password" class="form-control" name="current_password"  placeholder="Enter Current Password...">
									</div>
								</div>
							</div>
							<div class="form-group{{ $errors->has('new_password') ? ' has-error' : '' }}">
								<label for="new_password" class="col-sm-3 control-label">New Password</label>
								<div class="col-sm-9">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-lock"></i>
										</div>
										<input id="new_password" type="password" class="form-control" name="new_password"  placeholder="Enter New Password...">
									</div>
								</div>
							</div>
							<div class="form-group{{ $errors->has('confirm_password') ? ' has-error' : '' }}">
								<label for="confirm_password" class="col-sm-3 control-label">New Password</label>
								<div class="col-sm-9">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-lock"></i>
										</div>
										<input id="confirm_password" type="password" class="form-control" name="confirm_password"  placeholder="Confirmation Password...">
									</div>
								</div>
							</div>
							
						 </div>   
						<!-- /.box-body -->
						<div class="box-footer">
							<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-user-plus"></i> Reset Password</button>
						</div>
					@endif
                    <!-- /.box-footer -->
                </form>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- End new User Form-->
		
		<!-- Include modal -->
        @if(Session('password_updated'))
            @include('contacts.partials.success_action', ['modal_title' => "Password Updated!", 'modal_content' => session('password_updated')])
        @endif
    </div>
    @endsection
<!-- /.register-box -->
 @section('page_script')
<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

<script>
</script>
@endsection
