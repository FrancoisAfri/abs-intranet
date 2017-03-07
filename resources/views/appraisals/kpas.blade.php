@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Category ({{$categories->name}})</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
				<table class="table table-bordered">
					 <tr><th style="width: 10px">#</th><th>Name</th><th>Weight</th><th style="width: 40px"></th></tr>
                    @if (count($categories->kpascategory) > 0)
						@foreach($categories->kpascategory as $kpa)
						 <tr id="categories-list">
						  <td><button type="button" id="edit_job_title" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-job_title-modal" data-id="{{ $kpa->id }}" data-name="{{ $kpa->name }}" data-weight="{{ $kpa->weight }}"><i class="fa fa-pencil-square-o"></i> Edit</button></td>
						  <td>{{ (!empty($kpa->name)) ?  $kpa->name : ''}} </td>
						  <td>{{ (!empty( $kpa->weight)) ?  $kpa->weight : ''}} </td>
						  <td nowrap>
                              <button type="button" id="view_job_title" class="btn {{ (!empty($kpa->status) && $kpa->status == 1) ? "btn-danger" : "btn-success" }} btn-xs" onclick="postData({{$kpa->id}}, 'actdeac');"><i class="fa {{ (!empty($kpa->status) && $kpa->status == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($kpa->status) && $kpa->status == 1) ? "De-Activate" : "Activate"}}</button>
                          </td>
						</tr>
						@endforeach
                    @else
						<tr id="categories-list">
						<td colspan="6">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            No kpas to display, please start by adding a new kpa.
                        </div>
						</td>
						</tr>
                    @endif
					</table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
					<button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                    <button type="button" id="add-new-job_title" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-new-kpa-modal">Add New Kpa</button>
                </div>
            </div>
        </div>

        <!-- Include add new prime rate modal -->
        @include('appraisals.partials.add_kpa')
        @include('appraisals.partials.edit_kpa')
    </div>
@endsection

@section('page_script')
    <script>
		function postData(id, data)
		{
			if (data == 'actdeac')
				location.href = "/appraisal/kpa_active/" + id;
		}
        $(function () {
            var kpaId;
			
			document.getElementById("back_button").onclick = function () {
			location.href = "/appraisal/categories";	};
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
                kpaId = btnEdit.data('id');
                var Name = btnEdit.data('name');
                var weight = btnEdit.data('weight');
                var modal = $(this);
                modal.find('#name').val(Name);
                modal.find('#weight').val(weight);
            });

            //function to post job_title form with ajax
            function postRibbonForm(formMethod, postUrl, formName) {
                $.ajax({
                    method: formMethod,
                    url: postUrl,
                    data: {
                        name: $('form[name=' + formName + ']').find('#name').val(),
                        weight: $('form[name=' + formName + ']').find('#weight').val(),
                        _token: $('input[name=_token]').val()
                    },
                    success: function(success) {
                        location.href = "/appraisal/kpa/" + {{$categories->id}};
                        $('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups
                        $('form[name=' + formName + ']').trigger('reset'); //Reset the form

                        var successHTML = '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-check"></i> Job Title added!</h4>';
                        successHTML += 'The new job title has been added successfully.';
                        $('#kpa-success-alert').addClass('alert alert-success alert-dismissible')
                                .fadeIn()
                                .html(successHTML);

                        //show the newly added on the setup list
                        $('#active-job_title').removeClass('active');
                        var newModuleList = $('#categories-list').html();
                        newModuleList += '<li id="active-job_title" class="list-group-item active"><b>' + success['new_name'] + '</b> <font class="pull-right">' + success['new_path'] + ';</font></li>';

                        $('#categories-list').html(newModuleList);

                        //auto hide modal after 7 seconds
                        $("#add-new-kpa-modal").alert();
                        window.setTimeout(function() { $("#add-new-kpa-modal").modal('hide'); }, 5000);

                        //autoclose alert after 7 seconds
                        $("#kpa-success-alert").alert();
                        window.setTimeout(function() { $("#kpa-success-alert").fadeOut('slow'); }, 5000);
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

                            $('#kpa-invalid-input-alert').addClass('alert alert-danger alert-dismissible')
                                    .fadeIn()
                                    .html(errorsHTML);

                            //autoclose alert after 7 seconds
                            $("#kpa-invalid-input-alert").alert();
                            window.setTimeout(function() { $("#kpa-invalid-input-alert").fadeOut('slow'); }, 7000);

                            //Close btn click
                            $('#close-invalid-input-alert').on('click', function () {
                                $("#kpa-invalid-input-alert").fadeOut('slow');
                            });
                        }
                    }
                });
            }

            //Post job_title form to server using ajax (ADD NEW)
            $('#add-kpa').on('click', function() {
                postRibbonForm('POST', '/appraisal/add_kpa/{{ $categories->id }}', 'add-kpa-form');
            });

            //Post job_title form to server using ajax (UPDATE)
            $('#update-kpa').on('click', function() {
                postRibbonForm('PATCH', '/appraisal/kpas/' + kpaId, 'edit-job_title-form');
            });
        });
    </script>
@endsection