@extends('layouts.main_layout')
@section('page_dependencies')
 <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
        <!-- iCheck -->
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/green.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Public Holidays</h3>
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
                        <th>Description</th>
                        <th>Day</th>
                        <th>Year</th>
                     </tr>
                    @if (count($holidays) > 0)
						@foreach($holidays as $holiday)
						 <tr id="holidays-list">
						  <td nowrap>
                              <button type="button" id="edit_holiday" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-holiday-modal" data-id="{{ $holiday->id }}" data-name="{{ $holiday->name }}" data-year="{{ $holiday->year }}" 
							  data-country_id="{{ $holiday->country_id }}" data-day = "{{ date(' d M Y', $holiday->day)}}"><i class="fa fa-pencil-square-o"></i> Edit</button>
                          </td>
                          <td>{{ $holiday->name }} </td>
						  <td>
						  {{ !empty($holiday->day) ? date('d M Y', $holiday->day) : '' }}</td>
						  <td>
							{{ (!empty($holiday->year)) ? $holiday->year: ''  }}
						  </td>
						  <td>{{ $holiday->country }}</td>
						  <td><button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#delete-record-warning-modal" data-id="{{ $holiday->id }}"><i class="fa fa-trash"></i> Delete</button></td>
						</tr>
						@endforeach
                    @else
						<tr id="holidays-list">
						<td colspan="5">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            No Public Holiday to display, please start by adding a new holiday.
                        </div>
						</td>
						</tr>
                    @endif
				</table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="button" id="add-new-holiday" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-new-holiday-modal">Add New Public Holiday</button>
                </div>
            </div>
        </div>

        <!-- Include add new prime rate modal -->
        @include('security.partials.add_new_holiday')
        @include('security.partials.edit_holiday')
		@include('security.warnings.delete_warning_action', ['modal_title' => 'Delete safe', 'modal_content' => 'Are you sure you want to delete this record ? This action cannot be undone.'])
    </div>
@endsection

@section('page_script')
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
	<!-- bootstrap datepicker -->
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
	<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <script>
        $(function () {
            var holidayId;
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
			$('#holiday_date').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
			});
			$('#day').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
			});
			/*$('input').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green',
				increaseArea: '20%' // optional
			});*/
            //pass holiday data to the edit holiday modal
            $('#edit-holiday-modal').on('shown.bs.modal', function (e) {
                var btnEdit = $(e.relatedTarget);
                holidayId = btnEdit.data('id');
				/*year="{{ $holiday->year }}" 
							  data-country_id="{{ $holiday->country_id }}" data-day = "{{ date(' d M Y', $holiday->day*/
                var Name = btnEdit.data('name');
                var Day = btnEdit.data('day');
                var countryId = btnEdit.data('country_id');
                var Year = btnEdit.data('year');
                var modal = $(this);
                modal.find('#name').val(Name);
                modal.find('#day').val(Day);
				if (Year !== '')
					$('#year_once').prop('checked', true);
				modal.find('select#country_id').val(countryId);
				console.log(countryId);
            });
			// delete record
			$('#delete-record-warning-modal').on('show.bs.modal', function (e) {
				var btnEdit = $(e.relatedTarget);
				holidayId = btnEdit.data('id');
				var modal = $(this);
			});
            //Post holiday form to server using ajax (ADD)
            $('#add-holiday').on('click', function() {
                var strUrl = '/users/public-holiday';
                var formName = 'add-holiday-form';
                var modalID = 'add-new-holiday-modal';
                var submitBtnID = 'add-holiday';
                var redirectUrl = '/users/public-holiday';
                var successMsgTitle = 'Public Holiday Added!';
                var successMsg = 'The Public Holiday Has Been Successfully Saved!';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            //Update holiday
            $('#update-holiday').on('click', function() {
                var strUrl = '/users/holiday_edit/' + holidayId;
                var formName = 'edit-holiday-form';
                var modalID = 'edit-holiday-modal';
                var submitBtnID = 'update-holiday';
                var redirectUrl = '/users/public-holiday';
                var successMsgTitle = 'Public Holiday Updated!';
                var successMsg = 'Your Changes Has Been Successfully Saved!';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
			///
			 $('#delete_record').on('click', function () {
				var strUrl = '/users/public-holiday/delete/'+ holidayId;
				var modalID = 'delete-record-warning-modal';
				var objData = {
					_token: $('#' + modalID).find('input[name=_token]').val()
				};
				var submitBtnID = 'delete_record';
				var redirectUrl = '/users/public-holiday';
			   //var Method = 'PATCH';
				modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl);
			});
        });
    </script>
@endsection