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
							<div class="form-group">
								<label for="clockin" class="col-sm-2 control-label">CLOCK IN</label>
								<div class="col-sm-10">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-unlock-alt"></i>
										</div>
										{{$clockin->created_at}}
									</div>
								</div>		
							</div>
						@endif 
						@if (!empty($clockout))
							<div class="form-group">
								<label for="clockin" class="col-sm-2 control-label">CLOCK OUT</label>
								<div class="col-sm-10">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-unlock-alt"></i>
										</div>
										{{$clockout->created_at}}
									</div>
								</div>		
							</div>
						@endif
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
					@if (!empty($clockin))
						@if (empty($clockout))
							<input type="hidden" name="clockout" value="2">
							<button type="submit" class="btn btn-primary pull-right"> CLOCK OUT</button>
						@endif
					@else
						<input type="hidden" name="clockin" value="1">
						<button type="submit" class="btn btn-primary pull-right"> CLOCK IN</button>
					@endif

                        <input type="hidden" id="latitudes" name="latitudes" >
                        <input type="hidden" id="longitudes" name="longitudes" >
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

        let button = document.getElementById("get-location");
        let latText = document.getElementById("latitude");
        let longText = document.getElementById("longitude");
        let vras =  'hfjhjf';

       function getLocation(){
           navigator.geolocation.getCurrentPosition((position) => {
               let lat = position.coords.latitude;
               let long = position.coords.longitude;

               // latText.innerText = lat.toFixed(2);
               // longText.innerText = long.toFixed(2);

               document.getElementById("latitudes").value = lat;
               document.getElementById("longitudes").value = long;
           });
       }
        getLocation()



        //Phone mask
        $("[data-mask]").inputmask();
    </script>
@endsection