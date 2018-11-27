@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Stock Levels</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <form class="form-horizontal" method="POST" action="/hr/grouplevel">
                  <!--   {{ csrf_field() }}
                    {{ method_field('PATCH') }} -->
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 10x; text-align: center;"></th>
                                <th>Level</th>
                                <th>Name</th>
                                <th>Plural Name</th>
                                <th style="width: 40px; text-align: center;"></th>
                            </tr>

                            @foreach ($stock_types as $stock_type)
                                <tr>
                                    <td style="width: 5px; text-align: center;"><button type="button" id="edit_grouplevel" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-grouplevel-modal" data-id="{{ $stock_type->id }}" data-name="{{ $stock_type->name }}" data-plural_name="{{ $stock_type->plural_name }}" data-level="{{ $stock_type->level }}"><i class="fa fa-pencil-square-o"></i> Edit</button></td>
                                    <td>Stock Level {{ $stock_type->level }}</td>
                                    <td>{{ $stock_type->name }}</td>
                                    <td>{{ $stock_type->plural_name }}</td>
                                    <td style="width: 5px; text-align: center;">
                                        @if ($stock_type->name!='')
                                            <button type="button" id="view_ribbons" class="btn {{ (!empty($stock_type->active) && $stock_type->active == 1) ? "btn-danger" : "btn-success" }} btn-xs" onclick="postData({{$stock_type->id}}, 'activateGroupLevel');"><i class="fa {{ (!empty($stock_type->active) && $stock_type->active == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($stock_type->active) && $stock_type->active == 1) ? "De-Activate" : "Activate"}}</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>

                        <!-- /.box-body -->

                </form>
			</div>
        <!-- Include add new prime rate modal -->
          @include('stock.partials.edit_group_level')
		</div>
    </div>
	<div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Stock Settings</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <form class="form-horizontal" method="POST" 
				action="{{!empty($stockSettings->id) ? '/stock/settings/'.$stockSettings->id : '/stock/settings'}}">
                    {{ csrf_field() }}
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <th colspan="2" style="text-align:center">Settings</th>
                            </tr> 
							<div class="form-group">
								<tr>
									<td>Unit Of Measurement</td>
									<td>
										<input type="text" class="form-control" id="unit_of_measurement" name="unit_of_measurement" value="{{ !empty($stockSettings->unit_of_measurement) ? $stockSettings->unit_of_measurement : '' }}" placeholder="Unit Of Measurement"  >
									</td>
								</tr>
							</div>
                        </table>
                    </div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary"><i class="fa fa-database"></i> Save Settings</button>  
                    </div>
                </form>
			</div>
        <!-- Include add new prime rate modal -->
		</div>
    </div>
	<div class="row">
		<form class="form-horizontal" method="post" action="{{!empty($stockSettings->id) ? '/stock_approval/settings/'.$stockSettings->id : '/stock_approval/settings'}}"> 
		 {{ csrf_field() }}
			<div class="col-sm-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Approval Settings</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table class="table table-bordered">
							<div class="form-group">
								<tr>
									<td>Require Manager's approval</td>
									<td style="text-align: center; vertical-align: middle;">
										<input   type="checkbox" name="require_managers_approval" value="1" {{ $stockSettings->require_managers_approval === 1 ? 'checked ="checked"' : 0 }}>
									</td>
								</tr>
							</div>
							<div class="form-group">
								<tr>
								   <td>Require Department Head Approval</td>
									<td style="text-align: center; vertical-align: middle;">
										<input   type="checkbox" name="require_department_head_approval" value="1" {{ $stockSettings->require_department_head_approval === 1 ? 'checked ="checked"' : 0 }}>
									</td>
								</tr>
							</div>
							<div class = "form-group">         
								<tr>
								<td>Require Store Manager Approve</td>
									<td style="text-align: center; vertical-align: middle;">
										<input  type="checkbox" name="require_store_manager_approval" value="1" {{ $stockSettings->require_store_manager_approval === 1 ? 'checked ="checked"' : 0 }} >
									</td>
								</tr>
							</div>
							<div class="form-group">
								<tr>
								<td>Require CEO Approval</td>
									<td style="text-align: center; vertical-align: middle;">
										<input  type="checkbox" name="require_ceo_approval" value="1" {{ $stockSettings->require_ceo_approval === 1 ? 'checked ="checked"' : 0 }}>
									</td>
								</tr>
							</div>
						</table>
					</div>
					<!-- /.box-body -->
					<div class="modal-footer">

						<button type="submit" class="btn btn-primary"><i class="fa fa-database"></i> save approval settings</button>
					</div>
				</div>
			</div>
		</form>

    </div>
@endsection

@section('page_script')
<!-- Ajax form submit -->
<script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script>
		function postData(id , data ){
           if (data == 'activateGroupLevel') location.href = '/stock/grouplevel/activate/' + id;
		}
        $(function () {
            var moduleId;
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

            //pass module data to the edit module modal
            var grouplevelID;
            $('#edit-grouplevel-modal').on('show.bs.modal', function (e) {
                var btnEdit = $(e.relatedTarget);
                grouplevelID = btnEdit.data('id');
                var grouplevelname = btnEdit.data('name');
                var grouplevelnamepluralname = btnEdit.data('plural_name');
                var level = btnEdit.data('level');
                var modal = $(this);
                modal.find('#group_level_title').html('Edit Stock Group Level '+ level);
                modal.find('#name').val(grouplevelname);
                modal.find('#plural_name').val(grouplevelnamepluralname);//
            });

            //Post module form to server using ajax (ADD)
            $('#save_grouplevel').on('click', function() {
                var strUrl = '/stock/grouplevel/'+grouplevelID;
                var modalID = 'edit-grouplevel-modal';
                var objData = {
                    name: $('#'+modalID).find('#name').val(),
                    plural_name: $('#'+modalID).find('#plural_name').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'save_grouplevel';
                var redirectUrl = '/stock/setup';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The group level has been updated successfully.';
                var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, formMethod);
            });
        });
    </script>
@endsection
