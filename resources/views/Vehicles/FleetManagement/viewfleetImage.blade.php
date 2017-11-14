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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- year picker -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css"
          rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script> </head>
@endsection
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h4 class="box-title"></h4>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>

                <div align="center" class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title"> Image(s)

                        </h3>
                    </div>
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <div class="box-body">

                        <div class="row">
                            <div class="col-sm-12">
                                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                    <strong class="lead">Vehicle Details</strong><br>

                                    @if(!empty($vehiclemaker))
                                        | &nbsp; &nbsp; <strong>Vehicle Make:</strong> <em>{{ $vehiclemaker }}</em>
                                        &nbsp; &nbsp;
                                    @endif
                                    @if(!empty($vehiclemodeler))
                                        -| &nbsp; &nbsp; <strong>Vehicle Model:</strong> <em>{{ $vehiclemodeler }}</em>
                                        &nbsp; &nbsp;
                                    @endif
                                    @if(!empty($vehicleTypes))
                                        -| &nbsp; &nbsp; <strong>Vehicle Type:</strong> <em>{{ $vehicleTypes }}</em>
                                        &nbsp; &nbsp;
                                    @endif
                                    @if(!empty($maintenance->vehicle_registration))
                                        -| &nbsp; &nbsp; <strong>Vehicle Registration:</strong>
                                        <em>{{ $maintenance->vehicle_registration }}</em> &nbsp; &nbsp;
                                    @endif
                                    @if(!empty($maintenance->year))
                                        -| &nbsp; &nbsp; <strong>Year:</strong> <em>{{ $maintenance->year }}</em> &nbsp;
                                        &nbsp;
                                    @endif
                                    @if(!empty($maintenance->vehicle_color))
                                        -| &nbsp; &nbsp; <strong>Vehicle Color:</strong>
                                        <em>{{ $maintenance->vehicle_color }}</em> &nbsp; &nbsp; -|
                                    @endif

                                </p>
                            </div>
                        </div>
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th style="width: 10px; text-align: center;"></th>
                                <th style="width: 5px; text-align: center;">Image</th>
                                <th>Description</th>
                                <th>Date Uploaded</th>
                                <th> Registration</th>
                                <th>Uploaded By</th>
                                <th style="width: 5px; text-align: center;"></th>
                            </tr>
                            @if (count($vehiclemaintenance) > 0)
                                @foreach ($vehiclemaintenance as $vehiclemaintenance)
                                    <tr id="categories-list">
                                        <td nowrap>
                                            <button type="button" id="edit_compan" class="btn btn-default  btn-xs"
                                                    data-toggle="modal" data-target="#edit-package-modal"
                                                    data-id="{{ $vehiclemaintenance->id }}"
                                                    data-image="{{ $vehiclemaintenance->image }}"><i
                                                        class="fa fa-pencil-square-o"></i> Edit
                                            </button>
                                        </td>
                                        <td>


                                            <div id="my_div" class="hidden">
                                                <a href="{{ '/vehicle_management/viewImage/' . $vehiclemaintenance->id }}"
                                                   id="edit_compan" class="btn btn-default  btn-xs"
                                                   data-id="{{ $vehiclemaintenance->id }}">image</a>
                                            </div>


                                        </td>
                                        <td>
                                            <div class="product-img">
                                                <img src="{{ (!empty($vehiclemaintenance->image)) ? Storage::disk('local')->url("image/$vehiclemaintenance->image") : 'http://placehold.it/60x50' }}"
                                                     alt="Product Image" width="50" height="50">
                                            </div>


                                            <div class="modal fade" id="enlargeImageModal" tabindex="-1" role="dialog"
                                                 aria-labelledby="enlargeImageModal" aria-hidden="true">
                                                <!--  <div class="modal-dialog modal" role="document"> -->
                                                <div class="modal-dialog modal-sm">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close"><span aria-hidden="true">x</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <img src="" class="enlargeImageModalSource"
                                                                 style="width: 100%;">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td>{{ !empty($vehiclemaintenance->currentDate) ? date(' d M Y', $vehiclemaintenance->currentDate) : '' }}</td>
                                        <td>{{ !empty($vehiclemaintenance->vehicle_registration) ? $vehiclemaintenance->vehicle_registration : ''}}</td>

                                        <td>{{ !empty($vehiclemaintenance->currentDate) ? $vehiclemaintenance->currentDate : ''}}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr id="categories-list">
                                    <td colspan="9">
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                                &times;
                                            </button>
                                            No Fleet to display, please start by adding a new Fleet..
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </table>
                        <div class="box-footer">
                            <button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                            <button type="button" id="cat_module" class="btn btn-warning pull-right" data-toggle="modal"
                                    data-target="#upload-image-modal">Upload new Image
                            </button>
                        </div>
                        @include('Vehicles.partials.edit_image_modal')
                        @include('Vehicles.partials.upload_newImage_modal')

                    </div>
                </div>
            </div>
            @endsection
            @section('page_script')
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

                <script src="/custom_components/js/modal_ajax_submit.js"></script>



                <script>

                    $('#back_button').click(function () {
                        location.href = '/vehicle_management/viewdetails/{{ $maintenance->id }}';
                    });
                    $(function () {
                        $(".select2").select2();
                        $('.zip-field').hide();

                        var moduleId;
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

                        //
                        $('.datepicker').datepicker({
                            format: 'dd/mm/yyyy',
                            autoclose: true,
                            todayHighlight: true
                        });

                        $(function () {
                            $('img').on('click', function () {
                                $('.enlargeImageModalSource').attr('src', $(this).attr('src'));
                                $('#enlargeImageModal').modal('show');
                            });
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


                        //Post perk form to server using ajax (add)
                        $('#add-vehicle_image').on('click', function () {

                            var strUrl = '/vehicle_management/add_images';
                            var formName = 'add-new-vehicleImage-form';
                            var modalID = 'upload-image-modal';
                            //var modal = $('#'+modalID);
                            var submitBtnID = 'add-vehicle_image';
                            var redirectUrl = '/vehicle_management/viewImage/{{ $ID}}';
                            var successMsgTitle = 'Image Added!';
                            var successMsg = 'The Image  has been updated successfully.';
                            modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                        });


                        var ImageID;
                        $('#edit-package-modal').on('show.bs.modal', function (e) {
                            //console.log('kjhsjs');
                            var btnEdit = $(e.relatedTarget);
                            ImageID = btnEdit.data('id');
                            var name = btnEdit.data('name');
                            var description = btnEdit.data('description');
                            var images = btnEdit.data('images');
                            var modal = $(this);
                            modal.find('#name').val(name);
                            modal.find('#description').val(description);
                            modal.find('#images').val(images);
                        });

                        // //Post perk form to server using ajax (add)
                        //     $('#edit_image').on('click', function () {

                        //         var strUrl = '/vehicle_management/edit_images/' + ImageID;
                        //         var formName = 'add-new-vehicleImage-form';
                        //         var modalID = 'edit-image-modal';
                        //         //var modal = $('#'+modalID);
                        //         var submitBtnID = 'edit_image';
                        //         var redirectUrl = '/vehicle_management/viewImage/{{ $ID}}';
                        //         var successMsgTitle = 'Image Modified!';
                        //         var successMsg = 'The Image  has been updated successfully.';
                        //         var Method = 'PATCH';
                        //         modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
                        //     });

                        $('#edit_image').on('click', function () {
                            var strUrl = '/vehicle_management/edit_images/' + ImageID;
                            var modalID = 'edit-image-modal';
                            var objData = {
                                name: $('#' + modalID).find('#name').val(),
                                description: $('#' + modalID).find('#description').val(),
                                images: $('#' + modalID).find('#images').val(),
                                _token: $('#' + modalID).find('input[name=_token]').val()
                            };
                            var submitBtnID = 'edit_image';
                            var redirectUrl = '/vehicle_management/viewImage/{{ $ID}}';
                            var successMsgTitle = 'Image Modified!';
                            var successMsg = 'The Image  has been updated successfully.';
                            var Method = 'PATCH'
                            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
                        });


                    });
                </script>
@endsection
