@extends('layouts.main_layout')
@section('page_dependencies')
     <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
    <!-- DataTables -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/yellow.css">
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-files-o pull-right"></i>
                    <h3 class="box-title">Policy</h3>
                    <p>Informations:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="">
                    {{ csrf_field() }}

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
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ !empty($policy->name) ? $policy->name : '' }}" placeholder="" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="description" class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="description" name="description" value="{{ !empty($policy->description) ? $policy->description : '' }}" readonly>
                                </div>
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('document') ? ' has-error' : '' }}">
							<label for="document" class="col-sm-2 control-label">Document</label>
							<div class="col-sm-10">
								<div class="input-group">
									@if(!empty($policy->document))
                                        <a class="btn btn-default btn-flat btn-block pull-right btn-xs"
										   href="{{ Storage::disk('local')->url("Policies/policy/$policy->document") }}"
										   target="_blank"><i class="fa fa-file-pdf-o"></i> Document</a>
                                    @else
                                        <a class="btn btn-default pull-centre btn-xs"><i
											class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
                                    @endif
								</div>
							</div>
						</div>
                        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                            <label for="status" class="col-sm-2 control-label">status</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="status" class="form-control" id="status" name="status" value="{{ !empty($company->status) && ($company->status == 1) ? 'Active' : 'Inactive' }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('date') ? ' has-error' : '' }}">
                            <label for="date" class="col-sm-2 control-label">Date Added</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="date" name="date" value="{{ (!empty( $policy->date)) ?  date(' d M Y', $policy->date) : ''}}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('category') ? ' has-error' : '' }}">
                            <label for="category" class="col-sm-2 control-label">Category</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="category" name="category" value="{{ !empty($policy->policyCategory->name) ? $policy->policyCategory->name : '' }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-footer -->
                </form>
				<div class="box-footer">
                        <button type="button" id="cat_module" class="btn btn-warning pull-right" data-toggle="modal"
                                data-target="#add-user-modal">Add More Employees
                        </button>
                    </div>
            </div>
            <!-- /.box -->
            <!-- Company's contacts box -->
            <div class="box box-default collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-users"></i> Employees</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding no-margin">
                    <div id="company-contacts" style="margin-right: 10px; max-height: 250px;">
                        <table class="table table-striped" >
							<tr>
								<th>#</th>
								<th>Employee</th>
							</tr>
							@foreach($policy->policyUsers as $users)
								<tr>
									<td>{{ $loop->iteration }}</td>
									<td>{{ !empty($users->employees->first_name) && !empty($users->employees->surname) ? $users->employees->first_name." ".$users->employees->surname : '' }}</td>
								</tr>
							@endforeach
						</table>
					</div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
		     <!-- Include add new prime rate modal -->
        @include('policy.partials.add_users_modal')
        <!-- End Column -->
    </div>
@endsection

@section('page_script')
     <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <!-- DataTables -->
    <script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>

    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js"
            type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
    <!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
    <script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>

    <script type="text/javascript">
        //Cancel button click event
        /*document.getElementById("cancel").onclick = function () {
            location.href = "/contacts";
        };*/

        $(function () {
            //Phone mask
            $("[data-mask]").inputmask();
			 $(".select2").select2();
            //slimScroll
            $('#company-contacts').slimScroll({
                height: '',
                railVisible: true,
                alwaysVisible: true
            });
			
			 $('#add-user').on('click', function () {
                var strUrl = '/System/policy/add_policyUsers';
                var formName = 'add-user-form';
                var modalID = 'add-user-modal';
                var submitBtnID = 'add-user';
                var redirectUrl = '/System/policy/view/{{ $policy->id }}';
                var successMsgTitle = 'New Users  Added!';
                var successMsg = 'The policy Users has been updated successfully.';
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
        });
    </script>
@endsection