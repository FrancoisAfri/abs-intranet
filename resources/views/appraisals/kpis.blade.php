@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">KPIs ({{$template->template}})</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
				<table class="table table-bordered">
					 <tr><th style="width: 10px"></th><th>Category</th><th>KPA</th><th>Indicator</th><th>Measurement</th><th>Source Of Ovidence</th><th>KPI Type</th><th style="width: 40px"></th></tr>
                    @if (!empty($kpis) > 0)
						@foreach($kpis as $kpi)
						 <tr id="kpis-list">
						  <td nowrap></td>
						  <td></td>
						  <td></td>
						  <td></td>
						  <td></td>
						  <td></td>
						  <td></td>
						  <td></td>
						</tr>
						@endforeach
                    @else
						<tr id="kpis-list">
						<td colspan="5">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            No kpi to display, please start by adding a new kpi.
                        </div>
						</td>
						</tr>
                    @endif
				</table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="button" id="add-new-kpi" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-new-kpi-modal">Add KPI</button>
                </div>
            </div>
        </div>

        <!-- Include add new modal -->
        @include('appraisals.partials.add_kpi')
        @include('appraisals.partials.edit_kpi')
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
            $('#edit-kpi-modal').on('show.bs.modal', function (e) {
                var btnEdit = $(e.relatedTarget);
                templateId = btnEdit.data('id');
                var templateName = btnEdit.data('kpi');
                var jobTitleId = btnEdit.data('job_title_id');
                var modal = $(this);
                modal.find('#kpi').val(templateName);
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
                        kpi: $('form[name=' + formName + ']').find('#kpi').val(),
                        job_title_id: $('form[name=' + formName + ']').find('#job_title_id').val(),
                         _token: $('input[name=_token]').val()
                    },
                    success: function(success) {
                        location.href = "/appraisal/templates/";
                        $('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups
                        $('form[name=' + formName + ']').trigger('reset'); //Reset the form

                        var successHTML = '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-check"></i> Category added!</h4>';
                        successHTML += 'The new kpi has been added successfully.';
                        $('#kpi-success-alert').addClass('alert alert-success alert-dismissible')
                                .fadeIn()
                                .html(successHTML);

                        //show the newly added on the setup list
                        $('#active-kpi').removeClass('active');
                        var newModuleList = $('#kpis-list').html();
                        newModuleList += '<li id="active-kpi" class="list-group-item active"><b>' + success['new_template'] + '</b> <font class="pull-right">' + ' '+ ';</font></li>';

                        $('#kpis-list').html(newModuleList);

                        //auto hide modal after 7 seconds
                        $("#add-new-kpi-modal").alert();
                        window.setTimeout(function() { $("#add-new-kpi-modal").modal('hide'); }, 5000);

                        //autoclose alert after 7 seconds
                        $("#kpi-success-alert").alert();
                        window.setTimeout(function() { $("#kpi-success-alert").fadeOut('slow'); }, 5000);
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

                            $('#kpi-invalid-input-alert').addClass('alert alert-danger alert-dismissible')
                                    .fadeIn()
                                    .html(errorsHTML);

                            //autoclose alert after 7 seconds
                            $("#kpi-invalid-input-alert").alert();
                            window.setTimeout(function() { $("#kpi-invalid-input-alert").fadeOut('slow'); }, 7000);

                            //Close btn click
                            $('#close-invalid-input-alert').on('click', function () {
                                $("#kpi-invalid-input-alert").fadeOut('slow');
                            });
                        }
                    }
                });
            }

            //Post category form to server using ajax (ADD)
            $('#add-kpi').on('click', function() {
                postModuleForm('POST', '/appraisal/kpi', 'add-kpi-form');
            });

            $('#update-kpi').on('click', function() {
                postModuleForm('PATCH', '/appraisal/template_edit/' + templateId, 'edit-kpi-form');
            });
        });
    </script>
@endsection