@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-8 col-md-offset-2">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user pull-right"></i>
                    <h3 class="box-title">Attendance</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/employee/clockin/assign">
                    {{ csrf_field() }}
                    <div class="box-body">
                        @if (!empty($clockin))
							<div>
								<label for="clockin" class="col-sm-3 control-label">CLOCK IN</label>
								<div>
									{{$clockin->created_at}}
								</div>
							</div>
						@endif
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
					@if (!empty($clockin))
						<input type="hidden" name="clockout" value="1">
                        <button type="submit" class="btn btn-primary pull-right"> CLOCK OUT</button>
					@else
						<input type="hidden" name="clockin" value="1">
						<button type="submit" class="btn btn-primary pull-right"> CLOCK IN</button>
					@endif
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
    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <script type="text/javascript">
        //Cancel button click event
        document.getElementById("cancel").onclick = function () {
            location.href = "/users";
        };

        //Phone mask
        $("[data-mask]").inputmask();
    </script>
@endsection