@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Categories</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
				<table class="table table-bordered">
					 <tr><th style="width: 10px"></th><th>Name</th><th>Description</th><th style="width: 40px"></th></tr>
                    @if (count($jobCategories) > 0)
						@foreach($jobCategories as $jobCategory)
						 <tr id="categories-list">
						  <td nowrap>
								<button type="button" id="view_position" class="btn btn-primary  btn-xs" onclick="postData({{$jobCategory->id}}, 'job');">Job Titles {{!empty($jobCategory->cat_job_title) ? ($jobCategory->cat_job_title->count()) : ''}}</button>
								<button type="button" id="edit_category" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-category-modal" data-id="{{ $jobCategory->id }}" data-name="{{ $jobCategory->name }}" data-description="{{ $jobCategory->description }}" ><i class="fa fa-pencil-square-o"></i> Edit</button>
                          </td>
						  <td>{{ $jobCategory->name }} </td>
						  <td>
							{{ (!empty($jobCategory->description)) ? $jobCategory->description : ''  }}
						  </td>
						  <td>
                              <button type="button" id="" class="btn {{ (!empty($jobCategory->status) && $jobCategory->status == 1) ? "btn-danger" : "btn-success" }} btn-xs" onclick="postData({{$jobCategory->id}}, 'actdeac');"><i class="fa {{ (!empty($jobCategory->status) && $jobCategory->status == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($jobCategory->status) && $jobCategory->status == 1) ? "De-Activate" : "Activate"}}</button>
                          </td>
						</tr>
						@endforeach
                    @else
						<tr id="categories-list">
						<td colspan="5">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            No category to display, please start by adding a new category.
                        </div>
						</td>
						</tr>
                    @endif
				</table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="button" id="add-new-category" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-new-category-modal">Add Category</button>
                </div>
            </div>
        </div>

        <!-- Include add new prime rate modal -->
        @include('hr.partials.add_new_category')
        @include('hr.partials.edit_category_modal')
    </div>
@endsection

@section('page_script')
    <script>
		function postData(id, data)
		{
			if (data == 'job')
				location.href = "/hr/jobtitles/" + id;
			else if (data == 'edit')
				location.href = "/hr/category_edit/" + id;
			else if (data == 'actdeac')
				location.href = "/hr/category_active/" + id;
		}
        $(function () {
            var categoryId;
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

            //pass category data to the edit category modal
            $('#edit-category-modal').on('show.bs.modal', function (e) {
                var btnEdit = $(e.relatedTarget);
                categoryId = btnEdit.data('id');
                var categoryName = btnEdit.data('name');
                var description = btnEdit.data('description');
                var modal = $(this);
                modal.find('#name').val(categoryName);
                modal.find('#description').val(description);
            });

            //function to post category form to server using ajax
            function postModuleForm(formMethod, postUrl, formName) {
                //alert('do you get here');
                $.ajax({
                    method: formMethod,
                    url: postUrl,
                    data: {
                        name: $('form[name=' + formName + ']').find('#name').val(),
                        description: $('form[name=' + formName + ']').find('#description').val(),
                         _token: $('input[name=_token]').val()
                    },
                    success: function(success) {
                        location.href = "/hr/job_title/";
                        $('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups
                        $('form[name=' + formName + ']').trigger('reset'); //Reset the form

                        var successHTML = '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-check"></i> Category added!</h4>';
                        successHTML += 'The new category has been added successfully.';
                        $('#category-success-alert').addClass('alert alert-success alert-dismissible')
                                .fadeIn()
                                .html(successHTML);

                        //show the newly added on the setup list
                        $('#active-category').removeClass('active');
                        var newModuleList = $('#categories-list').html();
                        newModuleList += '<li id="active-category" class="list-group-item active"><b>' + success['new_name'] + '</b> <font class="pull-right">' + success['new_description'] + ';</font></li>';

                        $('#categories-list').html(newModuleList);

                        //auto hide modal after 7 seconds
                        $("#add-new-category-modal").alert();
                        window.setTimeout(function() { $("#add-new-category-modal").modal('hide'); }, 5000);

                        //autoclose alert after 7 seconds
                        $("#category-success-alert").alert();
                        window.setTimeout(function() { $("#category-success-alert").fadeOut('slow'); }, 5000);
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

                            $('#category-invalid-input-alert').addClass('alert alert-danger alert-dismissible')
                                    .fadeIn()
                                    .html(errorsHTML);

                            //autoclose alert after 7 seconds
                            $("#category-invalid-input-alert").alert();
                            window.setTimeout(function() { $("#category-invalid-input-alert").fadeOut('slow'); }, 7000);

                            //Close btn click
                            $('#close-invalid-input-alert').on('click', function () {
                                $("#category-invalid-input-alert").fadeOut('slow');
                            });
                        }
                    }
                });
            }

            //Post category form to server using ajax (ADD)
            $('#add-category').on('click', function() {
                postModuleForm('POST', '/hr/categories', 'add-category-form');
            });

            $('#update-category').on('click', function() {
                postModuleForm('PATCH', '/hr/category_edit/' + categoryId, 'edit-category-form');
            });
        });
    </script>
@endsection