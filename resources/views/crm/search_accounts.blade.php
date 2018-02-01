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
                <form class="form-horizontal" method="POST" action="/crm/accounts/search">
                    {{ csrf_field() }}
                    <div class="box-header with-border">
                        <h3 class="box-title">Search Accounts</h3>
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

                        <hr class="hr-text" data-content="CLIENT DETAILS">

                        <div class="form-group{{ $errors->has('company_id') ? ' has-error' : '' }}">
                            <label for="{{ 'company_id' }}" class="col-sm-2 control-label">Client Company</label>

                            <div class="col-sm-10">
                                <select id="company_id" name="company_id" class="form-control select2" style="width: 100%;" onchange="contactCompanyDDOnChange(this)">
                                    <option value="">*** Please Select a Company ***</option>
                                    <option value="0">[Individual Clients]</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ ($company->id == old('company_id')) ? 'selected' : '' }}>{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('contact_person_id') ? ' has-error' : '' }}">
                            <label for="{{ 'contact_person_id' }}" class="col-sm-2 control-label">Contact Person</label>

                            <div class="col-sm-10">
                                <select id="contact_person_id" name="contact_person_id" class="form-control select2" style="width: 100%;">
                                    <option value="">*** Please Select a Company First ***</option>
                                </select>
                            </div>
						</div>
						<div class="form-group{{ $errors->has('account_number') ? ' has-error' : '' }}">
                            <label for="name" class="col-sm-2 control-label">Account Number</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="account_number" name="account_number" value="{{ old('account_number') }}" placeholder="Account Number">
                                </div>
                            </div>
                        </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right">Search</button>
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
            //Initialize Select2 Elements
            $(".select2").select2();

            //Tooltip
            $('[data-toggle="tooltip"]').tooltip();

            //Initialize the data table
            $('#terms-conditions-table').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": false,
                "autoWidth": true
            });

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

            //Show success action modal
            @if(Session('changes_saved'))
                $('#success-action-modal').modal('show');
            @endif
        });
    </script>
@endsection