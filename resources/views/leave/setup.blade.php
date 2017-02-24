@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Leave Types</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
				<table class="table table-bordered">
					 <tr><th style="width: 10px"></th><th>Name</th><th>Description</th><th style="width: 40px"></th></tr>
                    @if (count($leaveTypes) > 0)
						@foreach($leaveTypes as $leaveType)
						 <tr id="modules-list">
						  <td nowrap>
<!--                              <button type="button" id="view_ribbons" class="btn btn-primary  btn-xs" onclick="postData({{$leaveType->id}}, 'ribbons');"><i class="fa fa-eye"></i> Ribbons</button>-->
                              <button type="button" id="edit_leave" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-leave-modal" data-id="{{ $leaveType->id }}" data-name="{{ $leaveType->name }}" data-description="{{ $leaveType->description }}"<i class="fa fa-pencil-square-o"></i> Edit</button>
                          </td>
						  <td>{{ $leaveType->name }} </td>
						
						  <td>{{ $leaveType->description }} </td>
						  <td>
                              <button type="button" class="btn {{ $leaveType->status === 1 ? "btn-danger" : "btn-primary" }} btn-xs"><i class="fa {{ $leaveType->status === 1 ? "fa-times" : "fa-check" }}"></i> {{ $leaveType->status === 1 ? "De-activate" : "Activate" }}</button>
                          </td>
						</tr>
						@endforeach
                    @else
						<tr id="modules-list">
						<td colspan="5">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            No leave types to display, please start by adding a new module.
                        </div>
						</td>
						</tr>
                    @endif
				</table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="button" id="add-new-leave" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-new-leave-modal">Add New leave</button>
                </div>
            </div>
        </div>

        <!-- Include add new prime rate modal -->
        @include('leave.partials.add_new_leavetype')
        @include('leave.partials.edit_leavetype')


    </div>
@endsection
<!--        edit ribbon-->
@section('page_script')
    <script>
		 function postData(id, data)
		 {
		 	if (data == 'ribbons')
		 		location.href = "/leave/ribbons/" + id;
		 	else if (data == 'edit')
		 		location.href = "/leave/leave_edit/" + id;
//		 	else if (data == 'actdeac')
//		 		location.href = "/leave/module_active/" + id;
//		 	else if (data == 'access')
//		 		location.href = "/leave/module_access/" + id;
		 }
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
            $(window).on('resize', function() {
                $('.modal:visible').each(reposition);
            });

            //pass module data to the edit module modal
            
            var leaveTypeId;
            
           $('#edit-leave-modal').on('show.bs.modal', function (e) {
               //console.log('kjhsjs');
               
               var btnEdit = $(e.relatedTarget);
               leaveTypeId = btnEdit.data('id');
               var name = btnEdit.data('name');
               var description = btnEdit.data('description');
              // var moduleFontAwesome = btnEdit.data('font_awesome');
               var modal = $(this);
               modal.find('#name').val(name);
               modal.find('#description').val(description);
              // modal.find('#font_awesome').val(moduleFontAwesome);
               //if(primeRate != null && primeRate != '' && primeRate > 0) {
               //    modal.find('#prime_rate').val(primeRate.toFixed(2));
               //}
               
               $('#add_leave').on('click', function() {
//                console.log('gettest');
                postModuleForm('POST', '/leave/setup/LeaveSetUp', 'add_new_leavetype-form');
            });

            $('#update-leave_type').on('click', function() {
               
                postModuleForm('PATCH', '/leave/leave_edit/' + leaveTypeId , 'edit_leavetype-form');
            });
           });

            //function to post module form to server using ajax
            function postModuleForm(formMethod, postUrl, formName) {
                //alert('do you get here');
                $.ajax({
                    method: formMethod,
                    url: postUrl,
                    data: {
                        name: $('form[name=' + formName + ']').find('#name').val(),
                        
                        description: $('form[name=' + formName + ']').find('#description').val(),
                       // font_awesome: $('form[name=' + formName + ']').find('#font_awesome').val(),
                        _token: $('input[name=_token]').val()
                    },
                    success: function(success) {
                        location.href = "/leave/setup/";
                        $('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups
                        $('form[name=' + formName + ']').trigger('reset'); //Reset the form

                        var successHTML = '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-check"></i> leave added!</h4>';
                        successHTML += 'The leave has been added successfully.';
                        $('#leave-success-alert').addClass('alert alert-success alert-dismissible')
                                .fadeIn()
                                .html(successHTML);

                        //show the newly added on the setup list
                        $('#active-leave').removeClass('active');
                        var newLeaveList = $('#modules-list').html();
                        newLeaveList += '<li id="active-leave" class="list-group-item active"><b>' + success['new_leave'] + '</b> <font class="pull-right">' + success['new_description'] + ';</font></li>';

                        $('#leave-list').html(newLeaveList);

                        //auto hide modal after 7 seconds
                        $("#add_new_leavetype").alert();
                        window.setTimeout(function() { $("#add-new-module-modal").modal('hide'); }, 5000);

                        //autoclose alert after 7 seconds
                        $("#module-success-alert").alert();
                        window.setTimeout(function() { $("#module-success-alert").fadeOut('slow'); }, 5000);
                    },
                    error: function(xhr) {
                        //if(xhr.status === 401) //redirect if not authenticated
                        //$( location ).prop( 'pathname', 'auth/login' );
                        if(xhr.status === 422) {
                            console.log(xhr);
                            var errors = xhr.responseJSON; //get the errors response data

                            $('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups

                            var errorsHTML = '<button type="button" id="close-invalid-input-alert" class="close" aria-hidden="true">&times;</button><h4><i class="icon fa fa-ban"></i> Invalid Input!</h4><ul>';
                            $.each(errors, function (key, value) {
                                errorsHTML += '<li>' + value[0] + '</li>'; //shows only the first error.
                                $('#'+key).closest('.form-group')
                                        .addClass('has-error'); //Add the has error class to form-groups with errors
                            });
                            errorsHTML += '</ul>';

                            $('#module-invalid-input-alert').addClass('alert alert-danger alert-dismissible')
                                    .fadeIn()
                                    .html(errorsHTML);

                            //autoclose alert after 7 seconds
                            $("#module-invalid-input-alert").alert();
                            window.setTimeout(function() { $("#module-invalid-input-alert").fadeOut('slow'); }, 7000);

                            //Close btn click
                            $('#close-invalid-input-alert').on('click', function () {
                                $("#module-invalid-input-alert").fadeOut('slow');
                            });
                        }
                    }
                });
            }

            //Post module form to server using ajax (ADD)
            
        });
    </script>
@endsection