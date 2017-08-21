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
				<div style="overflow-X:auto;">
				<table class="table table-bordered">
					 <tr><th style="width: 10px"></th><th>Category</th><th>KPA</th><th>Indicator</th><th>Measurement</th><th>Source Of Evidence</th><th>Weight</th><th>Upload</th><th>Upload Type</th><th>KPI Type</th><th style="width: 40px"></th></tr>
                    @if (!empty($kpis))
						@foreach($kpis as $kpi)
						 <tr id="kpis-list">
						  <td><button type="button" id="edit_kpi_title" class="btn btn-primary  btn-xs" 
						  data-toggle="modal" 
						  data-target="#edit-kpi-modal" 
						  data-id="{{ $kpi->id }}" 
						  data-measurement="{{ $kpi->measurement }}" 
						  data-source_of_evidence="{{ $kpi->source_of_evidence }}" 
						  data-indicator="{{ $kpi->indicator }}" 
						  data-kpi_type="{{ $kpi->kpi_type }}" 
						  data-is_upload="{{ $kpi->is_upload }}" 
						  data-kpa_id="{{ $kpi->kpa_id }}" 
						  data-category_id="{{ $kpi->category_id }}" 
						  data-upload_type="{{ $kpi->upload_type }}" 
						  data-weight="{{ $kpi->weight }}"><i class="fa fa-pencil-square-o"></i> Edit</button></td>
						  <td>{{!empty($kpi->cat_name) ? $kpi->cat_name : ''}}</td>
						  <td>{{!empty($kpi->kpa_name) ? $kpi->kpa_name : ''}}</td>
						  <td>{{!empty($kpi->indicator) ? $kpi->indicator : ''}}</td>
						  <td>{{!empty($kpi->measurement) ? $kpi->measurement : ''}}</td>
						  <td>{{!empty($kpi->source_of_evidence) ? $kpi->source_of_evidence : ''}}</td>
						  <td>{{!empty($kpi->weight) ? $kpi->weight : ''}}</td>
						  <td>{{($kpi->is_upload == 1) && !empty($kpi->is_upload) ? 'Yes' : 'No'}}</td>
						  <td>{{(!empty($kpi->upload_type)) && !empty($kpi->upload_type) ? $KpiUploadTypeArray[$kpi->upload_type] : 'N/A'}}</td>
<<<<<<< HEAD
						  <td><button type="button" id="view_kpi" class="btn btn-xs" onclick="postData({{$kpi->id}}, '{{!empty($kpi->kpi_type) ? $KpiTypeArray[$kpi->kpi_type] : '' }}');">{{ !empty($kpi->kpi_type) ? $KpiTypeArray[$kpi->kpi_type] : ''}}</td>
=======
						  <td><button type="button" id="view_kpi" class="btn btn-xs" onclick="postData({{$kpi->id}}, '{{!empty($kpi->kpi_type) ? $KpiTypeArray[$kpi->kpi_type] : '' }}');">{{!empty($kpi->kpi_type) ? $KpiTypeArray[$kpi->kpi_type] : '' }}</td>
