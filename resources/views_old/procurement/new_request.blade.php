@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- iCheck -->
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/green.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
    <!-- DataTables -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
	 <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">   
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <form class="form-horizontal" method="POST" action="/procurement/adjust-request">
				<input type="hidden" name="contact_email" id="contact_email" value="">
                    {{ csrf_field() }}
                    <div class="box-header with-border">
                        <h3 class="box-title">New Request</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-ban"></i> Invalid Input Data!</h4>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="form-group{{ $errors->has('item_type') ? ' has-error' : '' }}">
                            <label for="item_type" class="col-sm-2 control-label"> Request Type</label>
                            <div class="col-sm-9">
                                <label class="radio-inline rdo-iCheck" style="padding-left: 0px;"><input type="radio" id="rdo_stock" name="item_type" value="1" checked> Stock Items</label>
                                <label class="radio-inline rdo-iCheck"><input type="radio" id="rdo_nonstock" name="item_type" value="2">  Non Stock Items</label>
                            </div>
                        </div>
						<div class="form-group">
							<label for="title_name" class="col-sm-2 control-label">Title</label>
							<div class="col-sm-10">
								<div class="input-group">
									<input type="text" class="form-control" id="title_name" name="title_name" value="" placeholder="Enter Title">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="employee_id" class="col-sm-2 control-label">Employee</label>
							<div class="col-sm-10">
								<div class="input-group">
									<select id="employee_id" name="employee_id" style="width: 100%;" class="form-control">
										@foreach($employees as $employee)
											<option value="{{ $employee->id }}">{{$employee->first_name . ' ' .  $employee->surname }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="on_behalf" class="col-sm-2 control-label"> On Behalf Of</label>
							<div class="col-sm-10">
								<div class="input-group">
									<input type="checkbox" id="on_behalf" value="1" name="on_behalf">
								</div>
							</div>
						</div>
						<div class="form-group on_behalf_field">
							<label for="on_behalf_employee_id" class="col-sm-2 control-label">Employees</label>
							<div class="col-sm-10">
								<select id="on_behalf_employee_id" name="on_behalf_employee_id"
								style="width: 100%;" class="form-control select2">
									<option value="0">*** Select an Employees ***</option>
									@foreach($employeesOnBehalf as $employeeOnBehalf)
										<option value="{{ $employeeOnBehalf->id }}">{{ $employeeOnBehalf->first_name . ' ' .  $employeeOnBehalf->surname}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="special_instructions" class="col-sm-2 control-label">Special Instructions</label>
							<div class="col-sm-10">
								<div class="input-group">
									<textarea class="form-control" rows="3" cols="70" id="special_instructions" name="special_instructions"
											  placeholder="Enter Special Instructions"></textarea>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="justification_of_expenditure" class="col-sm-2 control-label">Justification Of Expenditure</label>
							<div class="col-sm-10">
								<div class="input-group">
									<textarea class="form-control" rows="3" cols="70" id="justification_of_expenditure" name="justification_of_expenditure"
											  placeholder="Enter Special Instructions"></textarea>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="detail_of_expenditure" class="col-sm-2 control-label">Detail Of Expenditure</label>
							<div class="col-sm-10">
								<div class="input-group">
									<textarea class="form-control" rows="3" cols="70" id="detail_of_expenditure" name="detail_of_expenditure"
											  placeholder="Enter Special Instructions"></textarea>
								</div>
							</div>
						</div>
						<div class="form-group{{ $errors->has('delivery_type') ? ' has-error' : '' }}">
                            <label for="delivery_type" class="col-sm-2 control-label"> Delivery / Collection</label>
                            <div class="col-sm-9">
                                <label class="radio-inline rdo-iCheck" style="padding-left: 0px;"><input type="radio" id="rdo_delivery" name="delivery_type" value="1" checked> Delivery</label>
                                <label class="radio-inline rdo-iCheck"><input type="radio" id="rdo_collection" name="delivery_type" value="2">  Collection</label>
                            </div>
                        </div>
                        <hr class="hr-text products-field" data-content="SELECT PRODUCTS">
						<div class="form-group products-field {{ $errors->has('product_id') ? ' has-error' : '' }}">
                            <label for="product_id" class="col-sm-2 control-label">Products</label>
                            <div class="col-sm-10">
                                <select id="product_id" name="product_id[]" class="form-control select2" style="width: 100%;" multiple>
                                    <option value="0">*** Please Select Products ***</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ ($product->id == old('product_id[]')) ? 'selected' : '' }}>{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right">Next <i class="fa fa-arrow-right"></i></button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
        </div>
        <!-- Include modal -->
        @if(Session('changes_saved'))
            @include('contacts.partials.success_action', ['modal_title' => "Users Access Updated!", 'modal_content' => session('changes_saved')])
        @endif
    </div>
@endsection
@section('page_script')
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- DataTables -->
    <script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>

    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
	<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
	<script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
            //Tooltip
            $('[data-toggle="tooltip"]').tooltip();
            //Initialize iCheck/iRadio Elements
            $('.rdo-iCheck').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
			$('input').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green',
				increaseArea: '20%' // optional
			});
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
			$('.on_behalf_field').hide();
			$('#on_behalf').on('ifChecked', function(event){
			$('.on_behalf_field').show();
			});
			$('#on_behalf').on('ifUnchecked', function(event){
				$('.on_behalf_field').hide();
				$('#on_behalf_employee_id').val('');
			});
            //show / hide fields
            hideFields();

            $('#rdo_stock, #rdo_nonstock').on('ifChecked', function(){
                var allType = hideFields();
            });

            //Show success action modal
            @if(Session('changes_saved'))
                $('#success-action-modal').modal('show');
            @endif
			$(document).ready(function () {

                    $('#date_created').datepicker({
                        format: 'dd/mm/yyyy',
						endDate: '-0d',
                        autoclose: true,
                        todayHighlight: true
                });
            });
        });
        //function to hide/show fields depending on the quote  type
        function hideFields() {
            var itemType = $("input[name='item_type']:checked").val();
            if (itemType == 1) { //products and packages
                $('.products-field').show();
                $('.services-field').hide();
            }
            else if (itemType == 2) { //services
                $('.products-field').hide();
                $('.services-field').show();
            }
            return itemType;
        }
    </script>
@endsection