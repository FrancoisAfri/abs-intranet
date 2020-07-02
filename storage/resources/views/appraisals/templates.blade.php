@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Templates</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
				<table class="table table-bordered">
					 <tr><th style="width: 10px"></th><th>Name</th><th>Job Title</th><th style="width: 40px"></th></tr>
                    @if (count($Templates) > 0)
						@foreach($Templates as $Template)
						 <tr id="categories-list">
						  <td nowrap>
								<button type="button" id="view_templates" class="btn btn-primary  btn-xs" onclick="postData({{$Template->id}}, 'templ');">View</button>
								<button type="button" id="edit_template" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-template-modal" data-id="{{ $Template->id }}" data-template="{{ $Template->template }}" data-job_title_id="{{ $Template->job_title_id }}"><i class="fa fa-pencil-square-o"></i> Edit</button>
                          </td>
						  <td>{{ $Template->template }} </td>
						  <td>{{ $Template->jobTitle->name }} </td>
						  <td>
                              <button type="button" id="" class="btn {{ (!empty($Template->status) && $Template->status == 1) ? "btn-danger" : "btn-success" }} btn-xs" onclick="postData({{$Template->id}}, 'actdeac');"><i class="fa {{ (!empty($Template->status) && $Template->status == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($Template->status) && $Template->status == 1) ? "De-Activate" : "Activate"}}</button>
                          </td>
						</tr>
						@endforeach
                    @else
						<tr id="categories-list">
						<td colspan="5">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            No template to display, please start by adding a new template.
                        </div>
						</td>
						</tr>
                    @endif
				</table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="button" id="add-new-template" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-new-template-modal">Add Template</button>
                </div>
            </div>
        </div>

        <!-- Include add new prime rate modal -->
        @include('appraisals.partials.add_new_template')
        @include('appraisals.partials.edit_template')
    </div>
@endsection

@section('page_script')
    <script>
		function postData(id, data)
		{
			if (data == 'templ')
				location.href = "/appraisal/template/" + id;
			else if (data == 'edit')
				location.href = "/appraisal/template_edit/" + id;
			else if (data == 'actdeac')
				location.href = "/appraisal/template_active/" + id;
		}
        $(function () {
            var templateId;
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
            $('#edit-template-modal').on('show.bs.modal', function (e) {
                var btnEdit = $(e.relatedTarget);
                templateId = btnEdit.data('id');
                var templateName = btnEdit.data('template');
                var jobTitleId = btnEdit.data('job_title_id');
                var modal = $(this);
                modal.find('#template').val(templateName);
                modal.find('#job_title_id').val(jobTitleId);
				$('select#job_title_id').val(jobTitleId);
				
            });

            //function to post category form to server using ajax
            function postModuleForm(formMethod, postUrl, formName) {
                //alert('do you get here');
                $.ajax({
                    method: formMethod,
                    url: postUrl,
                    data: {
                        template: $('form[name=' + formName + ']').find('#template').val(),
                        job_title_id: $('form[name=' + formName + ']').find('#job_title_id').val(),
                         _token: $('input[name=_token]').val()
                    },
                    success: function(success) {
                        location.href = "/appraisal/templates/";
                        $('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups
                        $('form[name=' + formName + ']').trigger('reset'); //Reset the form

                        var successHTML = '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-check"></i> Category added!</h4>';
                        successHTML += 'The new template has been added successfully.';
                        $('#template-success-alert').addClass('alert alert-success alert-dismissible')
                                .fadeIn()
                                .html(successHTML);

                        //show the newly added on the setup list
                        $('#active-template').removeClass('active');
                        var newModuleList = $('#categories-list').html();
                        newModuleList += '<li id="active-template" class="list-group-item active"><b>' + success['new_template'] + '</b> <font class="pull-right">' + ' '+ ';</font></li>';

                        $('#categories-list').html(newModuleList);

                        //auto hide modal after 7 seconds
                        $("#add-new-template-modal").alert();
                        window.setTimeout(function() { $("#add-new-template-modal").modal('hide'); }, 5000);

                        //autoclose alert after 7 seconds
                        $("#template-success-alert").alert();
                        window.setTimeout(function() { $("#template-success-alert").fadeOut('slow'); }, 5000);
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

                            $('#template-invalid-input-alert').addClass('alert alert-danger alert-dismissible')
                                    .fadeIn()
                                    .html(errorsHTML);

                            //autoclose alert after 7 seconds
                            $("#template-invalid-input-alert").alert();
                            window.setTimeout(function() { $("#template-invalid-input-alert").fadeOut('slow'); }, 7000);

                            //Close btn click
                            $('#close-invalid-input-alert').on('click', function () {
                                $("#template-invalid-input-alert").fadeOut('slow');
                            });
                        }
                    }
                });
            }

            //Post category form to server using ajax (ADD)
            $('#add-template').on('click', function() {
                postModuleForm('POST', '/appraisal/template', 'add-template-form');
            });

            $('#update-template').on('click', function() {
                postModuleForm('PATCH', '/appraisal/template_edit/' + templateId, 'edit-template-form');
            });
        });
    </script>
@endsection