@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title"> Create Policy</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                {{ csrf_field() }}
                <div class="box-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 10px; text-align: center;"></th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Completion date</th>
                            <th style="width: 5px; text-align: center;"></th>
                            <th style="width: 5px; text-align: center;"></th>
                            <th style="width: 5px; text-align: center;"></th>
                        </tr>
                        @if (count($Policy) > 0)
                            @foreach ($Policy as $policy)
                                <tr id="categories-list">
                                    <td nowrap>
                                        <button vehice="button" id="edit_compan" class="btn btn-warning  btn-xs"
                                                data-toggle="modal" data-target="#edit-policy-modal"
                                                data-id="{{ $policy->id }}" data-name="{{ $policy->name }}"
                                                data-description="{{$policy->description}}"><i
                                                    class="fa fa-pencil-square-o"></i> Edit
                                        </button>
                                    </td>
                                    <td>{{ (!empty( $policy->name)) ?  $policy->name : ''}} </td>
                                    <td>{{ (!empty( $policy->description)) ?  $policy->description : ''}} </td>
                                    <td>{{ (!empty( $policy->date)) ?  date(' d M Y', $policy->date) : '' }}</td>
                                    <td>
                                        <!--   leave here  -->
                                        <button vehice="button" id="view_ribbons"
                                                class="btn {{ (!empty($policy->status) && $policy->status == 1) ? " btn-danger " : "btn-success " }}
                                                        btn-xs" onclick="postData({{$policy->id}}, 'actdeac');"><i
                                                    class="fa {{ (!empty($policy->status) && $policy->status == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($policy->status) && $policy->status == 1) ? "De-Activate" : "Activate"}}
                                        </button>
                                    </td>
                                    <td>
                                        <div class="form-group{{ $errors->has('document') ? ' has-error' : '' }}">
                                            <label for="document" class="control-label"></label>
                                            @if(!empty($policy->document))
                                                <a class="btn btn-default btn-flat btn-block pull-right btn-xs"
                                                   href="{{ Storage::disk('local')->url("Policies/policy/$policy->document") }}"
                                                   target="_blank"><i class="fa fa-file-pdf-o"></i> View Document</a>
                                            @else
                                                <a class="btn btn-default pull-centre btn-xs"><i
                                                            class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
                                            @endif
                                        </div>
                                    </td>

                                    <td nowrap>
                                        <a href="{{ '/system/policy/viewUsers/' . $policy->id }}" id="edit_compan" class="btn btn-primary  btn-xs"><i class="fa fa-user"> </i> View Users</a></td>
                                    </td>

                                </tr>
                            @endforeach
                        @else
                            <tr id="categories-list">
                                <td colspan="5">
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        No Policies to display, please start by adding a new FPolicies ...
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </table>
                    <!--   </div> -->
                    <!-- /.box-body -->
                    <div class="box-footer">
                        {{--<button type="button" class="btn btn-default pull-left" id="back_button">Back</button>--}}
                        <button type="button" id="cat_module" class="btn btn-warning pull-right" data-toggle="modal"
                                data-target="#add-policy-modal">Create Policy
                        </button>
                    </div>
                </div>
            </div>
            <!-- Include add new prime rate modal -->
        @include('policy.partials.add_policy_modal')
        @include('policy.partials.edit_policy_modal')
        {{--@include('Vehicles.partials.edit_fleetcard_modal')--}}
        <!-- Include delete warning Modal form-->
            {{--@if (count($policyType) > 0)--}}
            {{--@include('Vehicles.warnings.fleetcard_warning_action', ['modal_title' => 'Delete Task', 'modal_content' => 'Are you sure you want to delete this Fleet Type? This action cannot be undone.'])--}}
            {{--@endif--}}
        </div>


        @endsection

        @section('page_script')
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
                    if (data == 'actdeac') location.href = "/System/policy_act/" + id;

                }

                $('#back_button').click(function () {
                    location.href = '/vehicle_management/setup';
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
                    $('input').iCheck({
                        checkboxClass: 'icheckbox_square-blue',
                        radioClass: 'iradio_square-blue',
                        increaseArea: '10%' // optional
                    });


                    $(document).ready(function () {

                        $('#date').datepicker({
                            format: 'dd/mm/yyyy',
                            autoclose: true,
                            todayHighlight: true
                        });
                    });

                    //save Fleet

                    $('#add-policy').on('click', function () {
                        var strUrl = '/System/policy/add_policy';
                        var formName = 'add-policy-form';
                        var modalID = 'add-policy-modal';
                        var submitBtnID = 'add-policy';
                        var redirectUrl = '/System/policy/create';
                        var successMsgTitle = 'New policy  Added!';
                        var successMsg = 'The policy  has been updated successfully.';
                        modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                    });

                    //Post module form to server using ajax (ADD)
//                    $('#add-fleet-card').on('click', function () {
//                        //console.log('strUrl');
//                        var strUrl = '/vehice/add_fleetcard';
//                        var modalID = 'add-fleet-modal';
//                        var objData = {
//                            name: $('#' + modalID).find('#name').val(),
//                            description: $('#' + modalID).find('#description').val(),
//                            _token: $('#' + modalID).find('input[name=_token]').val()
//                        };
//                        var submitBtnID = 'add-fleet-card';
//                        var redirectUrl = '/vehicle_management/fleet_card';
//                        var successMsgTitle = 'Fleet Type Added!';
//                        var successMsg = 'The Fleet Type has been updated successfully.';
//                        //var formMethod = 'PATCH';
//                        modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
//                    });

                    var fleetID;
                    $('#edit-package-modal').on('show.bs.modal', function (e) {
                        //console.log('kjhsjs');
                        var btnEdit = $(e.relatedTarget);
                        fleetID = btnEdit.data('id');
                        var name = btnEdit.data('name');
                        var description = btnEdit.data('description');
                        var modal = $(this);
                        modal.find('#name').val(name);
                        modal.find('#description').val(description);
                    });
                    $('#edit_fleetcard').on('click', function () {
                        var strUrl = '/vehice/edit_fleetcard/' + fleetID;
                        var modalID = 'edit-package-modal';
                        var objData = {
                            name: $('#' + modalID).find('#name').val(),
                            description: $('#' + modalID).find('#description').val(),
                            _token: $('#' + modalID).find('input[name=_token]').val()
                        };
                        var submitBtnID = 'edit_fleetcard';
                        var redirectUrl = '/vehicle_management/Manage_fleet_types';
                        var successMsgTitle = 'Changes Saved!';
                        var successMsg = 'The Fleet Type has been updated successfully.';
                        var Method = 'PATCH';
                        modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
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
