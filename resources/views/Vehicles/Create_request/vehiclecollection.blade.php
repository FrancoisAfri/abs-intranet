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
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-anchor pull-right"></i>
                    <h3 class="box-title">Confirm Booking collection</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form name="leave-application-form" class="form-horizontal" method="POST"
                      action="/vehicle_management/{{ $collect->id }}/confirmbooking"
                      enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}

                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                    <strong class="lead">Vehicle Details</strong><br>
                                    @if(!empty($vehiclemaker))
                                        | &nbsp; &nbsp; <strong>Vehicle Make:</strong><em>{{ $vehiclemaker->name }}</em>
                                        &nbsp;&nbsp;
                                    @endif
                                    @if(!empty($vehiclemodeler))
                                        -| &nbsp; &nbsp; <strong>Vehicle Model:</strong>
                                        <em>{{ $vehiclemodeler->name }}</em>&nbsp; &nbsp;
                                    @endif
                                    @if(!empty($vehicleTypes))
                                        -| &nbsp; &nbsp; <strong>Vehicle Type:</strong>
                                        <em>{{ $vehicleTypes->name }}</em> &nbsp;&nbsp;
                                    @endif
                                    @if(!empty($maintenance->vehicle_registration))
                                        -| &nbsp; &nbsp; <strong>Vehicle Registration:</strong>
                                        <em>{{ $vehiclebookings->vehicle_reg }}</em> &nbsp; &nbsp;
                                    @endif
                                    @if(!empty($maintenance->year))
                                        -| &nbsp; &nbsp; <strong>Year:</strong>
                                        <em>{{ $vehiclebookings->vehicle_reg->year }}</em> &nbsp;
                                        &nbsp;
                                    @endif
                                    @if(!empty($maintenance->vehicle_color))
                                        -| &nbsp; &nbsp; <strong>Vehicle Color:</strong>
                                        <em>{{ $vehiclebookings->vehicle_reg->vehicle_color }}</em> &nbsp; &nbsp; -|
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="box-body">
                            @if (count($errors) > 0)
                                <div class="alert alert-danger alert-dismissible fade in">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                        &times;
                                    </button>
                                    <h4><i class="icon fa fa-ban"></i> Invalid Input Data!</h4>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="path" class="col-sm-2 control-label">Vehicle Model</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-bullseye"></i>
                                        </div>
                                        <input type="text" id="vehiclemodel" class="form-control pull-left"
                                               name="vehiclemodel"
                                               value="{{  (!empty($vehiclemodeler->name)) ?  $vehiclemodeler->name : '' }}"
                                               readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="path" class="col-sm-2 control-label">Vehicle Reg. No</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-id-card-o"></i>
                                        </div>
                                        <input type="text" id="vehicle_reg" class="form-control pull-left"
                                               name="vehicle_reg"
                                               value="{{ $vehiclebookings->vehicle_reg }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="path" class="col-sm-2 control-label">Required From </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" id="require_datetime"
                                               class="form-control pull-left" name="require_datetime"
                                               value="{{ date("y F  Y, g:i a", $vehiclebookings->require_datetime) }}"
                                               readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="path" class="col-sm-2 control-label">Required To </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" id="return_datetime"
                                               class="form-control pull-left" name="return_datetime"
                                               value="{{ date("y F  Y, g:i a", $vehiclebookings->return_datetime) }}"
                                               readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="path" class="col-sm-2 control-label">Capturer </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user-o"></i>
                                        </div>
                                        <input type="text" id="capturer_id" class="form-control pull-left"
                                               name="capturer_id"
                                               value="{{  $vehiclebookings->capturer_id }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="path" class="col-sm-2 control-label"> VehicleDriver </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user-o"></i>
                                        </div>
                                        <input type="text" id="driver_id" class="form-control pull-left"
                                               name="driver_id"
                                               value="{{  $vehiclebookings->firstname . ' ' . $vehiclebookings->surname }}"
                                               readonly>
                                    </div>
                                </div>
                            </div>
							<div class="form-group">
								<label for="path" class="col-sm-2 control-label">{{isset($vehiclebookings) && $vehiclebookings->metre_reading_type === 1 ? "Odometer Reading" : "Hour Reading"}} </label>
								<div class="col-sm-8">
									<div class="input-group">
										<div class="input-group-addon">
											 <i class="fa fa-spinner"></i>
										</div>
										<input type="text" id="start_mileage_id" class="form-control pull-left"
											   name="start_mileage_id"
											   value="{{  (!empty($vehiclebookings->start_mileage_id)) ?  $vehiclebookings->start_mileage_id : '' }}"
											   readonly>
									</div>
								</div>
							</div>
                            <div class="form-group">
                                <label for="path" class="col-sm-2 control-label">Destination </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-car"></i>
                                        </div>
                                        <input type="text" id="destination" class="form-control pull-left"
                                               name="destination"
                                               value="{{  $vehiclebookings->destination }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="path" class="col-sm-2 control-label">Purpose </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-anchor"></i>
                                        </div>
                                        <input type="text" id="purpose" class="form-control pull-left"
                                               name="purpose" value="{{  $vehiclebookings->purpose }}"
                                               readonly>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                            </div>
                            <!-- /.box-footer -->
                            <div class="box-footer" style="text-align: center;">
                                <button type="button" id="cancel" class="btn btn-default pull-left">Cancel</button>
                                <button type="submit" name="command" id="update" class="btn btn-primary pull-right">
                                    Update
                                </button>
                                @if (isset($InforceVehiclerules) && $InforceVehiclerules->inforce_vehicle_image === 1)

                                    <button type="button" id="cat_module"
                                            class="btn btn-info btn-xs" data-toggle="modal"
                                            data-target="#add-image-modal">Inspection Images
                                    </button>

                                @endif
								@if (isset($InforceVehiclerules) && $InforceVehiclerules->inforce_vehicle_documents === 1)
                                    <button type="button" id="cat_module"
                                            class="btn btn-muted btn-xs " data-toggle="modal"
                                            data-target="#add-document-modal">Inspection Documents
                                    </button>
								@endif
                            </div>
                        </div>
                </form>
            </div>
        </div>
        <!-- /.box -->
    </div>
    <!-- End new User Form-->
    @include('Vehicles.Create_request.inspection_document_modal')
    @include('Vehicles.Create_request.inspection_image_modal')
    {{--@include('Vehicles.partials.edit_document_modal')--}}
    @if(Session('success_application'))
        @include('Vehicles.sucess.success_action', ['modal_title' => "Vehicle Collection successful!", 'modal_content' => session('success_application')])
    @endif
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

    <script src="/bower_components/bootstrap_fileinput/js/plugins/canvas-to-blob.min.js"
            type="text/javascript"></script>
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
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
            $('.zip-field').hide();
            //Cancel button click event
            $('#cancel').click(function () {
                location.href = '/vehicle_management/create_request';
            });

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

        //Initialize iCheck/iRadio Elements
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '10%' // optional
        });
        $('#rdo_single, #rdo_zip').on('ifChecked', function () {
            var allType = hideFields();
            if (allType == 1) $('#box-subtitle').html('Site Address');
            else if (allType == 2) $('#box-subtitle').html('Temo Site Address');
        });


        function hideFields() {
            var allType = $("input[name='image_type']:checked").val();
            if (allType == 1) {
                $('.zip-field').hide();
                $('.Single-field').show();
            }
            else if (allType == 2) {
                $('.Single-field').hide();
                $('.zip-field').show();
            }
            return allType;
        }

        $('#add_document').on('click', function () {
            var strUrl = '/vehicle_management/add_collectiondoc';
            var formName = 'add-document-form';
            var modalID = 'add-document-modal';
            var submitBtnID = 'add_document';
            var redirectUrl = '/vehicle_management/collect/{{ $collect->id}}';
            var successMsgTitle = 'Document Added!';
            var successMsg = 'The Document  has been updated successfully.';
            modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });

        //Post perk form to server using ajax (add)
        $('#add-collection_image').on('click', function () {
            var strUrl = '/vehicle_management/addcollectionImage';
            var formName = 'add-new-collectImage-form';
            var modalID = 'add-image-modal';
            var submitBtnID = 'add-collection_image';
            var redirectUrl = '/vehicle_management/collect/{{ $collect->id }}';
            var successMsgTitle = 'Image Added!';
            var successMsg = 'The Image  has been updated successfully.';
            modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });


    </script>
@endsection