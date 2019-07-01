@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<!-- iCheck -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Step Approvals</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 10px; text-align: center;"></th>
                            <th style="text-align: center">Step number</th>
                            <th>Step name</th>
                            <th>Max Amount</th>
                            <th>Role</th>
							@if (!empty($LevelFive))
                            <th>{{$LevelFive->name}}</th>
							@endif
                            @if (!empty($LevelFour))
                            <th>{{$LevelFour->name}}</th>
							@endif
                            @if (!empty($LevelTHree))
                            <th>{{$LevelTHree->name}}</th>
							@endif
                            @if (!empty($LevelTwo))
                            <th>{{$LevelTwo->name}}</th>
							@endif
                            @if (!empty($LevelOne))
                            <th>{{$LevelOne->name}}</th>
							@endif
							<th>Employee</th>
							<th>Enforce Upload</th>
                            <th style="width: 5px; text-align: center;"></th>
                        </tr>
                        @if (count($processflows) > 0)
                            @foreach ($processflows as $processflow)
                                <tr id="categories-list">
                                    <td nowrap>
                                        <button vehice="button" id="edit_compan" class="btn btn-warning  btn-xs"
                                                data-toggle="modal" data-target="#edit-step-modal"
                                                data-id="{{ $processflow->id }}"
                                                data-step_number="{{ $processflow->step_number }}"
                                                data-step_name="{{$processflow->step_name}}"
                                                data-max_amount="{{$processflow->max_amount}}"
                                                data-employee_id="{{$processflow->employee_id}}"
                                                data-role_id="{{$processflow->role_id}}"
                                                data-division_id="{{!empty($processflow->division_id) ? $processflow->division_id : 0}}"
                                                data-division_level_5="{{!empty($processflow->division_level_5) ? $processflow->division_level_5 : 0}}"
                                                data-division_level_4="{{!empty($processflow->division_level_4) ? $processflow->division_level_4 : 0 }}"
                                                data-division_level_3="{{!empty($processflow->division_level_3) ? $processflow->division_level_3 : 0 }}"
                                                data-division_level_2="{{!empty($processflow->division_level_2) ? $processflow->division_level_2 : 0 }}"
                                                data-division_level_1="{{!empty($processflow->division_level_1) ? $processflow->division_level_1 : 0 }}"
                                                data-enforce_upload="{{!empty($processflow->enforce_upload) ? $processflow->enforce_upload : 0 }}">
                                            <i class="fa fa-pencil-square-o"></i> Edit
                                        </button>
                                    </td>
                                    <td style="text-align: center">{{ (!empty( $processflow->step_number)) ?  $processflow->step_number : ''}} </td>
                                    <td>{{ (!empty( $processflow->step_name)) ?  $processflow->step_name : ''}} </td>
                                    <td>{{ (!empty( $processflow->max_amount)) ? 'R '. $processflow->max_amount : ''}} </td>
                                    <td>{{ (!empty( $processflow->roleDetails->description)) ?  $processflow->roleDetails->description : ''}} </td>
                                    @if (!empty($LevelFive))
										<td>{{ (!empty($processflow->divisionLevelFive->name)) ?  $processflow->divisionLevelFive->name : ''}}</td>
									@endif
									@if (!empty($LevelFour))
										<td>{{ (!empty($processflow->divisionLevelFour->name)) ?  $processflow->divisionLevelFour->name : ''}}</td>
									@endif
									@if (!empty($LevelTHree))
										<td>{{ (!empty($processflow->divisionLevelThree->name)) ?  $processflow->divisionLevelThree->name : ''}}</td>
									@endif
									@if (!empty($LevelTwo))
										<td>{{ (!empty($processflow->divisionLevelTwo->name)) ?  $processflow->divisionLevelTwo->name : ''}}</td>
									@endif
									@if (!empty($LevelOne))
										<td>{{ (!empty($processflow->divisionLevelOne->name)) ?  $processflow->divisionLevelOne->name : ''}}</td>
									@endif
									<td>{{ (!empty($processflow->employeeDetails->first_name)) ?  $processflow->employeeDetails->first_name ." ".$processflow->employeeDetails->surname : ''}}</td>
									<td>{{ (!empty($processflow->enforce_upload)) ?  $uploadArray[$processflow->enforce_upload] : 'None'}}</td>
									<td>
                                        <button type="button" id="view_ribbons"
                                                class="btn {{ (!empty($processflow->status) && $processflow->status == 1) ? " btn-danger " : "btn-success " }}
                                                        btn-xs" onclick="postData({{$processflow->id}}, 'actdeac');"><i
                                                    class="fa {{ (!empty($processflow->status) && $processflow->status == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($processflow->status) && $processflow->status == 1) ? "De-Activate" : "Activate"}}
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr id="categories-list">
                                <td colspan="10">
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        No Record to display, please start by adding a new Record....
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </table>
                    <div class="box-footer">
                        <button type="button" id="step" class="btn btn-warning pull-right" data-toggle="modal"
                                data-target="#add-new-step-modal">Add new Step
                        </button>
                    </div>
                </div>
            </div>
            <!-- Include add  modal -->
        @include('procurement.partials.add_step_approvals_modal')
        @include('procurement.partials.edit_step_approvls_modal')
        </div>
    </div>
