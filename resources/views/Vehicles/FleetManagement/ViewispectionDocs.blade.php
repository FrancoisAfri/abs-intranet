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
                    <h3 class="box-title">Vehicle Inspection Documents</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                <strong class="lead">Vehicle Details</strong><br>

                                @if(!empty($vehiclemaker))
                                    | &nbsp; &nbsp; <strong>Vehicle Make:</strong> <em>{{ $vehiclemaker->name }}</em>
                                    &nbsp;
                                    &nbsp;
                                @endif
                                @if(!empty($vehiclemodeler))
                                    -| &nbsp; &nbsp; <strong>Vehicle Model:</strong>
                                    <em>{{ $vehiclemodeler->name }}</em>
                                    &nbsp; &nbsp;
                                @endif
                                @if(!empty($vehicleTypes))
                                    -| &nbsp; &nbsp; <strong>Vehicle Type:</strong> <em>{{ $vehicleTypes->name }}</em>
                                    &nbsp;
                                    &nbsp;
                                @endif
                                @if(!empty($ispection->vehicle_registration))
                                    -| &nbsp; &nbsp; <strong>Vehicle Registration:</strong>
                                    <em>{{ $ispection->vehicle_registration }}</em> &nbsp; &nbsp;
                                @endif
                                @if(!empty($ispection->year))
                                    -| &nbsp; &nbsp; <strong>Year:</strong> <em>{{ $ispection->year }}</em> &nbsp;
                                    &nbsp;
                                @endif
                                @if(!empty($ispection->vehicle_color))
                                    -| &nbsp; &nbsp; <strong>Vehicle Color:</strong>
                                    <em>{{ $ispection->vehicle_color }}</em> &nbsp; &nbsp; -|
                                @endif

                            </p>
                        </div>
                    </div>
                    <!--  -->
                    <table class="table table-striped table-bordered">
                        <tr>

                            <td class="caption">Inspection In</td>

                            <td class="caption">Inspection Out</td>

                        </tr>
                        <tr>

                            <td class="caption">
                                <div class="form-group{{ $errors->has('document') ? ' has-error' : '' }}">
                                    <label for="end_mileage_id" class="col-sm-2 control-label">Inpection Documents
                                        Collect </label>
                                    <div class="col-sm-8">
                                        @if(!empty($booking->collectDoc))
                                            <a class="btn btn-default btn-flat btn-block pull-right "
                                               href="{{  (!empty($vehiclecollectdocuments)) ? Storage::disk('local')->url("projects/collectiondocuments/$vehiclecollectdocuments") : '' }}"
                                               target="_blank"><i
                                                        class="fa fa-file-pdf-o"></i> View Document</a>
                                        @else
                                            <a class="btn btn-default pull-centre "><i
                                                        class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group{{ $errors->has('document') ? ' has-error' : '' }}">
                                    <label for="end_mileage_id" class="col-sm-2 control-label">Inpection Documents
                                        Return </label>
                                    <div class="col-sm-8">
                                        @if(!empty($booking->returnDoc))
                                            <a class="btn btn-default btn-flat btn-block pull-right "
                                               href="{{ $booking->returnDoc}}" target="_blank"><i
                                                        class="fa fa-file-pdf-o"></i> View Document</a>
                                        @else
                                            <a class="btn btn-default pull-centre "><i
                                                        class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="product-img">
                                    <img src="{{ (!empty($vehiclecollectimage->image)) ? Storage::disk('local')->url("image/$vehiclecollectimage->image") : 'http://placehold.it/60x50' }}"
                                  alt="Product Image" width="75" height="50">
                                </div>
                            </td>

                            <td>
                               <div class="product-img">
                                    <img src="{{ (!empty($vehiclereturnimages->image)) ? Storage::disk('local')->url("image/$vehiclereturnimages->image") : 'http://placehold.it/60x50' }}"
                                  alt="Product Image" width="75" height="50">
                                </div>
                            </td>
                        </tr>


                    </table>
                    <!--   </div> -->
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn btn-default pull-left" id="back_button">Back</button>

                        </button>
                    </div>
                </div>
            </div>


        </div>


        @endsection

        @section('page_script')
            <script src="/custom_components/js/modal_ajax_submit.js"></script>
            <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
            <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
            <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
            <!-- iCheck -->
            <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
            <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
            <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
            <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
            <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
            <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js"
                    type="text/javascript"></script>
            <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
            <script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js"
                    type="text/javascript"></script>
            <!-- the main fileinput plugin file -->
            <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
            <!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
            <script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
            <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

            <!-- InputMask -->
            <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
            <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
            <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
            <script>
                function postData(id, data) {
                    if (data == 'actdeac') location.href = "/vehicle_management/policy_act/" + id;

                }

                $('#back_button').click(function () {
                    location.href = '/vehicle_management/bookin_log/{{ $vehicleID }}';
                });


                var moduleId;
                //Initialize Select2 Elements
                $(".select2").select2();
                $('.zip-field').hide();


                //Tooltip

                $('[data-toggle="tooltip"]').tooltip();

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

                //

                $(".js-example-basic-multiple").select2();

                $(document).ready(function () {

                    $('#nxt_service_date').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });


                    $('#date_serviced').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });

                    //
                    $('#nxtservice_date').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });


                    $('#dateserviced').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });


                });


                //Post perk form to server using ajax (add)
                $('#add_servicedetails').on('click', function () {
                    var strUrl = '/vehicle_management/addservicedetails';
                    var formName = 'add-servicedetails-form';
                    var modalID = 'add-servicedetails-modal';
                    var submitBtnID = 'add_servicedetails';
                    var redirectUrl = '/vehicle_management/service_details/{{ $ispection->id }}';
                    var successMsgTitle = 'New Record Added!';
                    var successMsg = 'The Record  has been updated successfully.';
                    modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                });


                var serviceID;
                $('#edit-servicedetails-modal').on('show.bs.modal', function (e) {
                    var btnEdit = $(e.relatedTarget);
                    serviceID = btnEdit.data('id');
                    var invoice_number = btnEdit.data('invoice_number');
                    var total_cost = btnEdit.data('total_cost');
                    var dateserviced = btnEdit.data('dateserviced');
                    var garage = btnEdit.data('garage');
                    var nxtservice_date = btnEdit.data('nxtservicedate');
                    var nxt_service_km = btnEdit.data('nxt_service_km');
                    var description = btnEdit.data('description');
                    var documents = btnEdit.data('documents');
                    var documents1 = btnEdit.data('documents1');
                    var valueID = btnEdit.data('valueID');
                    var modal = $(this);
                    modal.find('#invoice_number').val(invoice_number);
                    modal.find('#total_cost').val(total_cost);
                    modal.find('#dateserviced').val(dateserviced);
                    modal.find('#garage').val(garage);
                    modal.find('#nxtservice_date').val(nxtservice_date);
                    modal.find('#nxt_service_km').val(nxt_service_km);
                    modal.find('#description').val(description);
                    modal.find('#documents').val(documents);
                    modal.find('#documents1').val(documents1);
                    modal.find('#valueID').val(valueID);
                });

                $('#edit_servicedetails').on('click', function () {
                    var strUrl = '/vehicle_management/edit_servicedetails/' + serviceID;
                    var formName = 'edit-servicedetails-form';
                    var modalID = 'edit-servicedetails-modal';
                    var submitBtnID = 'edit_servicedetails';
                    var redirectUrl = '/vehicle_management/service_details/{{ $ispection->id }}';
                    var successMsgTitle = 'New Record Added!';
                    var successMsg = 'The Record  has been updated successfully.';
                    var Method = 'PATCH'
                    modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
                });


            </script>
@endsection
