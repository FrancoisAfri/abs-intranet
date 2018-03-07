@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Ribbons ({{$ribbons->name}})</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
				<table class="table table-bordered">
					 <tr><th style="width: 10px">#</th><th>Name</th><th>Description</th><th>Path</th><th>Access Right</th><th style="width: 40px"></th></tr>
                    @if (count($ribbons->moduleRibbon) > 0)
						@foreach($ribbons->moduleRibbon as $ribbon)
						 <tr id="ribbons-list">
						  <td>{{ (!empty($ribbon->sort_order)) ?  $ribbon->sort_order : ''}} </td>
						  <td>{{ (!empty($ribbon->ribbon_name)) ?  $ribbon->ribbon_name : '' }} </td>
						  <td>{{ (!empty( $ribbon->description)) ?  $ribbon->description : ''}} </td>
						  <td>
							{{ (!empty($ribbon->ribbon_path) && $ribbon->ribbon_path != '') ? str_replace('\/',"/",$ribbon->ribbon_path) : ''  }}
						  </td> 
						  <td>
							{{ !empty($ribbon->access_level) ? $arrayRights[$ribbon->access_level] : 'None' }}
						  </td>
						  <td nowrap>
                              <button type="button" id="edit_ribbon" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-ribbon-modal" data-id="{{ $ribbon->id }}" data-name="{{ $ribbon->ribbon_name }}" data-path="{{ $ribbon->ribbon_path }}" data-description="{{ $ribbon->description }}" data-sort_order="{{ $ribbon->sort_order }}" data-access_level="{{ $ribbon->access_level }}"><i class="fa fa-pencil-square-o"></i> Edit</button>
                              <button type="button" id="view_ribbons" class="btn {{ (!empty($ribbon->active) && $ribbon->active == 1) ? "btn-danger" : "btn-success" }} btn-xs" onclick="postData({{$ribbon->id}}, 'actdeac');"><i class="fa {{ (!empty($ribbon->active) && $ribbon->active == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($ribbon->active) && $ribbon->active == 1) ? "De-Activate" : "Activate"}}</button>
                          </td>
						</tr>
						@endforeach
                    @else
						<tr id="ribbons-list">
						<td colspan="6">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            No ribbon to display, please start by adding a new ribbon.
                        </div>
						</td>
						</tr>
                    @endif
					</table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
					<button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                    <button type="button" id="add-new-ribbon" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-new-ribbon-modal">Add New ribbon</button>
                </div>
            </div>
        </div>

        <!-- Include add new prime rate modal -->
        @include('security.partials.add_ribbon')
        @include('security.partials.edit_ribbon')
    </div>
@endsection

@section('page_script')
    <script>
		function postData(id, data)
		{
			if (data == 'actdeac')
				location.href = "/users/ribbon_active/" + id;
		}
        $(function () {
            var ribbonId;
			
			document.getElementById("back_button").onclick = function () {
			location.href = "/users/modules";	};
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

            //pass ribbon data to the edit ribbon modal
            $('#edit-ribbon-modal').on('show.bs.modal', function (e) {
                var btnEdit = $(e.relatedTarget);
                ribbonId = btnEdit.data('id');
                var ribbonName = btnEdit.data('name');
                var ribbonPath = btnEdit.data('path');
                var ribbonDesc = btnEdit.data('description');
                var ribbonSortOrd = btnEdit.data('sort_order');
                var ribbonAccLvl = btnEdit.data('access_level');
                var modal = $(this);
                modal.find('#ribbon_name').val(ribbonName);
                modal.find('#description').val(ribbonDesc);
                modal.find('#ribbon_path').val(ribbonPath);
                modal.find('#sort_order').val(ribbonSortOrd);
                if(ribbonAccLvl != null && ribbonAccLvl != '' && ribbonAccLvl >= 0) {
                    modal.find('#access_level').val(ribbonAccLvl);
                }
            });

            //function to post ribbon form with ajax
            function postRibbonForm(formMethod, postUrl, formName) {
                $.ajax({
                    method: formMethod,
                    url: postUrl,
                    data: {
                        ribbon_name: $('form[name=' + formName + ']').find('#ribbon_name').val(),
                        ribbon_path: $('form[name=' + formName + ']').find('#ribbon_path').val(),
                        description: $('form[name=' + formName + ']').find('#description').val(),
                        access_level: $('form[name=' + formName + ']').find('#access_level').val(),
                        sort_order: $('form[name=' + formName + ']').find('#sort_order').val(),
                        _token: $('input[name=_token]').val()
                    },
                    success: function(success) {
                        location.href = "/users/ribbons/" + {{$ribbons->id}};
                        $('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups
                        $('form[name=' + formName + ']').trigger('reset'); //Reset the form

                        var successHTML = '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-check"></i> ribbon added!</h4>';
                        successHTML += 'The new ribbon has been added successfully.';
                        $('#ribbon-success-alert').addClass('alert alert-success alert-dismissible')
                                .fadeIn()
                                .html(successHTML);

                        //show the newly added on the setup list
                        $('#active-ribbon').removeClass('active');
                        var newModuleList = $('#ribbons-list').html();
                        newModuleList += '<li id="active-ribbon" class="list-group-item active"><b>' + success['new_name'] + '</b> <font class="pull-right">' + success['new_path'] + ';</font></li>';

                        $('#ribbons-list').html(newModuleList);

                        //auto hide modal after 7 seconds
                        $("#add-new-ribbon-modal").alert();
                        window.setTimeout(function() { $("#add-new-ribbon-modal").modal('hide'); }, 5000);

                        //autoclose alert after 7 seconds
                        $("#ribbon-success-alert").alert();
                        window.setTimeout(function() { $("#ribbon-success-alert").fadeOut('slow'); }, 5000);
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

                            $('#ribbon-invalid-input-alert').addClass('alert alert-danger alert-dismissible')
                                    .fadeIn()
                                    .html(errorsHTML);

                            //autoclose alert after 7 seconds
                            $("#ribbon-invalid-input-alert").alert();
                            window.setTimeout(function() { $("#ribbon-invalid-input-alert").fadeOut('slow'); }, 7000);

                            //Close btn click
                            $('#close-invalid-input-alert').on('click', function () {
                                $("#ribbon-invalid-input-alert").fadeOut('slow');
                            });
                        }
                    }
                });
            }

            //Post ribbon form to server using ajax (ADD NEW)
            $('#add-ribbon').on('click', function() {
                postRibbonForm('POST', '/users/setup/add_ribbon/{{ $ribbons->id }}', 'add-ribbon-form');
            });

            //Post ribbon form to server using ajax (UPDATE)
            $('#update-ribbon').on('click', function() {
                postRibbonForm('PATCH', '/ribbon/' + ribbonId, 'edit-ribbon-form');
            });
        });
    </script>
@endsection