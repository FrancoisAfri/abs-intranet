@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- Include Date Range Picker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
    <!--Time Charger-->
    <!--  -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css"
          rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                </div>
                <form class="form-horizontal" method="POST" action="/procurement/search_results">
                    {{ csrf_field() }}

                    <div class="box-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
                                </button>
                                <h4><i class="icon fa fa-ban"></i> Invalid Input Data!</h4>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="col-md-8 col-md-offset-2">
                            <div>
                                <div class="box-header with-border" align="center">
                                    <h3 class="box-title">Search Criteria</h3>
                                </div>
                                    <div class="form-group {{ $errors->has('po_order') ? ' has-error' : '' }}">
                                        <label for="po_order" class="col-sm-2 control-label">Po Nunber</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="po_order" name="po_order"
													value="" placeholder="Enter Purchase Order Number">
                                            </div>
                                        </div>
                                    </div>
									<div class="form-group {{ $errors->has('title_name') ? ' has-error' : '' }}">
                                        <label for="title_name" class="col-sm-2 control-label">Title</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="title_name" name="title_name"
															value="" placeholder="Enter Title">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('employee_id') ? ' has-error' : '' }}">
                                        <label for="path" class="col-sm-2 control-label">Created By</label>
                                        <div class="col-sm-8">
                                           <select id="employee_id" name="employee_id" style="width: 100%;" class="form-control">
												<option value="0">*** Select an Employee ***</option>
												@foreach($employees as $employee)
													<option value="{{ $employee->id }}">{{$employee->first_name . ' ' .  $employee->surname }}</option>
												@endforeach
											</select>
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('on_behalf_employee_id') ? ' has-error' : '' }}">
                                        <label for="path" class="col-sm-2 control-label">On Behalf Of</label>
                                        <div class="col-sm-8">
                                            <select id="on_behalf_employee_id" name="on_behalf_employee_id"
												style="width: 100%;" class="form-control select2">
													<option value="0">*** Select an On Behalf Of ***</option>
													@foreach($employeesOnBehalf as $employeeOnBehalf)
														<option value="{{ $employeeOnBehalf->id }}">{{ $employeeOnBehalf->first_name . ' ' .  $employeeOnBehalf->surname}}</option>
													@endforeach
											</select>
                                        </div>
                                    </div>
									<div class="form-group{{ $errors->has('requested_date') ? ' has-error' : '' }}">
                                        <label for="requested_date" class="col-sm-2 control-label">Date Created</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control daterangepicker" id="requested_date"
                                                       name="requested_date" value="" placeholder="Select Date Created...">
                                            </div>
                                        </div>
                                    </div>
									<div class="form-group{{ $errors->has('approved_date') ? ' has-error' : '' }}">
                                        <label for="approved_date" class="col-sm-2 control-label">Date Approved</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control daterangepicker" id="approved_date"
                                                       name="approved_date" value="" placeholder="Select Approved Date...">
                                            </div>
                                        </div>
                                    </div>
									<div class="form-group {{ $errors->has('on_behalf_employee_id') ? ' has-error' : '' }}">
                                        <label for="status" class="col-sm-2 control-label">Status</label>
                                        <div class="col-sm-8">
                                            <select id="status" name="status"
												style="width: 100%;" class="form-control select2">
													<option value="0">*** Select a Status ***</option>
													@foreach($approvals as $approval)
														<option value="{{ $approval->id }}">{{ $approval->step_name}}</option>
													@endforeach
											</select>
                                        </div>
                                    </div>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary pull-right"><i
                                                class="fa fa-search"></i> Search
                                    </button>
                                </div>
							</div>
						</div>
					</div>
				</form>
            <!-- /.box -->
			</div>
		</div>
    </div>
@endsection
@section('page_script')
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- bootstrap datepicker -->
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- Bootstrap date picker -->
    <script src="/bower_components/AdminLTE/plugins/daterangepicker/moment.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js"
            type="text/javascript"></script>
    <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script type="text/javascript">
        $(function () {
            $(".select2").select2();
            $('.hours-field').hide();
            $('.comp-field').hide();
            var moduleId;
            //Tooltip
            $('[data-toggle="tooltip"]').tooltip();

            //Vertically center modals on page

            //Phone mask
            $("[data-mask]").inputmask();

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
            $(window).on('resize', function () {
                $('.modal:visible').each(reposition);
            });

            //Show success action modal
            $('#success-action-modal').modal('show');
        });
        //Date Range picker
        $('.daterangepicker').daterangepicker({
            format: 'DD/MM/YYYY',
            endDate: '-1d',
            autoclose: true
        });
    </script>
@endsection