>>>>>>> cc217c142cfad18ac8cc98627d2101a8b99bbeaa
						  <td nowrap>
                              <button type="button" id="view_kpi" class="btn {{ (!empty($kpi->status) && $kpi->status == 1) ? "btn-danger" : "btn-success" }} btn-xs" onclick="postData({{$kpi->id}}, 'actdeac');"><i class="fa {{ (!empty($kpi->status) && $kpi->status == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($kpi->status) && $kpi->status == 1) ? "De-Activate" : "Activate"}}</button>
                          </td>
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
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
				<button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
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

	<!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
	
    <script>
		function postData(id, data)
		{
			if (data == 'actdeac')
				location.href = "/appraisal/kpi_active/" + id;
			else if (data == 'Range')
				location.href = "/appraisal/kpi_range/" + id;
			else if (data == 'Number')
				location.href = "/appraisal/kpi_number/" + id;
			else if (data == 'From 1 To 10')
				location.href = "/appraisal/kpi_from_to/" + id;
		}
        $(function () {
            var kpiId;
            //Tooltip
            $('[data-toggle="tooltip"]').tooltip();
			
			document.getElementById("back_button").onclick = function () {
			location.href = "/appraisal/templates";	};
			
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
                kpiId = btnEdit.data('id');
                var Measurement = btnEdit.data('measurement');
                var Weight = btnEdit.data('weight');
                var KpiType = btnEdit.data('kpi_type');
                var IsUpload = btnEdit.data('is_upload');
                var UploadType = btnEdit.data('upload_type');
                var SourceOfEvidence = btnEdit.data('source_of_evidence');
                var Indicator = btnEdit.data('indicator');
                var kpaId = btnEdit.data('kpa_id');
                var CategoryId = btnEdit.data('category_id');
                var modal = $(this);
                modal.find('#measurement').val(Measurement);
                modal.find('#weight').val(Weight);
                modal.find('#kpi_type').val(KpiType);
                modal.find('#is_upload').val(IsUpload);
                modal.find('#upload_type').val(UploadType);
                modal.find('#source_of_evidence').val(SourceOfEvidence);
                modal.find('#indicator').val(Indicator);
                modal.find('#kpa_id').val(kpaId);
                modal.find('#category_id').val(CategoryId);
				$('select#category_id').val(CategoryId);
				$('select#kpa_id').val(kpaId);
				$('select#kpi_type').val(KpiType);
				$('select#is_upload').val(IsUpload);
				$('select#upload_type').val(UploadType);
            });

            //function to post category form to server using ajax
            function postModuleForm(formMethod, postUrl, formName) {
                $.ajax({
                    method: formMethod,
                    url: postUrl,
                    data: {
                        measurement: $('form[name=' + formName + ']').find('#measurement').val(),
                        weight: $('form[name=' + formName + ']').find('#weight').val(),
                        source_of_evidence: $('form[name=' + formName + ']').find('#source_of_evidence').val(),
                        indicator: $('form[name=' + formName + ']').find('#indicator').val(),
                        kpi_type: $('form[name=' + formName + ']').find('#kpi_type').val(),
                        is_upload: $('form[name=' + formName + ']').find('#is_upload').val(),
                        upload_type: $('form[name=' + formName + ']').find('#upload_type').val(),
                        kpa_id: $('form[name=' + formName + ']').find('#kpa_id').val(),
                        category_id: $('form[name=' + formName + ']').find('#category_id').val(),
                        existing_kpi_id: $('form[name=' + formName + ']').find('#existing_kpi_id').val(),
                        template_id: {{$template->id}},
                         _token: $('input[name=_token]').val()
                    },
                    success: function(success) {
                        location.href = "/appraisal/template/" + {{$template->id}};
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
                postModuleForm('PATCH', '/appraisal/kpi_edit/' + kpiId, 'edit-kpi-form');
            });
			$('#old_kpi').hide();
			//call hide/show fields functions on doc ready
           // $('#upload_type_div').hide();
        });
		//function to hide/show manual or file upload fields
        function hideFields() 
		{
            var uploadID = $("#is_upload").val();
            if (uploadID == 1)
			{
				$('#upload_type_div').show();
				$("#upload_type").prop('disabled', false);
				$("upload_type").prop('required',true);
			}
            else if (uploadID == 2)
			{
				$('#upload_type_div').hide();
				$("#upload_type").prop('disabled', true);
				$("upload_type").prop('required',false);
			}
        }
		function hideexisting() 
		{
            var kpi = $("#existing").val();
            if (kpi == 1)
			{
				$('.existing_one').show();
				$('#old_kpi').hide();
				$("#existing_kpi_id").prop('disabled', true);
				$("existing_kpi_id").prop('required',false)
				
				$("#upload_type").prop('disabled', false);
				$("#is_upload").prop('disabled', false);
				$("#kpi_type").prop('disabled', false);
				$("#weight").prop('disabled', false);
				$("#measurement").prop('disabled', false);
				$("#source_of_evidence").prop('disabled', false);
				$("#indicator").prop('disabled', false);
				$("#kpa_id").prop('disabled', false);
				$("#category_id").prop('disabled', false);
				$("is_upload").prop('required',true)
				$("kpi_type").prop('required',true)
				$("weight").prop('required',true)
				$("indicator").prop('required',true)
				$("kpa_id").prop('required',true)
				$("category_id").prop('required',true)
			}
            else if (kpi == 2)
			{
				$('.existing_one').hide();
				$('#old_kpi').show();
				$("#existing_kpi_id").prop('disabled', false);
				$("existing_kpi_id").prop('required',true)
				
				$("#upload_type").prop('disabled', true);
				$("#is_upload").prop('disabled', true);
				$("#kpi_type").prop('disabled', true);
				$("#weight").prop('disabled', true);
				$("#measurement").prop('disabled', true);
				$("#source_of_evidence").prop('disabled', true);
				$("#indicator").prop('disabled', true);
				$("#kpa_id").prop('disabled', true);
				$("#category_id").prop('disabled', true);
				$("is_upload").prop('required',false)
				$("kpi_type").prop('required',false)
				$("weight").prop('required',false)
				$("indicator").prop('required',false)
				$("kpa_id").prop('required',false)
				$("category_id").prop('required',false)
			}
        }
    </script>
@endsection