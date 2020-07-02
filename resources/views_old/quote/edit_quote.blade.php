@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
    <!-- DataTables -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <form class="form-horizontal" method="POST" action="/quote/adjust_modification/{{ $quote->id }}">
                    {{ csrf_field() }}
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit Quote</h3>
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
                        <div class="form-group{{ $errors->has('application_type') ? ' has-error' : '' }}">
                            <label for="Leave_type" class="col-sm-2 control-label"> Quote Type</label>

                            <div class="col-sm-9">
                                <label class="radio-inline rdo-iCheck" style="padding-left: 0px;"><input type="radio" id="rdo_products" name="quote_type" value="1" {{ ($quote->quote_type == 1) ? 'checked' : '' }}> Products/Packages</label>
                                <label class="radio-inline rdo-iCheck"><input type="radio" id="rdo_services" name="quote_type" value="2" {{ ($quote->quote_type == 2) ? 'checked' : '' }}>  Services</label>
                            </div>
                        </div>
						<div class="form-group">
                        <label for="quote_title" class="col-sm-2 control-label">Quote Title</label>
							<div class="col-sm-10">
								<div class="input-group">
									<input type="text" class="form-control" id="quote_title" name="quote_title" value="{{ !empty($quote->quote_title) ? $quote->quote_title : '' }}" placeholder="Enter Quote Title" required>
								</div>
							</div>
						</div>
						<div class="form-group existing_one">
							<label for="name" class="col-sm-2 control-label">Remark</label>
							<div class="col-sm-10">
								<div class="input-group">
									<textarea class="form-control" rows="3" cols="70" id="quote_remarks" name="quote_remarks"
											  placeholder="Enter Quote Remark">{{ !empty($quote->quote_remarks) ? $quote->quote_remarks : '' }}</textarea>
								</div>
							</div>
						</div>
                        <div class="form-group{{ $errors->has('division_id') ? ' has-error' : '' }}">
                            <label for="division_id" class="col-sm-2 control-label">{{ $highestLvl->name }}</label>
                            <div class="col-sm-10">
                                <select id="division_id" name="division_id" class="form-control select2" style="width: 100%;">
                                    <option value="">*** Please Select a {{ $highestLvl->name }} ***</option>
                                    @if($highestLvl->divisionLevelGroup)
                                        @foreach($highestLvl->divisionLevelGroup as $division)
                                            <option value="{{ $division->id }}"{{ ($division->id == $quote->division_id && $highestLvl->level == $quote->division_level) ? ' selected' : '' }}>{{ $division->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <hr class="hr-text" data-content="SELECT A CLIENT">
                        <div class="form-group{{ $errors->has('company_id') ? ' has-error' : '' }}">
                            <label for="company_id" class="col-sm-2 control-label">Client Company</label>
                            <div class="col-sm-10">
                                <select id="company_id" name="company_id" class="form-control select2" style="width: 100%;" onchange="contactCompanyDDOnChange(this, null, parseInt('{{ $quote->client_id }}'))">
                                    <option value="">*** Please Select a Company ***</option>
                                    <option value="0">[Individual Clients]</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ ($company->id == $quote->company_id) ? ' selected' : '' }}>{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('contact_person_id') ? ' has-error' : '' }}">
                            <label for="contact_person_id" class="col-sm-2 control-label">Contact Person</label>
                            <div class="col-sm-10">
                                <select id="contact_person_id" name="contact_person_id" class="form-control select2" style="width: 100%;">
                                    <option value="">*** Please Select a Company First ***</option>
                                </select>
                            </div>
                        </div>
                        <hr class="hr-text products-field" data-content="SELECT PRODUCTS">
                        <div class="form-group products-field{{ $errors->has('product_id') ? ' has-error' : '' }}">
                            <label for="product_id" class="col-sm-2 control-label">Products</label>
                            <div class="col-sm-10">
                                <select id="product_id" name="product_id[]" class="form-control select2" style="width: 100%;" multiple>
                                    <option value="">*** Please Select Some Products ***</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ ($quote->products && $quote->products->contains('id', $product->id)) ? ' selected' : '' }}>{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <hr class="hr-text packages-field" data-content="OR SELECT PACKAGES">
                        <div class="form-group packages-field{{ $errors->has('package_id') ? ' has-error' : '' }}">
                            <label for="package_id" class="col-sm-2 control-label">Package</label>
                            <div class="col-sm-10">
                                <select id="package_id" name="package_id[]" class="form-control select2" style="width: 100%;" multiple>
                                    <option value="">*** Please Select a Package ***</option>
                                    @foreach($packages as $package)
                                        <option value="{{ $package->id }}" {{ ($quote->packages && $quote->packages->contains('id', $package->id)) ? ' selected' : '' }}>{{ $package->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <hr class="hr-text" data-content="SELECT TERMS AND CONDITIONS">
						<div style="max-height: 550px; overflow-y: scroll;">
							<table class="table table-bordered table-striped table-hover">
								<thead>
									<tr>
										<th width="5px" class="col-xs-2"></th>
										<th class="col-xs-10">Terms And Conditions</th>
									</tr>
								</thead>
								<tbody>
									@foreach($termsAndConditions as $condition)
										@if($loop->first || (isset($prevCategory) && $prevCategory != $condition->category_id))
											<?php $prevCategory = 0; ?>
											<tr>
												<th class="success" colspan="4" style="text-align: center;">
													<i>{{$condition->cat_name}}</i>
												</th>
											</tr>
										@endif
										<tr>
											<td class="col-xs-2">
												<label class="radio-inline pull-right no-padding" style="padding-left: 0px;">
													<span hidden>
														{{ ($quote->termsAndConditions && $quote->termsAndConditions->contains('id', $condition->term_id)) ? '1' : '2' }}
													</span>
													<input class="rdo-iCheck" type="checkbox" id="" name="tc_id[]" value="{{ $condition->term_id }}" {{ ($quote->termsAndConditions && $quote->termsAndConditions->contains('id', $condition->term_id)) ? ' checked' : '' }}>
												</label>
											</td>
											<td class="col-xs-10">{!! $condition->term_name !!}</td>
										</tr>
										<?php $prevCategory = $condition->category_id; ?>
									@endforeach
								</tbody>
								<tfoot>
									<tr>
										<th></th>
										<th>Terms And Conditions</th>
									</tr>
								</tfoot>
							</table>
						</div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
					<button type="button" class="btn btn-default pull-left" id="back_button"><i class="fa fa-arrow-left"></i> Back</button>
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
    <script>
        $(function () {
			//Cancel button click event
            $('#back_button').click(function () {
                location.href = '/quote/view/{{$quote->id}}/01';
            });	
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

            //select the contact person
            $('#company_id').trigger('change');

            //show / hide fields
            hideFields();

            $('#rdo_products, #rdo_services').on('ifChecked', function(){
                var allType = hideFields();
            });

            //Show success action modal
            @if(Session('changes_saved'))
                $('#success-action-modal').modal('show');
            @endif
        });

        //function to hide/show fields depending on the quote  type
        function hideFields() {
            var quoteType = $("input[name='quote_type']:checked").val();
            if (quoteType == 1) { //products and packages
                $('.products-field').show();
                $('.packages-field').show();
                $('.services-field').hide();
            }
            else if (quoteType == 2) { //services
                $('.products-field').hide();
                $('.packages-field').hide();
                $('.services-field').show();
            }
            return quoteType;
        }
    </script>
@endsection