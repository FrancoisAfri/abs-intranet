@extends('layouts.main_layout')
@section('page_dependencies')

    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>

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
                    <div class="box-body">
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
                                                    data-name="{{ $vehiclemaintenance->name }}"
                                                    data-description="{{ $vehiclemaintenance->description }}"
                                                    data-image="{{ $vehiclemaintenance->image }}"><i
                                                        class="fa fa-pencil-square-o"></i> Edit
                                            </button>
                                        </td>
                                        <td>
                                            <div class="product-img">
                                                <img src="{{ (!empty($vehiclemaintenance->image)) ? Storage::disk('local')->url("Vehicle/images/$vehiclemaintenance->image") : 'http://placehold.it/60x50' }}"
                                                     alt="Product Image" width="50" height="50">
                                            </div>


                                            <div class="modal fade" id="enlargeImageModal" tabindex="-1"
                                                         role="dialog" align="center"
                                                         aria-labelledby="enlargeImageModal" aria-hidden="true">
                                                        <!--  <div class="modal-dialog modal" role="document"> -->
                                                        <div class="modal-dialog modal-sm" >
                                                            {{--<div class="modal-content">--}}
                                                                {{--<div class="modal-header">--}}
                                                                    {{--<button type="button" class="close"--}}
                                                                            {{--data-dismiss="modal"--}}
                                                                            {{--aria-label="Close"><span aria-hidden="true">x</span>--}}
                                                                    {{--</button>--}}
                                                                {{--</div>--}}
                                                                <div class="modal-body" align="center">
                                                                    <img src="" class="enlargeImageModalSource"
                                                                         style="width: 200%;">

                                                                </div>
                                                            </div>
                                                        </div>
                                        </td>
                                        <td>{{ !empty($vehiclemaintenance->description) ? $vehiclemaintenance->description : ''}}</td>

                                        <td>{{ !empty($vehiclemaintenance->upload_date) ? date(' d M Y', $vehiclemaintenance->upload_date) : '' }}</td>
                                        <td>{{ !empty($maintenance->vehicle_registration) ? $maintenance->vehicle_registration : ''}}</td>

                                        <td>{{ !empty($vehiclemaintenance->first_name . ' ' . $vehiclemaintenance->surname ) ? $vehiclemaintenance->first_name . ' ' . $vehiclemaintenance->surname : ''}}</td>
                                    </tr>
                                @endforeach

                            @else
                                <tr id="categories-list">
                                    <td colspan="9">
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                                &times;
                                            </button>
                                            No Record to display, please start by adding a new Fleet..
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </table>
                        <div class="box-footer">
                            <button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                            <button type="button" id="cat_module" class="btn btn-warning pull-right" data-toggle="modal"
                                    data-target="#upload-image-modal">Upload New Image
                            </button>
                        </div>
                        @include('Vehicles.partials.edit_image_modal')
                        @include('Vehicles.partials.upload_newImage_modal')

                    </div>
                </div>
            </div>
            @endsection
            @section('page_script')



                <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js"
                        type="text/javascript"></script>
                <!-- the main fileinput plugin file -->
                <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
                <!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
                <script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>



                <script src="/custom_components/js/modal_ajax_submit.js"></script>
                <script>

                    $('#back_button').click(function () {
                        location.href = '/vehicle_management/viewdetails/{{ $maintenance->id }}';
                    });
                    $(function () {

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


                        $(function () {
                            $('img').on('click', function () {
                                $('.enlargeImageModalSource').attr('src', $(this).attr('src'));
                                $('#enlargeImageModal').modal('show');
                            });
                        });

                        //Initialize iCheck/iRadio Elements

//                        $(document).ready(function () {
//
//                            $('#year').datepicker({
//                                minViewMode: 'years',
//                                autoclose: true,
//                                format: 'yyyy'
//                            });
//
//                        });

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
                              if (parseInt(btnEdit.data('id')) > 0) {
                              ImageID = btnEdit.data('id');
                             }

                            var name = btnEdit.data('name');
                            var description = btnEdit.data('description');
                            var images = btnEdit.data('images');
                            var modal = $(this);
                            modal.find('#name').val(name);
                            modal.find('#description').val(description);
                            modal.find('#image').val(images);
                        });


                        $('#edit_image').on('click', function () {
                            var strUrl = '/vehicle_management/edit_images/' + ImageID;
                            var formName = 'edit-image-form';
                            var modalID = 'edit-package-modal';
                            var submitBtnID = 'edit_image';
                            var redirectUrl = '/vehicle_management/viewImage/{{ $maintenance->id }}';
                            var successMsgTitle = 'Image Modified!';
                            var successMsg = 'The Image  has been updated successfully.';
                            var Method = 'PATCH'
                            modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                        });


                    });
                </script>
@endsection