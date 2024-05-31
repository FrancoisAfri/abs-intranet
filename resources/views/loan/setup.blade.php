@extends('layouts.main_layout')

@section('page_dependencies')
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="{{ asset('bower_components/AdminLTE/plugins/iCheck/square/blue.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css"/>
@stop

@section('content')
    <div class="row">
		<div class="col-sm-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Settings</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i
									class="fa fa-minus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i
									class="fa fa-remove"></i></button>
					</div>
				</div>
				<!-- /.box-header -->
				<form class="form-horizontal" method="post" !empty($loan_configuration) ? action="/loan/setup/{{$loan_configuration->id}}" : action="/loan/setup/">
					{{ csrf_field() }}
					<div class="box-body">
						<table class="table table-bordered">
							<div class="form-group">
								<tr>
									<td>Max Amount</td>
									<td>
										<label for="max_amount" class="control-label"></label>
										<input type="text" class="form-control"
											   id="max_amount"
											   name="max_amount"
											   value="{{ !empty($loan_configuration->max_amount) ?
													$loan_configuration->max_amount : '' }}"
											   placeholder="Enter Max Amount">
									</td>
								</tr>
							</div>
							<div class="form-group">
								<tr>
									<td>Upload Directory</td>
									<td>
										<label for="max_amount" class="control-label"></label>
										<input type="text" class="form-control"
											   id="loan_upload_directory"
											   name="loan_upload_directory"
											   value="{{ !empty($loan_configuration->loan_upload_directory) ?
													$loan_configuration->loan_upload_directory : '' }}"
											   placeholder="Enter Upload Directory">
									</td>
								</tr>
							</div>
							<div class="form-group">
								<tr>
									<td>First Approval</td>
									<td>
										<label for="first_approval" class="control-label"></label>
										<select class="form-control select2" style="width: 100%;"
												id="first_approval" name="first_approval">
											<option value="">*** Select Employee ***</option>
											@foreach($employees as $employee)
												<option value="{{ $employee->id }}" {{ ($loan_configuration->first_approval == $employee->id) ?
                                                ' selected' : '' }}>{{$employee->first_name . ' ' . $employee->surname }}</option>
											@endforeach
										</select>
									</td>
								</tr>
							</div>
							<div class="form-group">
								<tr>
									<td>Second Approval</td>
									<td>
										<label for="second_approval" class="control-label"></label>
										<select class="form-control select2" style="width: 100%;"
												id="second_approval" name="second_approval">
											<option value="">*** Select Employee ***</option>
											@foreach($employees as $employee)
												<option value="{{ $employee->id }}"  {{ ($loan_configuration->second_approval == $employee->id) ?
                                                ' selected' : '' }}>{{$employee->first_name . ' ' . $employee->surname }}</option>
											@endforeach
										</select>
									</td>
								</tr>
							</div>
							<div class="form-group">
								<tr>
									<td>HR</td>
									<td>
										<label for="hr" class="control-label"></label>
										<select class="form-control select2" style="width: 100%;"
												id="hr" name="hr">
											<option value="">*** Select Employee ***</option>
											@foreach($employees as $employee)
												<option value="{{ $employee->id }}" {{ ($loan_configuration->hr == $employee->id) ?
                                                ' selected' : '' }}>{{$employee->first_name . ' ' . $employee->surname }}</option>
											@endforeach
										</select>
									</td>
								</tr>
							</div>
							<div class="form-group">
								<tr>
									<td>Payroll</td>
									<td>
										<label for="payroll" class="control-label"></label>
										<select class="form-control select2" style="width: 100%;"
												id="payroll" name="payroll">
											<option value="">*** Select Employee ***</option>
											@foreach($employees as $employee)
												<option value="{{ $employee->id }}"  {{ ($loan_configuration->payroll == $employee->id) ?
                                                ' selected' : '' }}>{{$employee->first_name . ' ' . $employee->surname }}</option>
											@endforeach
										</select>
									</td>
								</tr>
							</div>
							<div class="form-group">
								<tr>
									<td>Finance</td>
									<td>
										<label for="finance" class="control-label"></label>
										<select class="form-control select2" style="width: 100%;"
												id="finance" name="finance">
											<option value="">*** Select Employee ***</option>
											@foreach($employees as $employee)
												<option value="{{ $employee->id }}"  {{ ($loan_configuration->finance == $employee->id) ?
                                                ' selected' : '' }}>{{$employee->first_name . ' ' . $employee->surname }}</option>
											@endforeach
										</select>
									</td>
								</tr>
							</div>
							<div class="form-group">
								<tr>
									<td>Finance Second</td>
									<td>
										<label for="finance_second" class="control-label"></label>
										<select class="form-control select2" style="width: 100%;"
												id="finance_second" name="finance_second">
											<option value="">*** Select Employee ***</option>
											@foreach($employees as $employee)
												<option value="{{ $employee->id }}"  {{ ($loan_configuration->finance_second == $employee->id) ?
                                                ' selected' : '' }}>{{$employee->first_name . ' ' . $employee->surname }}</option>
											@endforeach
										</select>
									</td>
								</tr>
							</div>
						</table>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary"><i class="fa fa-database"></i> Save settings
						</button>
					</div>
				</form>
			</div>
		</div>
    </div>
