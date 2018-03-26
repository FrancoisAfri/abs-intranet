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
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Add Vehicle Details</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body"><!-- style="max-height: 200px; overflow-y: scroll;" -->
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 10px; text-align: center;"></th>
                            <th style="width: 5px; text-align: center;">Image</th>
                            <th>Vehicle Model/Year</th>
                            <th>Fleet Number</th>
                            <th>Vehicle Registration</th>
                            <th>VIN Numberr</th>
                            <th>Engine Number</th>
                            <th>Odometer/Hours</th>
                            <th>Company</th>
                            <th>Department</th>
                            <th style="width: 5px; text-align: center;"></th>
                        </tr>
                        @if (count($vehiclemaintenance) > 0)
                            @foreach ($vehiclemaintenance as $card)
                                <tr id="categories-list">
                                    <td>
                                        <a href="{{ '/vehicle_management/viewdetails/' . $card->id }}"
                                           id="edit_compan" class="btn btn-default  btn-xs"
                                           data-id="{{ $card->id }}">View</a>

                                        <div id="my_div" class="hidden">
                                            <a href="{{ '/vehicle_management/viewImage/' . $card->id }}"
                                               id="edit_compan" class="btn btn-default  btn-xs"
                                               data-id="{{ $card->id }}">image</a>
                                        </div>
                                        <div id="my_div" class="hidden">
                                            <a href="http://www.google.com">fuel log</a>
                                        </div>
                                        <div id="my_div" class="hidden">
                                            <a href="http://www.google.com">oil log</a>
                                        </div>
                                        <div id="my_div" class="hidden">
                                            <a href="http://www.google.com">incident</a>
                                        </div>
                                        <div id="my_div" class="hidden">
                                            <a href="http://www.google.com">fines</a>
                                        </div>


                                    </td>
                                    <td>
                                        <div class="product-img">
                                            <img src="{{ (!empty($card->image)) ? Storage::disk('local')->url("Vehicle/image/$card->image") : 'http://placehold.it/50x50' }}"
                                                 alt="Product Image" width="50" height="50">
                                        </div>
                                    </td>
                                    {{--<td>{{ (!empty( $card->image)) ?  $card->image : ''}} </td>--}}
                                    <td>{{ !empty($card->vehicle_model . ' ' . $card->year ) ? $card->vehicle_model  . ' ' . $card->year: ''}}</td>
                                    <td></td>
                                    <td>{{ !empty($card->vehicle_registration) ? $card->vehicle_registration : ''}}</td>
                                    <td></td>
                                    <td>{{ !empty($card->engine_number) ? $card->engine_number : ''}}</td>
                                    <td>{{ !empty($card->odometer_reading . ' ' . $card->hours_reading ) ? $card->odometer_reading  . ' ' . $card->hours_reading: ''}}</td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <!--   leave here  -->
                                        <button vehice="button" id="view_ribbons"
                                                class="btn {{ (!empty($card->status) && $card->status == 1) ? " btn-danger " : "btn-success " }}
                                                        btn-xs" onclick="postData({{$card->id}}, 'actdeac');"><i
                                                    class="fa {{ (!empty($card->status) && $card->status == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($card->status) && $card->status == 1) ? "De-Activate" : "Activate"}}
                                        </button>
                                    </td>

                                </tr>
                            @endforeach
                        @else
                            <tr id="categories-list">
                                <td colspan="10">
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        No Record to display, please start by adding a new Record ..
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </table>
                    <!--   </div> -->
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn btn-warning pull-left" id="back_button"><i
                                    class="fa fa-arrow-left"></i> Back
                        </button>

                        <button type="button" id="cat_module" class="btn btn-warning pull-right" data-toggle="modal"
                                data-target="#add-vehicledetails-modal">Add New Vehicle
                        </button>

                    </div>
                </div>
                <!-- </form> -->
            </div>


            @include('Vehicles.FleetManagement.partials.add_vehicleDetails_modal')
            @include('Vehicles.partials.add_vehicledetails_modal')
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

            <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
            <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js"
                    type="text/javascript"></script>
            <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->

            <!-- the main fileinput plugin file -->
            <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>

            <!-- iCheck -->
            <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

            <!-- Ajax dropdown options load -->
            <script src="/custom_components/js/load_dropdown_options.js"></script>
            <!-- Ajax form submit -->
            <script src="/custom_components/js/modal_ajax_submit.js"></script>
            <script type="text/javascript">
                $(function () {
                    $(".select2").select2();
                    $('.hours-field').hide();
                    $('.comp-field').hide();
                    var moduleId;
                    //Tooltip
                    $('[data-toggle="tooltip"]').tooltip();

                    //Vertically center modals on page

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

                $('.datepicker').datepicker({
                    format: 'dd/mm/yyyy',
                    autoclose: true,
                    todayHighlight: true
                });

                //Initialize iCheck/iRadio Elements
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '10%' // optional
                });

                $(document).ready(function () {

                    $('#year').datepicker({
                        minViewMode: 'years',
                        autoclose: true,
                        format: 'yyyy'
                    });

                });

                $('#rdo_package, #rdo_product').on('ifChecked', function () {
                    var allType = hideFields();
                    if (allType == 1) $('#box-subtitle').html('Site Address');
                    else if (allType == 2) $('#box-subtitle').html('Temo Site Address');
                });

                //

                $('#rdo_fin, #rdo_comp').on('ifChecked', function () {
                    var allType = hidenFields();
                    if (allType == 1) $('#box-subtitle').html('Financial Institutions');
                    else if (allType == 2) $('#box-subtitle').html('Company');
                });


                function hideFields() {
                    var allType = $("input[name='promotion_type']:checked").val();
                    if (allType == 1) {
                        $('.hours-field').hide();
                        $('.odometer-field').show();
                    }
                    else if (allType == 2) {
                        $('.odometer-field').hide();
                        $('.hours-field').show();
                    }
                    return allType;
                }

                //
                function hidenFields() {
                    var allType = $("input[name='title_type']:checked").val();
                    if (allType == 1) {
                        $('.comp-field').hide();
                        $('.fin-field').show();
                    }
                    else if (allType == 2) {
                        $('.fin-field').hide();
                        $('.comp-field').show();
                    }
                    return allType;
                }

                //Post perk form to server using ajax (add)
                $('#add_vehicledetails').on('click', function () {
                    var strUrl = '/vehicle_management/add_vehicleDetails';
                    var formName = 'add-new-vehicledetails-form';
                    var modalID = 'add-vehicledetails-modal';
                    //var modal = $('#'+modalID);
                    var submitBtnID = 'add_vehicledetails';
                    var redirectUrl = '/vehicle_management/add_vehicle';
                    var successMsgTitle = 'Fleet Type Added!';
                    var successMsg = 'The Fleet Type has been updated successfully.';
                    modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                });


            </script>
@endsection
