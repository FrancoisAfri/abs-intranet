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
					 <tr>
                        <th style="width: 10px"></th>
                        <th>Module Name</th>
                        <th>Code Name</th>
                        <th>Path</th>
                        <th>Font Awesome</th>
                        <th style="width: 40px"></th>
                     </tr>
                    @if (count($modules) > 0)
						@foreach($modules as $module)
						 <tr id="modules-list">
						  <td nowrap>
                              <button type="button" id="view_ribbons" class="btn btn-primary  btn-xs" onclick="postData({{$module->id}}, 'ribbons');"><i class="fa fa-eye"></i> Ribbons</button>
                              <button type="button" id="edit_module" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-module-modal" data-id="{{ $module->id }}" data-name="{{ $module->name }}" data-path="{{ $module->path }}" data-font_awesome="{{ $module->font_awesome }}" data-code_name="{{ $module->code_name }}"><i class="fa fa-pencil-square-o"></i> Edit</button>
                          </td>
                          <td>{{ $module->name }} </td>
						  <td>{{ $module->code_name }} </td>
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
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>

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
                var codeName = btnEdit.data('code_name');
                var modulePath = btnEdit.data('path');
                var moduleFontAwesome = btnEdit.data('font_awesome');
                var modal = $(this);
                modal.find('#module_name').val(moduleName);
                modal.find('#code_name').val(codeName);
                modal.find('#module_path').val(modulePath);
                modal.find('#font_awesome').val(moduleFontAwesome);
            });

            //Post module form to server using ajax (ADD)
            $('#add-module').on('click', function() {
                var strUrl = '/users/setup/modules';
                var formName = 'add-module-form';
                var modalID = 'add-new-module-modal';
                var submitBtnID = 'add-module';
                var redirectUrl = '/users/modules';
                var successMsgTitle = 'Module Added!';
                var successMsg = 'The Module Has Been Successfully Saved!';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            //Update module
            $('#update-module').on('click', function() {
                var strUrl = '/users/module_edit/' + moduleId;
                var formName = 'edit-module-form';
                var modalID = 'edit-module-modal';
                var submitBtnID = 'update-module';
                var redirectUrl = '/users/modules';
                var successMsgTitle = 'Module Updated!';
                var successMsg = 'Your Changes Has Been Successfully Saved!';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
        });
    </script>
@endsection