@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{$childLevel->plural_name}} under <b>{{$parentDiv->name}}</b></h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <form class="form-horizontal" method="POST" action="/hr/firstchild">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered"> 
                            <tr>
                                <th style="width: 10px; text-align: center;"></th>
                                <th>Name</th>
                                <th>Manager's Name</th>
                                <th style="width: 5px; text-align: center;"></th>
                            </tr>
                            @foreach ($childStock as $type)
                                <tr>
                                    <td style=" text-align: center;" nowrap>
                                        <button type="button" id="edit_compan" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-child-modal" data-id="{{ $type->id }}" data-name="{{ $type->name }}" data-manager_id="{{$type->manager_id}}" ><i class="fa fa-pencil-square-o"></i> Edit</button>
                                        @if($childLevel->level>$lowestactiveLvl)
                                            <a href="/stock/child_setup/{{$childLevel->level}}/{{$type->id}}" id="manage_child" class="btn btn-primary  btn-xs"   data-id="{{ $type->id }}" data-name="{{ $type->name }}" data-manager_id="{{$type->manager_id}}" ><i class="fa fa-eye"></i> {{$curLvlChild->plural_name}}</a>
                                        @endif
                                    </td>
                                    <td>{{ $type->name }}</td>
                                    <td>{{ ($type->stockManager) ? $type->stockManager->first_name." ".$type->stockManager->surname : ''}}</td>
                                    <td>
                                         <button type="button" id="view_ribbons" class="btn {{ (!empty($type->active) && $type->active == 1) ? "btn-danger" : "btn-success" }} btn-xs" onclick="postData({{$type->id}}, 'dactiv');"><i class="fa {{ (!empty($type->active) && $type->active == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($type->active) && $type->active == 1) ? "De-Activate" : "Activate"}}</button> 
                                     <!--   <button type="button" id="view_ribbons" class="btn {{ (!empty($type->active) && $type->active == 1) ? " btn-danger " : "btn-success " }}
                                      btn-xs" onclick="postData({{$type->id}}, 'dactiv');"><i class="fa {{ (!empty($type->active) && $type->active == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($type->active) && $type->active == 1) ? "De-Activate" : "Activate"}}</button>-->
                                      
                                    </td>
                                </tr>    
                            @endforeach
                        </table>
                    </div>
                </form>
				<!-- /.box-body -->
				<div class="box-footer">
				 <button type="button" id="child_module" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-child-modal">Add {{$childLevel->name}}</button>
					 <button type="button" id="back" class="btn btn-default pull-left"><i class="fa fa-arrow-left"></i> Back</button>
				</div>
			</div>
      @include('stock.partials.add_child_level')
      @include('stock.partials.edit_child_modal')
    </div>
@endsection

@section('page_script')
<!-- Ajax form submit -->
<script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script>
		function postData(id, data)
		{
             if (data == 'dactiv')location.href = "/stock/store_edit/" + "{{ $parentLevel - 1 }}/" + id + '/activate'; 
		}
		document.getElementById("back").onclick = function () {
                location.href = "{{ $back }}";
            };
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

			var updatechildID;
			$('#edit-child-modal').on('show.bs.modal', function (e) {
				   // console.log('kjhsjs');
				var btnEdit = $(e.relatedTarget);
				updatechildID = btnEdit.data('id');
				var name = btnEdit.data('name');
				var manager_id = btnEdit.data('manager_id');
				var level = btnEdit.data('level');
				//var employeeName = btnEdit.data('employeename');
				var modal = $(this);
				modal.find('#name').val(name);
				modal.find('#manager_id').val(manager_id);
				
			});
       
            $('#save_childlevel').on('click', function() {
				var strUrl = '/stock/firstchild/add/' + '{{ $parentLevel }}/'+ '{{ $parentDiv->id }}'  ;
				var modalID = 'add-child-modal';
				var objData = {
					name: $('#'+modalID).find('#name').val(),
					manager_id: $('#'+modalID).find('#manager_id').val(),
					_token: $('#'+modalID).find('input[name=_token]').val()
				};
				var submitBtnID = 'child_module';
				var redirectUrl = '/stock/child_setup/' + '{{ $parentLevel }}/'+ '{{ $parentDiv->id }}' ;
				var successMsgTitle = 'Changes Saved!';
				var successMsg = 'The stock modal has been updated successfully.';
				modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);

			});

		   $('#update_child-modal').on('click', function () {
				var strUrl = '/stock/firstchild/{{ $parentLevel - 1 }}/' +  updatechildID;
				var modalID = 'edit-child-modal';
				var objData = {
					name: $('#'+modalID).find('#name').val(),
					manager_id: $('#'+modalID).find('#manager_id').val(),
					 _token: $('#'+modalID).find('input[name=_token]').val()
				};
				var submitBtnID = 'update_child-modal';
				var redirectUrl = '/stock/child_setup/'+ '{{ $parentLevel }}/'+ '{{ $parentDiv->id }}';
				var successMsgTitle = 'Changes Saved!';
				var successMsg = 'stock modal has been updated successfully.';
				var Method = 'PATCH';
				modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
            });
        });
    </script>
@endsection