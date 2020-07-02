@extends('layouts.main_layout')
@section('page_dependencies')
	<!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/green.css">
@endsection
@section('content')
	<div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Procurement Setup</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <form class="form-horizontal" method="POST" 
				action="{{!empty($procurementSetup->id) ? '/procurement/setups/'.$procurementSetup->id : '/procurement/setups'}}">
                    {{ csrf_field() }}
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <th colspan="2" style="text-align:center">Settings</th>
                            </tr> 
							<div class="form-group">
								<tr>
                                    <td class="caption" colspan="2">Is Role General?</td>
                                    <input type="hidden" name="is_role_general" value="0">
                                    <td><input type="checkbox" name="is_role_general"
                                                           value="1" {{(!empty($procurementSetup->is_role_general) && $procurementSetup->is_role_general === 1) ? 'checked ="checked"' : 0 }}>

                                    </td>
                                </tr>
                                <tr>
                                    <td class="caption" colspan="2">Email PO To Supplier</td>
                                    <input type="hidden" name="email_po_to_supplier" value="0">
                                    <td colspan="3"><input type="checkbox" name="email_po_to_supplier"
                                                           value="1" {{(!empty($procurementSetup->email_po_to_supplier) && $procurementSetup->email_po_to_supplier === 1) ? 'checked ="checked"' : 0 }}>

                                    </td>
                                </tr>
								<tr id="show_role_tr">
									<td>Roles</td>
									<td>
										<select id="email_role" name="email_role" class="form-control select2"
												style="width: 100%;">
											<option value="0">*** Please Select a Role ***</option>
											@foreach($roles as $role)
												<option value="{{ $role->id }}">{{ $role->description}}
												{{ ($procurementSetup->email_role == $role->id) ?
                                                ' selected' : '' }}</option>
											@endforeach
										</select>
									</td>
								</tr>
								<tr>
									<td>Amount Required Double Approval</td>
									<td>
										<input type="text" class="form-control" id="amount_required_double" name="amount_required_double" value="{{ !empty($procurementSetup->amount_required_double) ? $procurementSetup->amount_required_double : '' }}" placeholder="Enter Amount Required Double Approval">
									</td>
								</tr>
							</div>
                        </table>
                    </div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary"><i class="fa fa-database"></i> Submit</button>  
                    </div>
                </form>
			</div>
        <!-- Include add new prime rate modal -->
		</div>
    </div>
@endsection
@section('page_script')
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
	
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
	<script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js"
			type="text/javascript"></script>
	<!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
	<script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js"
			type="text/javascript"></script>
	<!-- the main fileinput plugin file -->
	<script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
	<!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
	<script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>

	<!-- InputMask -->
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
	<script>
        $(function () {
            //$('#show_role_tr').hide();
			$('input').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green',
				increaseArea: '20%' // optional
			});
			$('#email_po_to_supplier').on('ifChecked', function(event){
				alert('dddddd');
				$('#show_role_tr').show();
				$('#email_role').val('0');
			});
			$('#email_po_to_supplier').on('ifUnchecked', function(event){
				$('#show_role_tr').hide();
			});
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
        });
    </script>
@endsection
