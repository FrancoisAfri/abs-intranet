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
                    <h3 class="box-title"> Add Fuel tank(s)</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                    {{ csrf_field() }}
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 10px; text-align: center;"></th>
                                <th style="width: 10px; text-align: center;"></th>
                                <th>Tank Name</th>
                                <th>Description</th>
                                <th>Capacity</th>
                                <th>Current Amount</th>
                                <th>Division</th>
                                <th>Department</th>
                                <th>Tank Location</th>
                                <th>Tank Manager</th>
                                <th style="width: 5px; text-align: center;">Status</th>
                            </tr>
                            @if (count($Fueltanks) > 0)
                              @foreach ($Fueltanks as $tanks)
                               <tr id="categories-list">
                               <td nowrap>
                                        <button tanks="button" id="edit_compan" class="btn btn-warning  btn-xs" data-toggle="modal" data-target="#edit-package-modal" data-id="{{ $tanks->id }}" data-name="{{ $tanks->name }}" data-description="{{$tanks->description}}" ><i class="fa fa-pencil-square-o"></i> Edit</button>
                                    </td>
                                     <td>{{ (!empty( $tanks->name)) ?  $tanks->name : ''}} </td>
                                     <td>{{ (!empty( $tanks->description)) ?  $tanks->description : ''}} </td>
                                  <td>
                                    <!--   leave here  -->
                                    <button tanks="button" id="view_ribbons" class="btn {{ (!empty($tanks->status) && $tanks->status == 1) ? " btn-danger " : "btn-success " }}
                                      btn-xs" onclick="postData({{$tanks->id}}, 'actdeac');"><i class="fa {{ (!empty($tanks->status) && $tanks->status == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($tanks->status) && $tanks->status == 1) ? "De-Activate" : "Activate"}}</button>
                                 </td>
                                 <td><button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#delete-contact-warning-modal"><i class="fa fa-trash"></i> Delete</button></td>
                                </tr>
                                   @endforeach
                               @else
                               <tr id="categories-list">
                        <td colspan="12">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            No Fuel tanks to display, please start by adding a new Fuel Tank Type..
                        </div>
                        </td>
                        </tr>
                           @endif
                            </table>
                      <!--   </div> -->
                                   <!-- /.box-body -->
                    <div class="box-footer">
                         <button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                     <button type="button" id="cat_module" class="btn btn-warning pull-right" data-toggle="modal" data-target="#add-tank-modal">Add new Fuel Tanks </button>
                    </div>
             </div>
        </div>
   <!-- Include add new prime rate modal -->
        @include('Vehicles.FuelTanks.partials.add_tank_modal')
        {{--  @include('Vehicles.partials.edit_fleet_modal')  --}}
</div>


@endsection

@section('page_script')
<script src="/custom_components/js/modal_ajax_submit.js"></script>
<!-- Select2 -->
<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
<script>
       function postData(id , data ){   
            if(data == 'actdeac') location.href = "/vehice/fleet_act/" + id; 
          
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

            $(".js-example-basic-multiple").select2();

            //save Fleet
            //Post module form to server using ajax (ADD)
            $('#add-fleet').on('click', function() {
                //console.log('strUrl');
                var strUrl = '/vehice/add_fleet';
                var modalID = 'add-package-modal';
                var objData = {
                    name: $('#'+modalID).find('#name').val(),
                    description: $('#'+modalID).find('#description').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'add-fleet';
                var redirectUrl = '/vehicle_management/Manage_fleet_types';
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
            $('#edit_fleet').on('click', function () {
                var strUrl = '/vehice/edit_fleet/' + fleetID;
                var modalID = 'edit-package-modal';
                var objData = {
                    name: $('#'+modalID).find('#name').val(),
                    description: $('#'+modalID).find('#description').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'edit_fleet';
                var redirectUrl = '/vehicle_management/Manage_fleet_types';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The Fleet Type has been updated successfully.';
                var Method = 'PATCH';
         modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
            });

        });
    </script>
@endsection
