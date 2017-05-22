@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Modules</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
				<table class="table table-bordered">
					 <tr><th style="width: 10px"></th><th>Name</th><th>Path</th><th>Font Awesome</th><th style="width: 40px"></th></tr>
                    @if (count($modules) > 0)
						@foreach($modules as $module)
						 <tr id="modules-list">
						  <td nowrap>
                              <button type="button" id="view_ribbons" class="btn btn-primary  btn-xs" onclick="postData({{$module->id}}, 'ribbons');"><i class="fa fa-eye"></i> Ribbons</button>
                              <button type="button" id="edit_module" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-module-modal" data-id="{{ $module->id }}" data-name="{{ $module->name }}" data-path="{{ $module->path }}" data-font_awesome="{{ $module->font_awesome }}"><i class="fa fa-pencil-square-o"></i> Edit</button>
                          </td>
						  <td>{{ $module->name }} </td>
						  <td>
							{{ (!empty($module->path) && $module->path != '') ? str_replace('\/',"/",$module->path) : ''  }}
						  </td>
						  <td>{{ $module->font_awesome }} </td>
						  <td>
                              <button type="button" id="view_ribbons" class="btn {{ (!empty($module->active) && $module->active == 1) ? "btn-danger" : "btn-success" }} btn-xs" onclick="postData({{$module->id}}, 'actdeac');"><i class="fa {{ (!empty($module->active) && $module->active == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($module->active) && $module->active == 1) ? "De-Activate" : "Activate"}}</button>
                          </td>
						</tr>
						@endforeach
                    @else
						<tr id="modules-list">
						<td colspan="5">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            No Module to display, please start by adding a new module.
                        </div>
						</td>
						</tr>
                    @endif
				</table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="button" id="add-new-module" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-new-module-modal">Add New Module</button>
                </div>
            </div>
        </div>

        <!-- Include add new prime rate modal -->
        @include('security.partials.add_new_module')
        @include('security.partials.edit_module')
    </div>
@endsection

@section('page_script')
    <script>
		function postData(id, data)
		{
			if (data == 'ribbons')
				location.href = "/users/ribbons/" + id;
			else if (data == 'edit')
				location.href = "/users/module_edit/" + id;
			else if (data == 'actdeac')
				location.href = "/users/module_active/" + id;
			else if (data == 'access')
				location.href = "/users/module_access/" + id;
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
            $('#edit-module-modal').on('show.bs.modal', function (e) {
                var btnEdit = $(e.relatedTarget);
                moduleId = btnEdit.data('id');
                var moduleName = btnEdit.data('name');
                var modulePath = btnEdit.data('path');
                var moduleFontAwesome = btnEdit.data('font_awesome');
                var modal = $(this);
                modal.find('#module_name').val(moduleName);
                modal.find('#module_path').val(modulePath);
                modal.find('#font_awesome').val(moduleFontAwesome);
                //if(primeRate != null && primeRate != '' && primeRate > 0) {
                //    modal.find('#prime_rate').val(primeRate.toFixed(2));
                //}
            });

            //function to post module form to server using ajax
            function postModuleForm(formMethod, postUrl, formName) {
                //alert('do you get here');
                $.ajax({
                    method: formMethod,
                    url: postUrl,
                    data: {
                        module_name: $('form[name=' + formName + ']').find('#module_name').val(),
                        module_path: $('form[name=' + formName + ']').find('#module_path').val(),
                        font_awesome: $('form[name=' + formName + ']').find('#font_awesome').val(),
                        _token: $('input[name=_token]').val()
                    },
                    success: function(success) {
                        location.href = "/users/modules/";
                        $('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups
                        $('form[name=' + formName + ']').trigger('reset'); //Reset the form

                        var successHTML = '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-check"></i> Module added!</h4>';
                        successHTML += 'The new module has been added successfully.';
                        $('#module-success-alert').addClass('alert alert-success alert-dismissible')
                                .fadeIn()
                                .html(successHTML);

                        //show the newly added on the setup list
                        $('#active-module').removeClass('active');
                        var newModuleList = $('#modules-list').html();
                        newModuleList += '<li id="active-module" class="list-group-item active"><b>' + success['new_name'] + '</b> <font class="pull-right">' + success['new_path'] + ';</font></li>';

                        $('#modules-list').html(newModuleList);

                        //auto hide modal after 7 seconds
                        $("#add-new-module-modal").alert();
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
            $('#add-module').on('click', function() {
                postModuleForm('POST', '/users/setup/modules', 'add-module-form');
            });

            $('#update-module').on('click', function() {
                postModuleForm('PATCH', '/users/module_edit/' + moduleId, 'edit-module-form');
            });
        });
    </script>
@endsection