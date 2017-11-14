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
                    <h3 class="box-title"> Documents  </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- <form class="form-horizontal" method="POST" action="/hr/document"> -->
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                    <strong class="lead">Vehicle Details</strong><br>
                                    
                                    @if(!empty($vehiclemaker))
                                        | &nbsp; &nbsp; <strong>Vehicle Make:</strong> <em>{{ $vehiclemaker }}</em> &nbsp; &nbsp;
                                    @endif
                                    @if(!empty($vehiclemodeler))
                                        -| &nbsp; &nbsp; <strong>Vehicle Model:</strong> <em>{{ $vehiclemodeler }}</em> &nbsp; &nbsp;
                                    @endif
                                    @if(!empty($vehicleTypes))
                                        -| &nbsp; &nbsp; <strong>Vehicle Type:</strong> <em>{{ $vehicleTypes }}</em> &nbsp; &nbsp;
                                    @endif
                                    @if(!empty($maintenance->vehicle_registration))
                                        -| &nbsp; &nbsp; <strong>Vehicle Registration:</strong> <em>{{ $maintenance->vehicle_registration }}</em> &nbsp; &nbsp;
                                    @endif
                                    @if(!empty($maintenance->year))
                                        -| &nbsp; &nbsp; <strong>Year:</strong> <em>{{ $maintenance->year }}</em> &nbsp; &nbsp;
                                    @endif
                                     @if(!empty($maintenance->vehicle_color))
                                        -| &nbsp; &nbsp; <strong>Vehicle Color:</strong> <em>{{ $maintenance->vehicle_color }}</em> &nbsp; &nbsp; -|
                                    @endif
                                    
                                </p>
                            </div>
                        </div>
                        <table class="table table-bordered">
                            <tr>
                               <th style="width: 10px; text-align: center;"></th>
                                <th style="width: 5px; text-align: center;"></th>
                                <th>Description</th>
                                <th>Date Uploaded</th>
                                <th>Date From</th>
                                <th>Expiry Date</th>
                                <th style="width: 5px; text-align: center;"></th>
                            </tr>
                            @if (count($vehicleDocumets) > 0)
                              @foreach ($vehicleDocumets as $document)
                               <tr id="categories-list">
                                 <td nowrap>
                                       <button document="button" id="edit_compan" class="btn btn-warning  btn-xs" data-toggle="modal" data-target="#edit-newdoc-modal" data-id="{{ $document->id }}" data-type="{{ $document->type }}"
                                        data-description="{{ $document->description }}" data-role="{{ $document->role }}" data-datefrom="{{ $document->date_from }}" data-exp_date="{{ $document->exp_date }}" 
                                        ><i class="fa fa-pencil-square-o"></i> Edit</button>
                                   </td>
                                <td nowrap>
                                        <div class="form-group{{ $errors->has('document') ? ' has-error' : '' }}">
                                            <label for="document" class="control-label"></label>
                                            @if(!empty($document->document))
                                            <a class="btn btn-default btn-flat btn-block pull-right btn-xs" href="{{ $document->document }}" target="_blank"><i class="fa fa-file-pdf-o"></i>  View Document</a>
                                            @else
                                            <a class="btn btn-default pull-centre btn-xs"><i class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ !empty($document->description) ? $document->description : ''}}</td>
                                    <td>{{ !empty($document->upload_date) ? date(' d M Y', $document->upload_date) : '' }}</td>
                                    <td>{{ !empty($document->date_from) ? date(' d M Y', $document->date_from) : '' }}</td>
                                    <td>{{ !empty($document->exp_date) ? date(' d M Y', $document->exp_date) : '' }}</td>
                                    

                                     <td><button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#delete-contact-warning-modal"><i class="fa fa-trash"></i> Delete</button></td>
                                    
                                </tr>
                                   @endforeach
                               @else
                               <tr id="categories-list">
                        <td colspan="6">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                           No key records for this vehicle, please start by adding key records for this vehicle..
                        </div>
                        </td>
                        </tr>
                        @endif
                            </table>
                      <!--   </div> -->
                                   <!-- /.box-body -->
                    <div class="box-footer">
                     <button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                     <button type="button" id="cat_module" class="btn btn-warning pull-right" data-toggle="modal" data-target="#add-document-modal">Upload new document</button>
                    </div>
             </div>
        </div>
   <!-- Include add new prime rate modal -->
        @include('Vehicles.partials.upload_newdocument_modal')
        @include('Vehicles.partials.edit_newdocument_modal')
          <!-- Include delete warning Modal form-->
     
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
       function postData(id , data ){   
            if(data == 'actdeac') location.href = "/vehice/fleetcard_act/" + id; 
          
        }
         $('#back_button').click(function () {
            location.href = '/vehicle_management/viewdetails/{{ $maintenance->id }}';
        });

        $(function () {
             $('#back_button').click(function () {
            location.href = '/vehicle_management/viewdetails/{{ $maintenance->id }}';
        });
            var moduleId;
            //Initialize Select2 Elements
           $(".select2").select2();
           $('.zip-field').hide();
           $('.sex-field').hide();


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
            $(window).on('resize', function() {
                $('.modal:visible').each(reposition);
            });

            //Show success action modal
            $('#success-action-modal').modal('show');
    
            //

            $(".js-example-basic-multiple").select2();

            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });

            $(function() {
                    $('img').on('click', function() {
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

                $('#date_from').datepicker({
                    format: 'dd/mm/yyyy',
                    autoclose: true,
                    todayHighlight: true
                });

            });

            $('#exp_date').datepicker({
                    format: 'dd/mm/yyyy',
                    autoclose: true,
                    todayHighlight: true
                });

            });




            $('#rdo_single, #rdo_bulke').on('ifChecked', function () {
                var allType = hideFields();
                if (allType == 1) $('#box-subtitle').html('Site Address');
                else if (allType == 2) $('#box-subtitle').html('Temo Site Address');
            });


            function hideFields() {
                var allType = $("input[name='upload_type']:checked").val();
                if (allType == 1) {
                    $('.zip-field').hide();
                    $('.user-field').show();
                }
                else if (allType == 2) {
                    $('.user-field').hide();
                    $('.zip-field').show();
                }
                return allType;
            }

            function changetextbox() {
                var levID = document.getElementById("key_status").value;
                    if (levID == 1) {
                    $('.sex-field').hide();
                    // $('.Sick-field').show();
              } 
            }    
            

            //Post perk form to server using ajax (add)
        $('#add_document').on('click', function () {
            var strUrl = '/vehicle_management/add_new_document';
            var formName = 'add-document-form';
            var modalID = 'add-document-modal';
            var submitBtnID = 'add_document';
            var redirectUrl = '/vehicle_management/document/{{ $maintenance->id }}';
            var successMsgTitle = 'New Documents Details Added!';
            var successMsg = 'The Documents Details has been updated successfully.';
            modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });


    </script>
@endsection
