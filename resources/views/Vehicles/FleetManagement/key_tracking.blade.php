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
                    <h3 class="box-title"> Key Details </h3>
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
                                <th style="width: 5px; text-align: center;"> Issued To</th>
                                <th>Employee</th>
                                <th>Safe Name</th>
                                <th> Safe Controller</th>
                                <th> Date Issued</th>
                                <th>Date Status Change</th>
                                <th>Issued By</th>
                                <th>Description</th>
                                <th>Key Status</th>
                            </tr>
                            @if (count($keytracking) > 0)
                              @foreach ($keytracking as $fleet)
                               <tr id="categories-list">
                               <td nowrap>
                                        <button vehice="button" id="edit_fleetcard" class="btn btn-warning  btn-xs" data-toggle="modal" data-target="#edit-package-modal" data-id="{{ $fleet->id }}" data-key_number="{{ $fleet->key_number }}" data-key_type="{{$fleet->key_type}}" data-key_status="{{$fleet->key_status}}" data-description="{{$fleet->description}}" data-date_issued="{{$fleet->date_issued}}"
                                          data-issued_by ="{{ $fleet->issued_by}}" data-employee ="{{ $fleet->employee}}"><i class="fa fa-pencil-square-o"></i> Edit</button>
                                    </td>
                                     <td>{{ (!empty( $fleet->firstname)) ?  $IssuedTo[$fleet->employee] : ''}} </td>


                                     <td>{{ (!empty( $fleet->firstname . ' ' . $fleet->surname)) ?   $fleet->firstname . ' ' . $fleet->surname : ''}} </td>
                                     <td>{{ (!empty( $fleet->safeName)) ?  $fleet->safeName : ''}} </td>
                                     <td>{{ (!empty( $fleet->safe_controller)) ?  $fleet->safe_controller : ''}} </td>
                                     <td>{{ !empty($fleet->date_issued) ? date(' d M Y', $fleet->date_issued) : '' }}</td>
                                     <td></td>
                                     <td>{{ (!empty( $fleet->issued_by)) ?  $fleet->issued_by : ''}} </td>
                                     <td>{{ (!empty( $fleet->description)) ?  $fleet->description : ''}} </td>
                                    <!--  <td>{{ (!empty( $fleet->key_status)) ?  $fleet->key_status : ''}} </td> -->

                                    <td>{{ (!empty($fleet->key_status)) ?  $keyStatus[$fleet->key_status] : ''}} </td>
                                    
                                </tr>
                                   @endforeach
                               @else
                               <tr id="categories-list">
                        <td colspan="5">
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
                     <button type="button" id="cat_module" class="btn btn-warning pull-right" data-toggle="modal" data-target="#add-key-modal">Add Key Details</button>
                    </div>
             </div>
        </div>
   <!-- Include add new prime rate modal -->
        @include('Vehicles.partials.add_key_modal')
        @include('Vehicles.partials.edit_key_modal')
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
            var moduleId;
            //Initialize Select2 Elements
           $(".select2").select2();
           $('.safe-field').hide();
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

                $('#date_issued').datepicker({
                    format: 'dd/mm/yyyy',
                    autoclose: true,
                    todayHighlight: true
                });

            });




            $('#rdo_user, #rdo_safe').on('ifChecked', function () {
                var allType = hideFields();
                if (allType == 1) $('#box-subtitle').html('Site Address');
                else if (allType == 2) $('#box-subtitle').html('Temo Site Address');
            });


            function hideFields() {
                var allType = $("input[name='key']:checked").val();
                if (allType == 1) {
                    $('.safe-field').hide();
                    $('.user-field').show();
                }
                else if (allType == 2) {
                    $('.user-field').hide();
                    $('.safe-field').show();
                }
                return allType;
            }

            function changetextbox() {
                var levID = document.getElementById("key_status").value;
                                                    // alert (levID);
                    if (levID == 1) {
                    $('.sex-field').hide();
                    // $('.Sick-field').show();
              } 
            }    
            //save Fleet
            //Post module form to server using ajax (ADD)
            $('#add-key-card').on('click', function() {
                //console.log('strUrl');
                var strUrl = '/vehicle_management/add_keys';
                var modalID = 'add-key-modal';
                var objData = {
                    date_issued: $('#'+modalID).find('#date_issued').val(),
                    key_number: $('#'+modalID).find('#key_number').val(),
                    key_type: $('#'+modalID).find('#key_type').val(),
                    key_status: $('#'+modalID).find('#key_status').val(),
                    description: $('#'+modalID).find('#description').val(),
                    key: $('#'+modalID).find('input:checked[name = key]').val(),
                    issued_by: $('#'+modalID).find('#issued_by').val(),
                    safe_name: $('#'+modalID).find('#safe_name').val(),
                    safe_controller: $('#'+modalID).find('#safe_controller').val(),
                    issued_to: $('#'+modalID).find('#issued_to').val(),
                    employee: $('#'+modalID).find('#employee').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                 var submitBtnID = 'add-key-card';
                var redirectUrl = '/vehicle_management/keys/{{ $maintenance->id }}';
                var successMsgTitle = 'Keys Added!';
                var successMsg = 'The Key Details  have been updated successfully..';
                //var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });



         //      var fleetID;
         //    $('#edit-package-modal').on('show.bs.modal', function (e) {
         //            //console.log('kjhsjs');
         //        var btnEdit = $(e.relatedTarget);
         //        fleetID = btnEdit.data('id');
         //        var name = btnEdit.data('name');
         //        var description = btnEdit.data('description');
         //        var modal = $(this);
         //        modal.find('#name').val(name);
         //        modal.find('#description').val(description);
         //     });
         //    $('#edit_fleetcard').on('click', function () {
         //        var strUrl = '/vehice/edit_fleetcard/' + fleetID;
         //        var modalID = 'edit-package-modal';
         //        var objData = {
         //            name: $('#'+modalID).find('#name').val(),
         //            description: $('#'+modalID).find('#description').val(),
         //            _token: $('#'+modalID).find('input[name=_token]').val()
         //        };
         //        var submitBtnID = 'save_category';
         //        var redirectUrl = '/vehicle_management/Manage_fleet_types';
         //        var successMsgTitle = 'Changes Saved!';
         //        var successMsg = 'The Fleet Type has been updated successfully.';
         //        var Method = 'PATCH';
         // modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
         //    });

        });
    </script>
@endsection
