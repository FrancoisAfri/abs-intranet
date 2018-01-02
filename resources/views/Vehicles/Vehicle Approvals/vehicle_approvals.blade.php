@extends('layouts.main_layout')
@section('page_dependencies')
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<!-- iCheck -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title"> Vehicle Approval  </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 10px; text-align: center;"></th>						  	 
                                <th>Vehicle Model/Year</th>
                                <th>Fleet Number</th>
                                <th>Vehicle Registration</th>
                                <thOdometer></th>
                                <th>Company</th>
                                <th>Department</th>
                                <th style="width: 5px; text-align: center;">Accept</th>
                                <th style="width: 5px; text-align: center;">Decline</th>
                            </tr>
                            @if (count($Vehiclemanagemnt) > 0)
                              @foreach ($Vehiclemanagemnt as $filling)
                               <tr id="categories-list">
                              
                                  <td nowrap>
                                            <div class="product-img">
                                                <img src="{{ (!empty($filling->image)) ? Storage::disk('local')->url("image/$filling->image") : 'http://placehold.it/60x50' }}"
                                                     alt="Product Image" width="75" height="50">
                                            </div>
                                            </td>
                                     <td>{{ (!empty( $filling->vehiclemodel . ' ' . $filling->year )) ?   $filling->vehiclemodel . ' ' . $filling->year : ''}} </td>
                                     <td>{{ (!empty( $filling->fleet_number)) ?  $filling->fleet_number : ''}} </td>
                                     <td>{{ (!empty( $filling->vehicle_registration)) ?  $filling->vehicle_registration : ''}} </td>
                                     <td>{{ (!empty( $filling->Department)) ?  $filling->Department : ''}} </td>
                                     <td>{{ (!empty( $filling->company)) ?  $filling->company : ''}} </td>
                                    <td>
                                    <input type="hidden" name="include_division_report" value="0">
                                    <input type="checkbox" name="include_division_report"
                                                           value="1" {{ $filling->status === 1 ? 'checked ="checked"' : 0 }} >
                                    </td>
                                    <td>
                                    <input type="hidden" name="include_division_report" value="0">
                                    <input type="checkbox" name="include_division_report"
                                                           value="1" {{ $filling->status === 1 ? 'checked ="checked"' : 0 }} >
                                    </td>
            
                                </tr>
                                   @endforeach
                               @else
                               <tr id="categories-list">
                        <td colspan="5">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            No Fleet Filling Station damage categories  to display, please start by adding a new Filling Station..
                        </div>
                        </td>
                        </tr>
                           @endif
                        </table>
                      <!--   </div> -->
                                   <!-- /.box-body -->
                    <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right"> Submit</button>
                                                
                     <!-- <button type="button" id="cat_module" class="btn btn-warning pull-right" data-toggle="modal" data-target="#add-station-modal">Add new  Filling Station </button> -->
                    </div>
             </div>
        </div>
   <!-- Include add new prime rate modal -->
       

</div>


@endsection

@section('page_script')
<script src="/custom_components/js/modal_ajax_submit.js"></script>
<!-- Select2 -->
<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
<!-- iCheck -->
<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

<script>
       function postData(id , data ){   
            if(data == 'actdeac') location.href = "/vehice/station_act/" + id;
          
        }
        $('#back_button').click(function () {
                location.href = '/vehicle_management/setup';
            });

        $(function () {
            var moduleId;
            //Initialize Select2 Elements
            $(".select2").select2();

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
             //Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '10%' // optional
            });

            $(".js-example-basic-multiple").select2();

            //save Fleet
            //Post module form to server using ajax (ADD)
            $('#add-filling-station').on('click', function() {
                //console.log('strUrl');
                var strUrl = '/vehice/add_fillingstation';
                var modalID = 'add-station-modal';
                var objData = {
                    name: $('#'+modalID).find('#name').val(),
                    description: $('#'+modalID).find('#description').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'cat_module';
                var redirectUrl = '/vehicle_management/fillingstaion';
                var successMsgTitle = 'Fleet Type Added!';
                var successMsg = 'The Fleet Type has been updated successfully.';
                //var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

              var fleetID;
            $('#edit-package-modal').on('show.bs.modal', function (e) {
                    //console.log('kjhsjs');
                var btnEdit = $(e.relatedTarget);
                fleetID = btnEdit.data('id');
                var name = btnEdit.data('name');
                var description = btnEdit.data('description');
                var modal = $(this);
                modal.find('#name').val(name);
                modal.find('#description').val(description);
             });
            $('#edit_station').on('click', function () {
                var strUrl = '/vehice/edit_station/' + fleetID;
                var modalID = 'edit-package-modal';
                var objData = {
                    name: $('#'+modalID).find('#name').val(),
                    description: $('#'+modalID).find('#description').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'edit_station';
                var redirectUrl = '/vehicle_management/fillingstaion';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The Fleet Type has been updated successfully.';
                var Method = 'PATCH';
         modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
            });

        });
    </script>
@endsection
