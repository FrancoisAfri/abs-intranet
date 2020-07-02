@extends('layouts.main_layout')

@section('page_dependencies')
<!-- bootstrap file input -->
<link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
<!-- DataTables -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
<!-- iCheck -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <form class="form-horizontal" method="POST" action="/users/update-users-access">
                {{ csrf_field() }}
                <input type="hidden" name="module_id" value="{{ $moduleID }}">
                <div class="box-header with-border">
                    <h3 class="box-title">Users Access (Module: {{ $moduleName }})</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
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
                    <table id="emp-list-table" class="table table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Employee</th>
                            <th style="vertical-align: middle; text-align: center;">None</th>
                            <th style="vertical-align: middle; text-align: center;">Read</th>
                            <th style="vertical-align: middle; text-align: center;">Write</th>
                            <th style="vertical-align: middle; text-align: center;">Modify</th>
                            <th style="vertical-align: middle; text-align: center;">Admin</th>
                            <th style="vertical-align: middle; text-align: center;">Superuser</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($employees as $employee)
                            <tr>
                                <td style="vertical-align: middle;" nowrap>{{ $employee->full_name }}</td>
                                <td style="vertical-align: middle; text-align: center;">
                                    <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="{{ $employee->id . '_rdo_none' }}" name="{{ "access_level[" . $employee->uid . "]" }}" value="0" {{ $employee->access_level == 0 ? ' checked' : '' }}></label>
                                </td>
                                <td style="vertical-align: middle; text-align: center;">
                                    <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="{{ $employee->id . '_rdo_read' }}" name="{{ "access_level[" . $employee->uid . "]" }}" value="1" {{ $employee->access_level == 1 ? ' checked' : '' }}></label>
                                </td>
                                <td style="vertical-align: middle; text-align: center;">
                                    <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="{{ $employee->id . '_rdo_write' }}" name="{{ "access_level[" . $employee->uid . "]" }}" value="2" {{ $employee->access_level == 2 ? ' checked' : '' }}></label>
                                </td>
                                <td style="vertical-align: middle; text-align: center;">
                                    <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="{{ $employee->id . '_rdo_modify' }}" name="{{ "access_level[" . $employee->uid . "]" }}" value="3" {{ $employee->access_level == 3 ? ' checked' : '' }}></label>
                                </td>
                                <td style="vertical-align: middle; text-align: center;">
                                    <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="{{ $employee->id . '_rdo_admin' }}" name="{{ "access_level[" . $employee->uid . "]" }}" value="4" {{ $employee->access_level == 4 ? ' checked' : '' }}></label>
                                </td>
                                <td style="vertical-align: middle; text-align: center;">
                                    <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="{{ $employee->id . '_rdo_superuser' }}" name="{{ "access_level[" . $employee->uid . "]" }}" value="5" {{ $employee->access_level == 5 ? ' checked' : '' }}></label>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>Employee</th>
                            <th style="vertical-align: middle; text-align: center;">None</th>
                            <th style="vertical-align: middle; text-align: center;">Read</th>
                            <th style="vertical-align: middle; text-align: center;">Write</th>
                            <th style="vertical-align: middle; text-align: center;">Modify</th>
                            <th style="vertical-align: middle; text-align: center;">Admin</th>
                            <th style="vertical-align: middle; text-align: center;">Superuser</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="button" id="cancel" class="btn btn-default pull-left"><i class="fa fa-arrow-left"></i> Cancel</button>
                    <button type="submit" id="add-new-module" class="btn btn-primary pull-right"><i class="fa fa-floppy-o"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Include modal -->
    @if(Session('changes_saved'))
    @include('contacts.partials.success_action', ['modal_title' => "Company Identity Updated!", 'modal_content' => session('changes_saved')])
    @endif
</div>
@endsection

@section('page_script')
<!-- Start Bootstrap File input -->
<!-- canvas-to-blob.min.js is only needed if you wish to resize images before upload. This must be loaded before fileinput.min.js -->
<script src="/bower_components/bootstrap_fileinput/js/plugins/canvas-to-blob.min.js" type="text/javascript"></script>
<!-- the main fileinput plugin file -->
<!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
<script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
<!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
<script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
<!-- the main fileinput plugin file -->
<script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
<!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
<script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
<!-- optionally if you need translation for your language then include locale file as mentioned below
<script src="/bower_components/bootstrap_fileinput/js/locales/<lang>.js"></script>-->
<!-- End Bootstrap File input -->

<!-- Select2 -->
<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
<!-- iCheck -->
<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
<!-- DataTables -->
<script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- Ajax dropdown options load -->
<script src="/custom_components/js/load_dropdown_options.js"></script>

<script>
    $(function () {
        //Initialize Select2 Elements
        $(".select2").select2();

        //Initialize iCheck/iRadio Elements
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });

        //Tooltip
        $('[data-toggle="tooltip"]').tooltip();

        //Initialize the data table
        $('#emp-list-table').DataTable({
            "paging": true,
			"lengthChange": true,
			"lengthMenu": [ 50, 75, 100, 150, 200, 250 ],
			"pageLength": 50,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": true,
        });

        //Cancel button
        $('#cancel').click(function () {
            location.href = '/users/users-access';
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