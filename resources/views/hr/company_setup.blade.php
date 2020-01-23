@extends('layouts.main_layout')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{$highestLvl->plural_name}}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <form class="form-horizontal" method="POST" action="/hr/firstlevel">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered"> 
                            <tr>
                                <th style="width: 10px; text-align: center;"></th>
                                <th>Name</th>
                                <th>Manager</th>
                                <th>HR</th>
                                <th>Payroll</th>
                                <th style="width: 5px; text-align: center;"></th>
                            </tr>
                            @if (count($highestLvl->divisionLevelGroup) > 0)
								@foreach ($highestLvl->divisionLevelGroup as $type)
									<tr id="divisionLevelGroup-list">
										<td nowrap>
											<button type="button" id="edit_compan" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-division-modal" data-id="{{ $type->id }}" data-name="{{ $type->name }}" data-manager_id="{{$type->manager_id}}"
												data-hr_manager_id="{{$type->hr_manager_id}}" data-payroll_officer="{{$type->payroll_officer}}"><i class="fa fa-pencil-square-o"></i> Edit</button>
											@if($highestLvl->level > $lowestactiveLvl && $type->childDiv())
                                            <a href="/hr/child_setup/{{$highestLvl->level}}/{{$type->id}}" id="edit_compan" class="btn btn-primary  btn-xs"   data-id="{{ $type->id }}" data-name="{{ $type->name }}" data-manager_id="{{$type->manager_id}}" ><i class="fa fa-eye"></i> {{$childLevelname}}</a>
											@endif
										</td>
										<td>{{ $type->name }}</td>
										<td>{{ ($type->manager) ? $type->manager->first_name." ".$type->manager->surname : ''}}</td>
										<td>{{ ($type->hrManager) ? $type->hrManager->first_name." ".$type->hrManager->surname : ''}}</td>
										<td>{{ ($type->payrollOfficer) ? $type->payrollOfficer->first_name." ".$type->payrollOfficer->surname : ''}}</td>
										<td>
											<button type="button" id="view_ribbons" class="btn {{ (!empty($type->active) && $type->active == 1) ? " btn-danger " : "btn-success " }}
											btn-xs" onclick="postData({{$type->id}}, 'dactive');"><i class="fa {{ (!empty($type->active) && $type->active == 1) ?
											"fa-times " : "fa-check " }}"></i> {{(!empty($type->active) && $type->active == 1) ? "De-Activate" : "Activate"}}</button>
										</td>
									</tr>    
								@endforeach
							@else
								<tr id="divisionLevelGroup-list">
									<td colspan="5">
										<div class="alert alert-danger alert-dismissable">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
											No {{$highestLvl->name}} to display, please start by adding a new {{$highestLvl->name}}.
										</div>
									</td>
								</tr>
							@endif
                        </table>
                    </div>
                        <!-- /.box-body -->
                    <div class="box-footer">
                     <button type="button" id="level_module" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-division-modal">Add {{$highestLvl->name}}</button>  
                    </div>
				</form>
			</div>
        <!-- Include add new prime rate modal -->
        @include('hr.partials.add_division_modal')
        @include('hr.partials.edit_division_modal')
		</div>
    </div>
@endsection
@section('page_script')
<!-- Ajax form submit -->
<script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script>
		function postData(id, data)
		{
            if (data == 'dactive') location.href = "/hr/company_edit/" + "{{ $highestLvl->id }}/" + id + '/activate';
		}
        $(function () {
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
            var divisionID;
            $('#edit-division-modal').on('show.bs.modal', function (e) {
                var btnEdit = $(e.relatedTarget);
                divisionID = btnEdit.data('id');
                var name = btnEdit.data('name');
                var managerID = btnEdit.data('manager_id');
                var hrManagerID = btnEdit.data('hr_manager_id');
                var payrollOfficer = btnEdit.data('payroll_officer');
                var modal = $(this);
                modal.find('#name').val(name);
                modal.find('#manager_id').val(managerID);
                modal.find('#hr_manager_id').val(hrManagerID);
                modal.find('#payroll_officer').val(payrollOfficer);  
             });

            //Post module form to server using ajax (ADD)
            $('#save_firstlevel').on('click', function() {
                //console.log('strUrl');
                var strUrl = '/hr/firstleveldiv/add/'+ '{{ $highestLvl->id }}';
                var modalID = 'add-division-modal';
                var objData = {
                    name: $('#'+modalID).find('#name').val(),
                    manager_id: $('#'+modalID).find('#manager_id').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'save_firstlevel';
                var redirectUrl = '/hr/company_setup';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The group level has been updated successfully.';
                //var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

			$('#update_division').on('click', function () {
                var strUrl = '/hr/company_edit/{{ $highestLvl->id }}/' + divisionID;
                var modalID = 'edit-division-modal';
                var objData = {
                    name: $('#'+modalID).find('#name').val(),
                    manager_id: $('#'+modalID).find('#manager_id').val(),
                    hr_manager_id: $('#'+modalID).find('#hr_manager_id').val(),
                    payroll_officer: $('#'+modalID).find('#payroll_officer').val(),
                     _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'update_division';
                var redirectUrl = '/hr/company_setup';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'Division has been updated successfully.';
                var Method = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
            });
		});
    </script>
@endsection