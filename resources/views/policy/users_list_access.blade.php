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
        <div class="col-md-12">
            <div class="box box-primary">
                {{--<form class="form-horizontal" method="POST" action="/users/update-users-access">--}}
                {{ csrf_field() }}
                {{--<input type="hidden" name="module_id" value="{{ $moduleID }}">--}}
                <div class="box-header with-border">
                    <h3 class="box-title">Policy Users for  - {{ $policyname }}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
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
                            <th>Users Name</th>
                            <th>Users Surname</th>
                            <th>Expiry Date</th>
                            <th style="vertical-align: middle; text-align: center;">Read and understood</th>
                            <th style="vertical-align: middle; text-align: center;">Read but not understood</th>
                            <th style="vertical-align: middle; text-align: center;">Read but not sure</th>
                            <th style="vertical-align: middle; text-align: center;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($policyUsers as $policyUser)
                            <tr>

                                <td style="vertical-align: middle;"
                                    nowrap>{{ (!empty( $policyUser->firstname)) ?  $policyUser->firstname : ''}}</td>
                                <td style="vertical-align: middle;"
                                    nowrap>{{ (!empty( $policyUser->surname)) ?  $policyUser->surname : ''}}</td>
                                <td style="vertical-align: middle;"
                                    nowrap>{{ (!empty( $policyUser->Expiry)) ?  date(' d M Y', $policyUser->Expiry) : ''}}</td>

                                <td style="vertical-align: middle; text-align: center;">
                                    <label class="radio-inline" style="padding-left: 0px;"><input type="radio"
                                                                                                  id="{{ $policyUser->id . '_rdo_none' }}"
                                                                                                  name=""
                                                                                                  value="0" {{ $policyUser->read_understood == 1 ? ' checked' : '' }} disabled='disabled' ></label>
                                </td>
                                <td style="vertical-align: middle; text-align: center;">
                                    <label class="radio-inline" style="padding-left: 0px;"><input type="radio"
                                                                                                  id="{{ $policyUser->id . '_rdo_read' }}"
                                                                                                  name=""
                                                                                                  value="1" {{ $policyUser->read_not_understood == 1 ? ' checked' : '' }} disabled='disabled' ></label>
                                </td>
                                <td style="vertical-align: middle; text-align: center;">
                                    <label class="radio-inline" style="padding-left: 0px;"><input type="radio"
                                                                                                  id="{{ $policyUser->id . '_rdo_write' }}"
                                                                                                  name=""
                                                                                                  value="2" {{ $policyUser->read_not_sure == 1 ? ' checked' : '' }} disabled='disabled' ></label>
                                </td>
                                <td>
                                    <!--   leave here  -->
                                    <button vehice="button" id="view_ribbons"
                                            class="btn {{ (!empty($policyUser->status) && $policyUser->status == 1) ? " btn-danger " : "btn-success " }}
                                                    btn-xs" onclick="postData({{$policyUser->id}}, 'actdeac');"><i
                                                class="fa {{ (!empty($policyUser->status) && $policyUser->status == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($policyUser->status) && $policyUser->status == 1) ? "De-Activate" : "Activate"}}
                                    </button>
                                </td>
                                @endforeach
                            </tr>

                        </tbody>
                        <tfoot>
                        <tr>
                            <th>Users Name</th>
                            <th>Users Surname</th>
                            <th>Expiry Date</th>
                            <th style="vertical-align: middle; text-align: center;">Read and understood</th>
                            <th style="vertical-align: middle; text-align: center;">Read but not understood</th>
                            <th style="vertical-align: middle; text-align: center;">Read but not sure</th>
                            <th style="vertical-align: middle; text-align: center;"></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="button" id="cancel" class="btn btn-default pull-left"><i class="fa fa-arrow-left"></i>
                        Back
                    </button>
                    <button type="button" id="cat_module" class="btn btn-primary pull-right" data-toggle="modal"
                            data-target="#add-user-modal">Add More Employees
                    </button>
                </div>
                </form>
            </div>
        </div>
    @include('policy.partials.add_users_modal')

    <!-- Include modal -->
        @if(Session('changes_saved'))
            @include('contacts.partials.success_action', ['modal_title' => "Company Identity Updated!", 'modal_content' => session('changes_saved')])
        @endif
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
    <script>
        function postData(id, data) {
            if (data == 'actdeac') location.href = "/System/add_user_act/" + id;
        }

        $('#cancel').click(function () {
            location.href = '/System/policy/create';
        });

        $(function () {
            var moduleId;
            //Initialize Select2 Elements
            $(".select2").select2();
            $('.zip-field').hide();
            //Tooltip

            //Phone mask
            $("[data-mask]").inputmask();

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
            $(window).on('resize', function () {
                $('.modal:visible').each(reposition);
            });

            //Show success action modal
            $('#success-action-modal').modal('show');
            //
            $(".js-example-basic-multiple").select2();

            //Initialize iCheck/iRadio Elements
//            $('input').iCheck({
//                checkboxClass: 'icheckbox_square-yellow',
//                radioClass: 'iradio_square-blue',
//                increaseArea: '10%' // optional
//            });

            $('#add-user').on('click', function () {
                var strUrl = '/System/policy/add_policyUsers';
                var formName = 'add-user-form';
                var modalID = 'add-user-modal';
                var submitBtnID = 'add-user';
                var redirectUrl = '/system/policy/viewUsers/{{ $policyID }}';
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
