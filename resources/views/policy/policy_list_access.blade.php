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
            <form class="form-horizontal" method="POST" action="/System/policy/update_status">
                {{ csrf_field() }}

                
                <div class="box-header with-border">
                    <h3 class="box-title"> My Policies </h3>
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
                            <th style="vertical-align: middle; text-align: center;"></th>
                            <th>Policy Name</th>
                            <th>Policy Description</th>
                            <th>Expiry Date </th>
                            <th style="vertical-align: middle; text-align: center;">Read and understood</th>
                            <th style="vertical-align: middle; text-align: center;">Read but not understood</th>
                            <th style="vertical-align: middle; text-align: center;">Read but not sure</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($policyUsers as $policy)
                            <tr>
                                <td style="vertical-align: middle;" nowrap>
                                    <div class="form-group{{ $errors->has('document') ? ' has-error' : '' }}">
                                        <label for="document" class="control-label"></label>
                                        @if(!empty($policy->policyDoc))
                                            <a class="btn btn-default btn-flat btn-block pull-right btn-xs"
                                               href="{{ Storage::disk('local')->url("Policies/policy/$policy->policyDoc") }}"
                                               target="_blank"><i class="fa fa-file-pdf-o"></i> View Document</a>
                                        @else
                                            <a class="btn btn-default pull-centre btn-xs"><i
                                                        class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
                                        @endif
                                    </div>
                                </td>
                                <td style="vertical-align: middle;" nowrap>{{ (!empty( $policy->policyName)) ?  $policy->policyName : ''}}</td>
                                <td style="vertical-align: middle;" nowrap>{{ (!empty( $policy->policyDescription)) ?  $policy->policyDescription : ''}}</td>
                                <td style="vertical-align: middle;" nowrap>{{ (!empty( $policy->Expiry)) ?  date(' d M Y', $policy->Expiry) : ''}}</td>

                                <td style="vertical-align: middle; text-align: center;">
                                    <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="{{ $policy->id . '_readunderstood' }}" name="{{ "docread[" . $policy->policy_id . "]" }}" value="{{ "1-$policy->user_id" }}" {{ $policy->read_understood == 1 ? ' checked' : '' }}></label>
                                </td>
                                <td style="vertical-align: middle; text-align: center;">
                                    <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="{{ $policy->id . '_readnotunderstood' }}" name="{{ "docread[" . $policy->policy_id . "]" }}" value="{{ "2-$policy->user_id" }}"  {{ $policy->read_not_understood == 1 ? ' checked' : '' }}></label>
                                </td>
                                <td style="vertical-align: middle; text-align: center;">
                                    <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="{{ $policy->id . '_readnotsure' }}" name="{{ "docread[" . $policy->policy_id . "]" }}" value="{{ "3-$policy->user_id" }}" {{ $policy->read_not_sure == 1 ? ' checked' : '' }}></label>
                                </td>
                                @endforeach
                            </tr>

                        </tbody>
                        <tfoot>
                        <tr>
                            <th style="vertical-align: middle; text-align: center;"></th>
                            <th>Policy Name</th>
                            <th>Policy Description</th>
                            <th>Expiry Date </th>
                            <th style="vertical-align: middle; text-align: center;">Read and understood</th>
                            <th style="vertical-align: middle; text-align: center;">Read but not understood</th>
                            <th style="vertical-align: middle; text-align: center;">Read but not sure</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
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
            "searching": true,
            "ordering": true,
            "info": false,
            "autoWidth": true
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