@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Company {{$highestLvl->plural_name}}</h3>
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
                                <th>Manager's Name</th>
                                <th>Address</th>
                                <th style="width: 5px; text-align: center;"></th>
                            </tr>
                              @if (count($highestLvl->stockLevelGroup) > 0)
                            @foreach ($highestLvl->stockLevelGroup as $type)
                                <tr id="stockLevelGroup-list">
                                     <td nowrap>
                                        <button type="button" id="edit_compan" class="btn btn-primary  btn-xs" 
										data-toggle="modal" data-target="#edit-company-modal" 
										data-id="{{ $type->id }}" data-name="{{ $type->name }}" 
										data-store_address="{{ $type->store_address }}" 
										data-manager_id="{{$type->manager_id}}" >
										<i class="fa fa-pencil-square-o"></i> Edit</button>
                                        @if($highestLvl->level > $lowestactiveLvl && $type->childStock())
                                            <a href="/stock/child_setup/{{$highestLvl->level}}/{{$type->id}}" id="edit_compan" class="btn btn-primary  btn-xs"   data-id="{{ $type->id }}" data-name="{{ $type->name }}" data-manager_id="{{$type->manager_id}}" ><i class="fa fa-eye"></i> {{$childLevelname}}</a>
                                        @endif
                                    </td>
                                    <td>{{ $type->name }}</td>
                                    <td>{{ ($type->stockManager) ? $type->stockManager->first_name." ".$type->stockManager->surname : ''}}</td>
                                    <td>{{ ($type->store_address) ? $type->store_address : ''}}</td>
                                    <td>
                                          <!--   <button type="button" id="view_ribbons" class="btn 11111111111111111111111{{ (!empty($type->active) && $type->active == 1) ? "btn-danger" : "btn-success" }} btn-xs" onclick="postData({{$type->id}}) , 'dactive';"><i class="fa {{ (!empty($type->active) && $type->active == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($type->active) && $type->active == 1) ? "De-Activate" : "Activate"}}</button> -->
                                    <button type="button" id="view_ribbons" class="btn {{ (!empty($type->active) && $type->active == 1) ? " btn-danger " : "btn-success " }}
                                      btn-xs" onclick="postData({{$type->id}}, 'dactive');"><i class="fa {{ (!empty($type->active) && $type->active == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($type->active) && $type->active == 1) ? "De-Activate" : "Activate"}}</button>
                                      
                                    </td>
                                </tr>    
                            @endforeach
                        @else
                        <tr id="stockLevelGroup-list">
                        <td colspan="6">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            No Stock level to display, please start by adding a new Stock.
                        </div>
                        </td>
                        </tr>
                    @endif
                        </table>
                    </div>
                        <!-- /.box-body -->
                    <div class="box-footer">
                     <button type="button" id="level_module" class="btn btn-primary pull-right" data-toggle="modal" data-target="#level-module-modal">Add {{$highestLvl->name}}</button>  
                    </div>
			</div>
        <!-- Include add new prime rate modal-->
        @include('stock.partials.level_module')
        @include('stock.partials.edit_company_modal')
		</div>
	</div>
@endsection

@section('page_script')
<!-- Ajax form submit -->
<script src="/custom_components/js/modal_ajax_submit.js"></script>
<script>
	function postData(id, data)
	{
		 if (data == 'dactive') location.href = "/stock/store_edit/" + "{{ $highestLvl->id }}/" + id + '/activate';
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

	var updatecompanyID;
	$('#edit-company-modal').on('show.bs.modal', function (e) {
			//console.log('kjhsjs');
		var btnEdit = $(e.relatedTarget);
		updatecompanyID = btnEdit.data('id');
		var name = btnEdit.data('name');
		var storeAddress = btnEdit.data('store_address');
		var manager_id = btnEdit.data('manager_id');
		var level = btnEdit.data('level');
		//var employeeName = btnEdit.data('employeename');
		var modal = $(this);
		modal.find('#name').val(name);
		modal.find('#store_address').val(storeAddress);
		modal.find('#manager_id').val(manager_id);
	 });

	//Post module form to server using ajax (ADD)
	$('#save_firstlevel').on('click', function() {
		//console.log('strUrl');
		var strUrl = '/stock/firstlevelstock/add/'+ '{{ $highestLvl->id }}';
		var modalID = 'level-module-modal';
		var objData = {
			name: $('#'+modalID).find('#name').val(),
			store_address: $('#'+modalID).find('#store_address').val(),
			manager_id: $('#'+modalID).find('#manager_id').val(),
			_token: $('#'+modalID).find('input[name=_token]').val()
		};
		var submitBtnID = 'level_module';
		var redirectUrl = '/stock/store_management';
		var successMsgTitle = 'Changes Saved!';
		var successMsg = 'The group level has been updated successfully.';
		//var formMethod = 'PATCH';
		modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
	});

	$('#update_company-modal').on('click', function () {
			var strUrl = '/stock/level_edit/{{ $highestLvl->id }}/' + updatecompanyID;
			var modalID = 'edit-company-modal';
			var objData = {
				name: $('#'+modalID).find('#name').val(),
				store_address: $('#'+modalID).find('#store_address').val(),
				manager_id: $('#'+modalID).find('#manager_id').val(),
				 _token: $('#'+modalID).find('input[name=_token]').val()
			};
			var submitBtnID = 'update_company-modal';
			var redirectUrl = '/stock/store_management';
			var successMsgTitle = 'Changes Saved!';
			var successMsg = 'Company modal has been updated successfully.';
			var Method = 'PATCH';
			modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
		});
	});
</script>
@endsection