@endsection
@section('page_script')
	<script src="/custom_components/js/modal_ajax_submit.js"></script>
	<!-- Select2 -->
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
	<!-- Ajax dropdown options load -->
	<script src="/custom_components/js/load_dropdown_options.js"></script>
	<script>
		function postData(id, data) {
			if (data == 'actdeac') location.href = "/procurement/process_act/" + id;
		}
		$('#back_button').click(function () {
			location.href = '/jobcards/set_up';
		});
		$(function () {
			var moduleId;
			//Initialize Select2 Elements
			$(".select2").select2();
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
			//Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
			// Reposition when a modal is shown
			$('.modal').on('show.bs.modal', reposition);
			// Reposition when the window is resized
			$(window).on('resize', function () {
				$('.modal:visible').each(reposition);
			});
			//Show success action modal
			$('#success-action-modal').modal('show');
			$(".js-example-basic-multiple").select2();
			//Post module form to server using ajax (ADD)
			$('#add_step').on('click', function () {
				var strUrl = '/procurement/add_step';
				var formName = 'add-new-step-form';
				var modalID = 'add-new-step-modal';
				var submitBtnID = 'add_step';
				var redirectUrl = '/procurement/approval_level';
				var successMsgTitle = 'New Step Added!';
				var successMsg = 'The Step has been Added successfully.';
				modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
			});

			var stepID;
			$('#edit-step-modal').on('show.bs.modal', function (e) {
				var btnEdit = $(e.relatedTarget);
				stepID = btnEdit.data('id');
				var step_number = btnEdit.data('step_number');
				var step_name = btnEdit.data('step_name');
				var maxAmount = btnEdit.data('max_amount');
				var EmployeeID = btnEdit.data('employee_id');
				var roleID = btnEdit.data('role_id');
				var enforceUpload = btnEdit.data('enforce_upload');
				var dept5 = btnEdit.data('division_level_5');
				var dept4 = btnEdit.data('division_level_4');
				var dept3 = btnEdit.data('division_level_3');
				var dept2 = btnEdit.data('division_level_2');
				var dept1 = btnEdit.data('division_level_1');
				var dept1 = btnEdit.data('division_level_1');
				var divisionID = btnEdit.data('division_id');
				var modal = $(this);
				modal.find('#step_number').val(step_number);
				modal.find('#step_name').val(step_name);
				modal.find('#max_amount').val(maxAmount);
				modal.find('select#role_id').val(roleID);
				modal.find('select#enforce_upload').val(enforceUpload);
				modal.find('select#division_id').val(divisionID);
				//Load divisions drop down
				//console.log(enforceUpload);
				var parentDDID = '';
				var loadAllDivs = 1;
				var firstDivDDID = null;
				var parentContainer = $('#edit-step-modal');
				@foreach($division_levels as $divisionLevel)
					//Populate drop down on page load
					var ddID = '{{ 'division_level_' . $divisionLevel->level }}';
					var postTo = '{!! route('divisionsdropdown') !!}';
					var selectedOption = '';
					var divLevel = parseInt('{{ $divisionLevel->level }}');

					if (divLevel == 5) selectedOption = dept5;
					else if(divLevel == 4) selectedOption = dept4;
					else if(divLevel == 3) selectedOption = dept3;
					else if(divLevel == 2) selectedOption = dept2;
					else if(divLevel == 1) selectedOption = dept1;
					var incInactive = -1;
					var loadAll = loadAllDivs;
					@if($loop->first)
						var selectFirstDiv = 0;
						var divHeadSpecific = 0;
						loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo, selectFirstDiv, divHeadSpecific, parentContainer);
						firstDivDDID = ddID;
					@else
						loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo, null, null, parentContainer);
					@endif
					parentDDID = ddID;
					loadAllDivs = 1;
				@endforeach
				modal.find('select#hr_person_id').val(EmployeeID);
				if (roleID > 0)
				{
					$('.role-field').show();
					$('.emp-field').hide();
					$( "#rdo_roles").prop( "checked", true );
				}
				else
				{
					$('.emp-field').show();
					$('.role-field').hide();
					$( "#rdo_emps").prop( "checked", true );
				}
			});
			// Update
			$('#update-step').on('click', function () {
				var strUrl = '/procument/edit_step/update/' + stepID;
				var formName = 'edit-step-form';
				var modalID = 'edit-step-modal';
				var submitBtnID = 'update-step';
				var successMsgTitle = 'Changes Saved!';
				var redirectUrl = '/procurement/approval_level';
				var successMsg = 'Step Details has been updated successfully.';
				modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
			});
			//Load divisions drop down
			var parentDDID = '';
			var loadAllDivs = 1;
			@foreach($division_levels as $division_level)
			//Populate drop down on page load
			var ddID = '{{ 'division_level_' . $division_level->level }}';
			var postTo = '{!! route('divisionsdropdown') !!}';
			var selectedOption = '';
			var divLevel = parseInt('{{ $division_level->level }}');
			var incInactive = -1;
			var loadAll = loadAllDivs;
			loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo);
			parentDDID = ddID;
			loadAllDivs = -1;
			@endforeach
			//call hide/show fields functions on doc ready
			hideFields();
			//show/hide file upload or manual fields on radio checked
			$('#rdo_role, #rdo_emp').on('ifChecked', function(){
				hideFields();
			});
			//show/hide file upload or manual fields on radio checked
			$('#rdo_roles, #rdo_emps').on('ifChecked', function(){
				hideField();
			});
		});
		//function to hide/show fields
		function hideFields() {
			var approvalType = $("input[name='approval_type']:checked").val();
			if (approvalType == 1) { 
				$('.role-field').show();
				$('.emp-field').hide();
			}
			else if (approvalType == 2) {
				$('.emp-field').show();
				$('.role-field').hide();
			}
		}
		//function to hide/show fields
		function hideField() {
			var approvalType = $("input[name='approval_types']:checked").val();
			if (approvalType == 1) { 
				$('.role-field').show();
				$('.emp-field').hide();
			}
			else if (approvalType == 2) {
				$('.emp-field').show();
				$('.role-field').hide();
			}
		}
	</script>
@endsection