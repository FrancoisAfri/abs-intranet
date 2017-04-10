@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Job Titles ({{$jobTitles->name}})</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
				<table class="table table-bordered">
					 <tr><th style="width: 10px">#</th><th>Name</th><th>Description</th><th style="width: 40px"></th></tr>
                    @if (count($jobTitles->catJobTitle) > 0)
						@foreach($jobTitles->catJobTitle as $jobTitle)
						 <tr id="jobtitles-list">
						  <td><button type="button" id="edit_job_title" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-job_title-modal" data-id="{{ $jobTitle->id }}" data-name="{{ $jobTitle->name }}" data-description="{{ $jobTitle->description }}"><i class="fa fa-pencil-square-o"></i> Edit</button></td>
						  <td>{{ (!empty($jobTitle->name)) ?  $jobTitle->name : ''}} </td>
						  <td>{{ (!empty( $jobTitle->description)) ?  $jobTitle->description : ''}} </td>
						  <td nowrap>
                              <button type="button" id="view_job_title" class="btn {{ (!empty($jobTitle->status) && $jobTitle->status == 1) ? "btn-danger" : "btn-success" }} btn-xs" onclick="postData({{$jobTitle->id}}, 'actdeac');"><i class="fa {{ (!empty($jobTitle->status) && $jobTitle->status == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($jobTitle->status) && $jobTitle->status == 1) ? "De-Activate" : "Activate"}}</button>
                          </td>
						</tr>
						@endforeach
                    @else
						<tr id="jobtitles-list">
						<td colspan="6">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            No job titles to display, please start by adding a new job title.
                        </div>
						</td>
						</tr>
                    @endif
					</table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
					<button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                    <button type="button" id="add-new-job_title" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-new-job_title-modal">Add New job Title</button>
                </div>
            </div>
        </div>

        <!-- Include add new prime rate modal -->
        @include('hr.partials.add_position')
        @include('hr.partials.edit_position')
    </div>
@endsection

@section('page_script')
    <script>
		function postData(id, data)
		{
			if (data == 'actdeac')
				location.href = "/hr/job_title_active/" + id;
		}
        $(function () {
            var jobId;
			
			document.getElementById("back_button").onclick = function () {
			location.href = "/hr/job_title";	};
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

            //pass job_title data to the edit job_title modal
            $('#edit-job_title-modal').on('show.bs.modal', function (e) {
                var btnEdit = $(e.relatedTarget);
                jobId = btnEdit.data('id');
                var jobName = btnEdit.data('name');
                var jobDesc = btnEdit.data('description');
                var modal = $(this);
                modal.find('#name').val(jobName);
                modal.find('#description').val(jobDesc);
            });

            //function to post job_title form with ajax
            function postRibbonForm(formMethod, postUrl, formName) {
                $.ajax({
                    method: formMethod,
                    url: postUrl,
                    data: {
                        name: $('form[name=' + formName + ']').find('#name').val(),
                        description: $('form[name=' + formName + ']').find('#description').val(),
                        _token: $('input[name=_token]').val()
                    },
                    success: function(success) {
                        location.href = "/hr/jobtitles/" + {{$jobTitles->id}};
                        $('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups
                        $('form[name=' + formName + ']').trigger('reset'); //Reset the form

                        var successHTML = '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-check"></i> Job Title added!</h4>';
                        successHTML += 'The new job title has been added successfully.';
                        $('#job_title-success-alert').addClass('alert alert-success alert-dismissible')
                                .fadeIn()
                                .html(successHTML);

                        //show the newly added on the setup list
                        $('#active-job_title').removeClass('active');
                        var newModuleList = $('#jobtitles-list').html();
                        newModuleList += '<li id="active-job_title" class="list-group-item active"><b>' + success['new_name'] + '</b> <font class="pull-right">' + success['new_path'] + ';</font></li>';

                        $('#jobtitles-list').html(newModuleList);

                        //auto hide modal after 7 seconds
                        $("#add-new-job_title-modal").alert();
                        window.setTimeout(function() { $("#add-new-job_title-modal").modal('hide'); }, 5000);

                        //autoclose alert after 7 seconds
                        $("#job_title-success-alert").alert();
                        window.setTimeout(function() { $("#job_title-success-alert").fadeOut('slow'); }, 5000);
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

                            $('#job_title-invalid-input-alert').addClass('alert alert-danger alert-dismissible')
                                    .fadeIn()
                                    .html(errorsHTML);

                            //autoclose alert after 7 seconds
                            $("#job_title-invalid-input-alert").alert();
                            window.setTimeout(function() { $("#job_title-invalid-input-alert").fadeOut('slow'); }, 7000);

                            //Close btn click
                            $('#close-invalid-input-alert').on('click', function () {
                                $("#job_title-invalid-input-alert").fadeOut('slow');
                            });
                        }
                    }
                });
            }

            //Post job_title form to server using ajax (ADD NEW)
            $('#add-job_title').on('click', function() {
                postRibbonForm('POST', '/hr/add_jobtitle/{{ $jobTitles->id }}', 'add-job_title-form');
            });

            //Post job_title form to server using ajax (UPDATE)
            $('#update-job_title').on('click', function() {
                postRibbonForm('PATCH', '/job_title/' + jobId, 'edit-job_title-form');
            });
        });
    </script>
@endsection