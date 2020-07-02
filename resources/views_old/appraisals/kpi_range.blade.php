@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">KPIs ({{$kpi->indicator}})</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
				<div style="overflow-X:auto;">
				<table class="table table-bordered">
					 <tr><th style="width: 10px"></th><th>Range From</th><th>Range To</th><th>Percentage</th><th style="width: 40px"></th></tr>
                    @if (!empty($ranges))
						@foreach($ranges as $range)
						 <tr id="ranges-list">
						  <td><button type="button" id="edit_kpi_title" class="btn btn-primary  btn-xs" 
						  data-toggle="modal" 
						  data-target="#edit-range-modal"
						  data-id="{{ $range->id }}"
						  data-range_from="{{ $range->range_from }}" 
						  data-range_to="{{ $range->range_to }}" 
						  data-percentage="{{ $range->percentage}}"><i class="fa fa-pencil-square-o"></i> Edit</button></td>
						  <td>{{$range->range_from}}</td>
						  <td>{{$range->range_to}}</td>
						  <td>{{$range->percentage}}</td>
						  <td nowrap>
                              <button type="button" id="view_kpi" class="btn {{ (!empty($range->status) && $range->status == 1) ? "btn-danger" : "btn-success" }} btn-xs" onclick="postData({{$range->id}}, 'actdeac');"><i class="fa {{ (!empty($range->status) && $range->status == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($range->status) && $range->status == 1) ? "De-Activate" : "Activate"}}</button>
                          </td>
						</tr>
						@endforeach
                    @else
						<tr id="ranges-list">
						<td colspan="5">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            No range to display, please start by adding a new range.
                        </div>
						</td>
						</tr>
                    @endif
				</table>
                </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
				<button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                    <button type="button" id="add-new-range" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-new-range-modal">Add Range</button>
                </div>
            </div>
        </div>

        <!-- Include add new modal -->
        @include('appraisals.partials.add_range')
        @include('appraisals.partials.edit_range')
    </div>
@endsection

@section('page_script')

	<!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
	
    <script>
		function postData(id, data)
		{
			if (data == 'actdeac')
				location.href = "/appraisal/range_active/" + id;
		}
        $(function () {
            var rangeId;
            //Tooltip
            $('[data-toggle="tooltip"]').tooltip();
			
			document.getElementById("back_button").onclick = function () {
			location.href = "/appraisal/template/" + {{$kpi->template_id}} };
			
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
            $('#edit-range-modal').on('show.bs.modal', function (e) {
                var btnEdit = $(e.relatedTarget);
                rangeId = btnEdit.data('id');
                var RangeFrom = btnEdit.data('range_from');
                var RangeTo = btnEdit.data('range_to');
                var Percentage = btnEdit.data('percentage');
                var modal = $(this);
                modal.find('#range_from').val(RangeFrom);
                modal.find('#range_to').val(RangeTo);
                modal.find('#percentage').val(Percentage);
            });

            //function to post category form to server using ajax
            function postModuleForm(formMethod, postUrl, formName) {
                $.ajax({
                    method: formMethod,
                    url: postUrl,
                    data: {
                        range_from: $('form[name=' + formName + ']').find('#range_from').val(),
                        range_to: $('form[name=' + formName + ']').find('#range_to').val(),
                        percentage: $('form[name=' + formName + ']').find('#percentage').val(),
                        kpi_id: {{$kpi->id}},
                         _token: $('input[name=_token]').val()
                    },
                    success: function(success) {
						location.href = "/appraisal/kpi_range/" + {{$kpi->id}};
                        $('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups
                        $('form[name=' + formName + ']').trigger('reset'); //Reset the form

                        var successHTML = '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-check"></i> Category added!</h4>';
                        successHTML += 'The new range has been added successfully.';
                        $('#range-success-alert').addClass('alert alert-success alert-dismissible')
                                .fadeIn()
                                .html(successHTML);

                        //show the newly added on the setup list
                        $('#active-range').removeClass('active');
                        var newModuleList = $('#ranges-list').html();
                        newModuleList += '<li id="active-range" class="list-group-item active"><b>' + success['new_template'] + '</b> <font class="pull-right">' + ' '+ ';</font></li>';

                        $('#ranges-list').html(newModuleList);

                        //auto hide modal after 7 seconds
                        $("#add-new-range-modal").alert();
                        window.setTimeout(function() { $("#add-new-range-modal").modal('hide'); }, 5000);

                        //autoclose alert after 7 seconds
                        $("#range-success-alert").alert();
                        window.setTimeout(function() { $("#range-success-alert").fadeOut('slow'); }, 5000);
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

                            $('#range-invalid-input-alert').addClass('alert alert-danger alert-dismissible')
                                    .fadeIn()
                                    .html(errorsHTML);

                            //autoclose alert after 7 seconds
                            $("#range-invalid-input-alert").alert();
                            window.setTimeout(function() { $("#range-invalid-input-alert").fadeOut('slow'); }, 7000);

                            //Close btn click
                            $('#close-invalid-input-alert').on('click', function () {
                                $("#range-invalid-input-alert").fadeOut('slow');
                            });
                        }
                    }
                });
            }

            //Post category form to server using ajax (ADD)
            $('#add-range').on('click', function() {
                postModuleForm('POST', '/appraisal/range', 'add-range-form');
            });

            $('#update-range').on('click', function() {
                postModuleForm('PATCH', '/appraisal/range_edit/' + rangeId, 'edit-range-form');
            });
        });
    </script>
@endsection