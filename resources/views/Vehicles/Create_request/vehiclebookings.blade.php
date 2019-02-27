@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <!-- Include Date Range Picker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title"> Vehicle Booking </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <form class="form-horizontal" method="POST" action="/vehicle_management/vehiclebooking/{{$vehicle->id}}">
                    {{ csrf_field() }}
                    <div class="box-body">

                        <div class="row">
                            <div class="col-sm-12">
                                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                    <strong class="lead">Vehicle Details</strong><br>

                                    @if(!empty($vehiclemaker))
                                        | &nbsp; &nbsp; <strong>Vehicle Make:</strong> <em>{{ $vehiclemaker->name }}</em> &nbsp;
                                        &nbsp;
                                    @endif
                                    @if(!empty($vehiclemodeler))
                                        -| &nbsp; &nbsp; <strong>Vehicle Model:</strong> <em>{{ $vehiclemodeler->name }}</em>
                                        &nbsp; &nbsp;
                                    @endif
                                    @if(!empty($vehicleTypes))
                                        -| &nbsp; &nbsp; <strong>Vehicle Type:</strong> <em>{{ $vehicleTypes->name }}</em> &nbsp;
                                        &nbsp;
                                    @endif
                                    @if(!empty($vehicle->vehicle_registration))
                                        -| &nbsp; &nbsp; <strong>Vehicle Registration:</strong>
                                        <em>{{ $vehicle->vehicle_registration }}</em> &nbsp; &nbsp;
                                    @endif
                                    @if(!empty($vehicle->year))
                                        -| &nbsp; &nbsp; <strong>Year:</strong> <em>{{ $vehicle->year }}</em> &nbsp;
                                        &nbsp;
                                    @endif
                                    @if(!empty($vehicle->vehicle_color))
                                        -| &nbsp; &nbsp; <strong>Vehicle Color:</strong>
                                        <em>{{ $vehicle->vehicle_color }}</em> &nbsp; &nbsp; -|
                                    @endif
                                </p>
                            </div>
                        </div>
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
                            <!--  -->
                            <div class="col-md-8 col-md-offset-2">
                                <div>
                                    <div class="box-header with-border" align="center">
                                        <h3 class="box-title">Booking Request</h3>
                                    </div>
                                    <div class="box-body" id="vehicle_details">

                                        <div class="form-group">
                                            <label for="path" class="col-sm-2 control-label">Vehicle Type</label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-truck"></i>
                                                    </div>

                                                    <input type="text" id ="vehicletype" class="form-control form-control-sm pull-left" name="vehicletype" value="{{ (!empty($vehicleTypes->name)) ?  $vehicleTypes->name : ''}}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="path" class="col-sm-2 control-label">Vehicle Model</label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-bullseye"></i>
                                                    </div>
                                                    <input type="text" id ="vehiclemodel" class="form-control pull-left" name="vehiclemodel" value="{{ (!empty($vehiclemodeler->name)) ?  $vehiclemodeler->name : ''}}" readonly>
                                                </div>
                                            </div>
                                        </div>   
                                        <div class="form-group">
                                            <label for="path" class="col-sm-2 control-label">Vehicle Reg. No</label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-id-card-o"></i>
                                                    </div>
                                                    <input type="text" id ="vehicle_reg" class="form-control pull-left" name="vehicle_reg" value="{{ (!empty($vehicle->vehicle_registration)) ?  $vehicle->vehicle_registration : ''}}" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="path" class="col-sm-2 control-label">Required From </label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <input type='text' class="form-control" id='requiredfrom' name="requiredfrom" value="{{ !empty($startdate) ?  date("F j, Y, g:i a", trim($startdate)) : '' }}"/>
                                                    <input type="hidden" name="required_from" id="required_from" value="{{$startdate}}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="path" class="col-sm-2 control-label">Required To </label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <input type='text' class="form-control" id='requiredto' name="requiredto" value="{{ !empty($enddate) ?  date("F j, Y, g:i a", $enddate) : '' }}"/>
                                                    <input type="hidden" name="required_to"  id="required_to" value="{{$enddate}}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="Status" class="col-sm-2 control-label">Usage Type </label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-ravelry"></i>
                                                    </div>
                                                    <select id="usage_type" name="usage_type" required class="form-control">
                                                        <option value="0">*** Select a Booking Type  ***</option>
                                                        <option value="1"> Usage</option>
                                                        <option value="2"> Service</option>
                                                        <option value="2"> Maintenance</option>
                                                        <option value="2"> Repair</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="path" class="col-sm-2 control-label">Vehicle Driver</label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-user-o"></i>
                                                    </div>
                                                    <select class="form-control" style="width: 100%;"
                                                            id="driver" name="driver">
                                                        <option value="">*** Select Driver ***</option>
                                                        @foreach($employees as $user)
                                                            <option value="{{ $user->id }}">{{ $user->first_name . ' ' . $user->surname  }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group {{ $errors->has('purpose') ? ' has-error' : '' }}">
                                            <label for="purpose" class="col-sm-2 control-label">Purpose</label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-sticky-note"></i>
                                                    </div>
                                                    <textarea class="form-control" id="purpose" name="purpose"
                                                              placeholder="Enter Purpose..."
                                                              rows="3">{{ old('purpose') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group {{ $errors->has('destination') ? ' has-error' : '' }}">
                                            <label for="path" class="col-sm-2 control-label">Destination </label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-anchor"></i>
                                                    </div>
                                                    <input type="text" id ="destination" class="form-control form-control-sm pull-left" name="destination" value="{{ old('purpose') }}">
                                                </div>
                                            </div>
                                        </div>
                                        @if (isset($vehicle) && $vehicle->metre_reading_type === 1)
                                         <div class="form-group">
                                            <label for="path" class="col-sm-2 control-label">OdometerReading</label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-spinner"></i>
                                                    </div>
                                                    <input type="text" id ="odometer_reading" class="form-control form-control-sm pull-left" name="odometer_reading" value="{{ (!empty($vehicle->odometer_reading)) ? $vehicle->odometer_reading : ''}}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                         <div class="form-group">
                                            <label for="path" class="col-sm-2 control-label">HoursReading</label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-spinner"></i>
                                                    </div>
                                                    <input type="text" id ="hours_reading" class="form-control form-control-sm pull-left" name="hours_reading" value="{{ (!empty($vehicle->hours_reading)) ? $vehicle->hours_reading : ''}}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                         @endif			
                                        <input type="hidden" id="vehicle_id" name="vehicle_id"
                                               value="{{ !empty($vehicle->id) ? $vehicle->id : ''}}">
										<input type="hidden" id="metre_reading_type" name="metre_reading_type"
                                               value="{{ !empty($vehicle->metre_reading_type) ? $vehicle->metre_reading_type : 0}}">

                                        <!-- /.box-body -->
                                        <div class="box-footer">
                                            <button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                                            <input type="submit"  class="btn btn-primary pull-right" value="Submit Request">
                                        </div>
                                    </div>
                                </div>
                                @if(Session('success_application'))
                                    @include('Vehicles.sucess.success_action', ['modal_title' => "Application Successful!", 'modal_content' => session('success_application')])
                                @endif
                            </div>

                        @endsection
                        @section('page_script')
                            <!-- Select2 -->
                                <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
                                <!-- bootstrap datepicker -->
                                <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>

                                <!-- InputMask -->
                                <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
                                <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
                                <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

                                <script src="/bower_components/bootstrap_fileinput/js/plugins/canvas-to-blob.min.js" type="text/javascript"></script>
                                <!-- the main fileinput plugin file -->
                                <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
                                <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
                                <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
                                <script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
                                <!-- the main fileinput plugin file -->
                                <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>


                                <!-- Date rane picker -->
                                <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
                                <script src="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.js"></script>

                                <!-- iCheck -->
                                <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

                                <!-- Ajax dropdown options load -->
                                <script src="/custom_components/js/load_dropdown_options.js"></script>
                                <!-- Date picker -->
                                <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
                                <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>

                                <!-- Ajax form submit -->
                                <script src="/custom_components/js/modal_ajax_submit.js"></script>

                                <!--        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>-->
                                <!--    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->

                                <script type="text/javascript">
                                    $(function() {
                                        //Initialize Select2 Elements
                                        $(".select2").select2();
                                        //Phone mask
                                        $("[data-mask]").inputmask();
										$('#back_button').click(function () {
														location.href = '/vehicle_management/vehicle_request';
													});
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

                                        //Show success action modal
                                        $('#success-action-modal').modal('show');
                                    });
                                </script>
@endsection