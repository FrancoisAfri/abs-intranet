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
                    <h3 class="box-title"> Vehicle Notes  </h3>
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
                                <th>Date Captured</th>
                                <th>Captured By </th>
                                <th>Notes</th>
                                <th style="width: 5px; text-align: center;">Attachment</th>
                                <th style="width: 5px; text-align: center;"></th>
                            </tr>
                            @if (count($vehiclenotes) > 0)
                              @foreach ($vehiclenotes as $document)
                               <tr id="categories-list">
                                 <td nowrap>
                                       <button document="button" id="edit_compan" class="btn btn-warning  btn-xs" data-toggle="modal" data-target="#edit-newdoc-modal" data-id="{{ $document->id }}" data-captured_by="{{ date(' d M Y', $document->date_captured) }}"
                                        data-date_captured="{{ $document->date_captured }}" data-notes="{{ $document->notes }}" ><i class="fa fa-pencil-square-o"></i> Edit</button>
                                        
                                   </td>
                               
                                    
                                    <td>{{ !empty($document->captured_by) ?  $document->captured_by : '' }}</td>
                                    <td>{{ !empty($document->date_captured) ? date(' d M Y', $document->date_captured) : '' }}</td>
                                    <td>{{ !empty($document->notes) ?  $document->notes : '' }}</td>
                                     <td nowrap>
                                        <div class="form-group{{ $errors->has('documents') ? ' has-error' : '' }}">
                                            <label for="documents" class="control-label"></label>
                                            @if(!empty($document->documents))
                                            <a class="btn btn-default btn-flat btn-block pull-right btn-xs" href="{{ $document->documents }}" target="_blank"><i class="fa fa-file-pdf-o"></i>  View Document</a>
                                            @else
                                            <a class="btn btn-default pull-centre btn-xs"><i class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
                                            @endif
                                        </div>
                                    </td>
                                    <!--  <td>{{ !empty($document->documents) ?  $document->documents : '' }}</td> -->
                                    
                                    

                                     <td><button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#delete-contact-warning-modal"><i class="fa fa-trash"></i> Delete</button></td>
                                    
                                </tr>
                                   @endforeach
                               @else
                               <tr id="categories-list">
                        <td colspan="7">
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
                     <button type="button" id="cat_module" class="btn btn-warning pull-right" data-toggle="modal" data-target="#add-note-modal">Upload new Notes</button>
                    </div>
             </div>
        </div>
   <!-- Include add new prime rate modal -->
        @include('Vehicles.partials.upload_newnote_modal')
        @include('Vehicles.partials.edit_notes_modal')
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

                $('#date_captured').datepicker({
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
        $('#add_notes').on('click', function () {
            var strUrl = '/vehicle_management/add_new_note';
            var formName = 'add-note-form';
            var modalID = 'add-note-modal';
            var submitBtnID = 'add_notes';
            var redirectUrl = '/vehicle_management/notes/{{ $maintenance->id }}';
            var successMsgTitle = 'New Note  Added!';
            var successMsg = 'The Note  has been updated successfully.';
            modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });

       // });

       var noteID; 
       $('#edit-newdoc-modal').on('show.bs.modal', function (e) {
                    //console.log('kjhsjs');
                var btnEdit = $(e.relatedTarget);
                noteID = btnEdit.data('id');
                var captured_by = btnEdit.data('captured_by');
                var date_captured = btnEdit.data('date_captured');
                var notes = btnEdit.data('notes');
                var documents = btnEdit.data('documents');
                var modal = $(this);
                modal.find('#captured_by').val(captured_by);
                modal.find('#date_captured').val(date_captured);
                modal.find('#notes').val(notes);
                modal.find('#documents').val(documents);
               
             });

   
            //Post perk form to server using ajax (edit)
            $('#edit_newdoc').on('click', function() {
                var strUrl = '/vehicle_management/edit_newdoc/' + noteID;
                var formName = 'edit-newdoc-form';
                 var modalID = 'edit-newdoc-modal';
                var submitBtnID = 'edit_newdoc';
                var redirectUrl = '/vehicle_management/permits_licences/{{$maintenance->id}}';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The  details have been updated successfully!';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });



    </script>
@endsection