@endsection
<!-- Ajax form submit -->
@section('page_script')
    <script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>

    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <script src="{{ asset('bower_components/AdminLTE/plugins/iCheck/icheck.min.js')}}"></script>
    <script src="/custom_components/js/deleteAlert.js"></script>
        <script src="/custom_components/js/dataTable.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <!-- InputMask -->

    <script>

        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#ers_token_number");

        togglePassword.addEventListener("click", function () {
            // toggle the type attribute
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);

            // toggle the icon
            this.classList.toggle("bi-eye");
        });

        $('.delete_confirm').click(function (event) {

            let form = $(this).closest("form");

            let name = $(this).data("name");

            event.preventDefault();

            swal({

                title: `Are you sure you want to delete this record?`,
                text: "If you delete this, it will be gone forever.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                        swal("Poof! Your Record has been deleted!", {
                            icon: "success",
                        });
                    }

                });

        });

        // prevent form submit
        const form = document.querySelector("form");
        form.addEventListener('submit', function (e) {
            e.preventDefault();
        });

        function postData(id, data) {
            //if (data == 'actdeac') location.href = "/leave/types/activate" + id;
            if (data == 'ribbons') location.href = "/leave/ribbons/" + id;
            else if (data == 'edit') location.href = "/leave/leave_edit/" + id;
            else if (data == 'actdeac') location.href = "/leave/setup/" + id; //leave_type_edit
            //  else if (data == 'cu_actdeac') location.href = "/leave/custom/leave_type_edit/" + id;
            //		 	else if (data == 'access')
            //		 		location.href = "/leave/module_access/" + id;
        }


        $(function () {

            //Initialize Select2 Elements
            $(".select2").select2();

            let moduleId;
            //Tooltip
            $('[data-toggle="tooltip"]').tooltip();

            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '10%' // optional
            });


            // Reposition when a modal is shown
            $('.modal').on('show.bs.modal', reposition);
            // Reposition when the window is resized


            let leavesetupId;
            $('#edit-leave_taken-modal').on('show.bs.modal', function (e) {
                //console.log('kjhsjs');
                let btnEdit = $(e.relatedTarget);
                leavesetupId = btnEdit.data('id');
                console.log('leavesetupID: ' + leavesetupId);
                let name = btnEdit.data('name');
                let day5min = btnEdit.data('day5min');
                let day5max = btnEdit.data('day5max');
                let day6min = btnEdit.data('day6min');
                let day6max = btnEdit.data('day6max');
                let shiftmin = btnEdit.data('shiftmin');
                let shiftmax = btnEdit.data('shiftmax');

                // var moduleFontAwesome = btnEdit.data('font_awesome');
                let modal = $(this);
                modal.find('#name').val(name);
                modal.find('#day5min').val(day5min);
                modal.find('#day5max').val(day5max);
                modal.find('#day6min').val(day6min);
                modal.find('#day6max').val(day6max);
                modal.find('#shiftmin').val(shiftmin);
                modal.find('#shiftmax').val(shiftmax);
                //if(primeRate != null && primeRate != '' && primeRate > 0) {
                //    modal.find('#prime_rate').val(primeRate.toFixed(2));
                //}
            });
            // pass module data to the custom leave  -edit module modal
            //****leave type post
            $('#update-leave_taken').on('click', function () {
                var strUrl = '/leave/setup/leave_type_edit/' + leavesetupId;
                var objData = {
                    day5min: $('#edit-leave_taken-modal').find('#day5min').val()
                    , day5max: $('#edit-leave_taken-modal').find('#day5max').val()
                    , day6min: $('#edit-leave_taken-modal').find('#day6min').val()
                    , day6max: $('#edit-leave_taken-modal').find('#day6max').val()
                    , shiftmin: $('#edit-leave_taken-modal').find('#shiftmin').val()
                    , shiftmax: $('#edit-leave_taken-modal').find('#shiftmax').val()
                    , _token: $('#edit-leave_taken-modal').find('input[name=_token]').val()
                };
                //console.log('gets here ' + JSON.stringify(objData));
                let modalID = 'edit-leave_taken-modal';
                let submitBtnID = 'update-leave_taken';
                let redirectUrl = '/leave/setup';
                let successMsgTitle = 'Changes Saved!';
                let successMsg = 'Leave days has been successfully added.';
                // var method = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });                        // ----edit setup leave days ------
        });

        //#leave cresdit settings
        $('#save_leave_credit').on('click', function () {
            let strUrl = '/leave/custom/add_leave';
            let objData = {
                hr_id: $('#add-custom-leave-modal').find('#hr_id').val()
                , number_of_days: $('#add-custom-leave-modal').find('#number_of_days').val()
                , _token: $('#add-custom-leave-modal').find('input[name=_token]').val()
            };
            let modalID = 'add-custom-leave-modal';
            let submitBtnID = 'add_custom_leave';
            let redirectUrl = '/leave/types';
            let successMsgTitle = 'Changes Saved!';
            let successMsg = 'Leave has been successfully added.';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });


        /**
         * Add Exempted users
         */
        $('#add-exempted').on('click', function () {

            let strUrl = '{{ route('exempted_users') }}';
            let modalID = 'add-exempted-modal';
            let formName = 'add-exempted-form';

            let submitBtnID = 'add-exempted';
            let redirectUrl = '/leave/setup';
            let successMsgTitle = 'User Added to List!';
            let successMsg = 'Record has been updated successfully.';

            modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });

        /**
         * Add managers
         */

        $('#add-manager').on('click', function () {

            let strUrl = '{{ route('manager_report') }}';
            let modalID = 'add-managers-modal';
            let formName = 'add-manager_module-form';

            let submitBtnID = 'add-manager';
            let redirectUrl = '/leave/setup';
            let successMsgTitle = 'Manager Added to List!';
            let successMsg = 'Record has been updated successfully.';

            modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });

		/**
         * Add leave notification users
         */

        $('#add-leave-users').on('click', function () {

            let strUrl = '{{ route('leave_not_user') }}';
            let modalID = 'add-leave-notification-modal';
            let formName = 'add-leave-notification-form';

            let submitBtnID = 'add-leave-users';
            let redirectUrl = '/leave/setup';
            let successMsgTitle = 'User Added to List!';
            let successMsg = 'Record has been updated successfully.';

            modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });
        //UPDATE

        let updateNegativeID;
        $('#edit-annual-modal').on('show.bs.modal', function (e) {
            let btnEdit = $(e.relatedTarget);

            updateNegativeID = btnEdit.data('id');
            let number_of_days_annual = btnEdit.data('number_of_days_annual');
            //console.log(number_of_days_annual);
            let modal = $(this);
            modal.find('#number_of_days_annual').val(number_of_days_annual);

        });

        let updateSickID;
        $('#edit-sick-modal').on('show.bs.modal', function (e) {
            let btnEdit = $(e.relatedTarget);

            updateSickID = btnEdit.data('id');
            let number_of_days_sick = btnEdit.data('number_of_days_sick');
            // console.log(number_of_days_sick);
            let modal = $(this);
            modal.find('#number_of_days_sick').val(number_of_days_sick);

        });

        //SAVE

        $('#update_annual').on('click', function () {
            let strUrl = '/leave/setup/' + '1';
            let objData = {
                number_of_days_annual: $('#edit-annual-modal').find('#number_of_days_annual').val()
                , _token: $('#edit-annual-modal').find('input[name=_token]').val()
            };
            let modalID = 'edit-annual-modal';
            let submitBtnID = 'edit_annual';
            let redirectUrl = '/leave/setup';
            let successMsgTitle = 'Changes Saved!';
            let successMsg = 'Leave has been successfully added.';
            let formMethod = 'PATCH';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, formMethod);
        });

        $('#update-sick').on('click', function () {
            let strUrl = '/leave/setup/' + '1/' + 'sick';
            let objData = {
                number_of_days_sick: $('#edit-sick-modal').find('#number_of_days_sick').val()
                , _token: $('#edit-sick-modal').find('input[name=_token]').val()
            };
            let modalID = 'edit-sick-modal';
            let submitBtnID = 'edit_sick';
            let redirectUrl = '/leave/setup';
            let successMsgTitle = 'Changes Saved!';
            let successMsg = 'Leave has been successfully added.';
            let formMethod = 'PATCH';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, formMethod);
        });


    </script>

@endsection
