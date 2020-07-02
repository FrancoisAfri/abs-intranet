@extends('layouts.main_layout')
@section('page_dependencies')

    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Fuel tank(s)</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
            {{ csrf_field() }}
            <!-- /.box-header -->
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
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 10px; text-align: center;"></th>
                            <th style="width: 10px; text-align: center;"></th>
                            <th>Tank Name</th>
                            <th>Description</th>
                            <th>Capacity</th>
                            <th>Current Amount</th>
                            <th>Division</th>
                            <th>Department</th>
                            <th>Tank Location</th>
                            <th>Tank Manager</th>
                            <th style="width: 5px; text-align: center;">Status</th>
                        </tr>
                        @if (count($Fueltanks) > 0)
                            @foreach ($Fueltanks as $tanks)
                                <tr id="categories-list">
                                    <td nowrap>
                                        <button tanks="button" id="edit_compan" class="btn btn-warning  btn-xs"
                                                data-toggle="modal"
                                                data-target="#edit-tank-modal" data-id="{{ $tanks->id }}"
                                                data-tank_name="{{ $tanks->tank_name }}"
                                                data-division_level_1="{{ $tanks->division_level_1 }}"
                                                data-division_level_2="{{ $tanks->division_level_2 }}"
                                                data-division_level_3="{{ $tanks->division_level_3 }}"
                                                data-division_level_4="{{ $tanks->division_level_4 }}"
                                                data-division_level_5="{{ $tanks->division_level_5 }}"
                                                data-tank_location="{{ $tanks->tank_location }}"
                                                data-tank_description="{{ $tanks->tank_description }}"
                                                data-tank_capacity="{{number_format($tanks->tank_capacity, 2) }}"
                                                
                                                data-tank_manager="{{$tanks->tank_manager}}"
                                        ><i class="fa fa-pencil-square-o"></i> Edit
                                        </button>
                                    </td>
                                    @if (isset($tanks) && $tanks->status === 1)
                                        <td>
                                            <a href="{{ '/vehicle_management/vehice_tank/' . $tanks->id }}"
                                               id="edit_compan"
                                               class="btn btn-primary  btn-xs" data-id="{{ $tanks->id }}"
                                            ><i class="fa fa-bullseye"></i> View Tank</a>
                                        </td> @else
                                        <td></td>
                                        @endif
                                        </td></td>
                                        <td>{{ (!empty( $tanks->tank_name)) ?  $tanks->tank_name : ''}} </td>
                                        <td>{{ (!empty( $tanks->tank_description)) ?  $tanks->tank_description : ''}} </td>
                                        <td>{{ (!empty( $tanks->tank_capacity)) ? number_format($tanks->tank_capacity, 2) : ''}} </td>
                                        <td>{{ !empty($tanks->current_fuel_litres) ?  number_format($tanks->current_fuel_litres, 2) : 0 }}</td>
                                        <td>{{ (!empty( $tanks->Department)) ?  $tanks->Department : ''}} </td>
                                        <td>{{ (!empty( $tanks->company)) ?  $tanks->company : ''}} </td>
                                        <td>{{ (!empty( $tanks->tank_location)) ?  $tanks->tank_location : ''}} </td>
                                        <td>{{ (!empty( $tanks->first_name . ' ' . $tanks->surname)) ?  $tanks->first_name . ' ' . $tanks->surname : ''}} </td>
                                        <td>
                                            <button tanks="button" id="view_ribbons"
                                                    class="btn {{ (!empty($tanks->status) && $tanks->status == 1) ? " btn-danger " : "btn-success " }}
                                                            btn-xs" onclick="postData({{$tanks->id}}, 'actdeac');"><i
                                                        class="fa {{ (!empty($tanks->status) && $tanks->status == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($tanks->status) && $tanks->status == 1) ? "De-Activate" : "Activate"}}
                                            </button>
                                        </td>

                                </tr>
                            @endforeach
                        @else
                            <tr id="categories-list">
                                <td colspan="12">
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        No Fuel tanks to display, please start by adding a new Fuel Tank..
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </table>
                    <!--   </div> -->
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                        <button type="button" id="cat_module" class="btn btn-warning pull-right" data-toggle="modal"
                                data-target="#add-tank-modal">Add Fuel Tanks
                        </button>
                    </div>
                </div>
            </div>
            <!-- Include add new prime rate modal -->
            @include('Vehicles.FuelTanks.partials.add_tank_modal')
            @include('Vehicles.FuelTanks.partials.edit_tank_modal')
        </div>
    @endsection
    @section('page_script')
        <!-- Select2 -->
            <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>

            <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
            <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js"
                    type="text/javascript"></script>
            <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
            <!-- iCheck -->
            <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

            <!-- Ajax dropdown options load -->
            <script src="/custom_components/js/load_dropdown_options.js"></script>
            <!-- Ajax form submit -->
            <script src="/custom_components/js/modal_ajax_submit.js"></script>

            <script type="text/javascript">

                function postData(id, data) {
                    if (data == 'actdeac') location.href = "/vehicle_management/fueltank_act/" + id;
                }

                $(function () {
                    $(".select2").select2();
                    $('.hours-field').hide();
                    $('.comp-field').hide();
                    var moduleId;
                    //Tooltip
                    $('[data-toggle="tooltip"]').tooltip();


                    //Vertically center modals on page

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


                //Initialize iCheck/iRadio Elements
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '10%' // optional
                });

                function convertMoney(value, type) {
                    if (value.length > 1) {
                        var str = value.toString().split('.');
                        if (str[0].length >= 4) {
                            str[0] = str[0].replace(/(\d)(?=(\d{3})+$)/g, '$1,');
                        }
                        if (str[1] && str[1].length >= 5) {
                            str[1] = str[1].replace(/(\d{3})/g, '$1 ');
                        }
                        value = str + '. 00';
                    }
                    else value = value + '. 00';
                    if (type == 1) $('#tank_capacity').val(value);
                    else if (type == 2) $('#current_fuel_litres').val(value);

                    //console.log(value);
                }


                //
                //save Fuel Tank
                //Post module form to server using ajax (ADD)
                $('#add-fueltank').on('click', function () {
                    var strUrl = '/vehicle_management/addfueltank';
                    var modalID = 'add-tank-modal';
                    var objData = {
                        division_level_1: $('#' + modalID).find('#division_level_1').val(),
                        division_level_2: $('#' + modalID).find('#division_level_2').val(),
                        division_level_3: $('#' + modalID).find('#division_level_3').val(),
                        division_level_4: $('#' + modalID).find('#division_level_4').val(),
                        division_level_5: $('#' + modalID).find('#division_level_5').val(),
                        tank_name: $('#' + modalID).find('#tank_name').val(),
                        tank_location: $('#' + modalID).find('#tank_location').val(),
                        tank_description: $('#' + modalID).find('#tank_description').val(),
                        tank_capacity: $('#' + modalID).find('#tank_capacity').val(),
                        current_fuel_litres: $('#' + modalID).find('#current_fuel_litres').val(),
                        tank_manager: $('#' + modalID).find('#tank_manager').val(),
                        _token: $('#' + modalID).find('input[name=_token]').val()
                    };
                    var submitBtnID = 'add-fueltank';
                    var redirectUrl = '/vehicle_management/fuel_tank';
                    var successMsgTitle = 'Fuel Tank Added!';
                    var successMsg = 'The Fuel Tank has been added successfully.';
                    modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                });

                var tankID;
                $('#edit-tank-modal').on('show.bs.modal', function (e) {
                    var btnEdit = $(e.relatedTarget);
                    if (parseInt(btnEdit.data('id')) > 0) {
                        tankID = btnEdit.data('id');
                    }
                    var division_level_5 = btnEdit.data('division_level_5');
                    var division_level_4 = btnEdit.data('division_level_4');
                    var division_level_3 = btnEdit.data('division_level_3');
                    var division_level_2 = btnEdit.data('division_level_2');
                    var division_level_1 = btnEdit.data('division_level_1');
                    var tank_name = btnEdit.data('tank_name');
                    var tank_location = btnEdit.data('tank_location');
                    var tank_description = btnEdit.data('tank_description');
                    var tank_capacity = btnEdit.data('tank_capacity');
                    var current_fuel_litres = btnEdit.data('current_fuel_litres');
                    var tank_manager = btnEdit.data('tank_manager');
                    var modal = $(this);
                    modal.find('#division_level_5').val(division_level_5);
                    modal.find('#division_level_4').val(division_level_4);
                    modal.find('#division_level_3').val(division_level_3);
                    modal.find('#division_level_2').val(division_level_2);
                    modal.find('#division_level_1').val(division_level_1);
                    modal.find('#tank_name').val(tank_name);
                    modal.find('#tank_location').val(tank_location);
                    modal.find('#tank_description').val(tank_description);
                    modal.find('#tank_capacity').val(tank_capacity);
                    modal.find('#current_fuel_litres').val(current_fuel_litres);
                    modal.find('#tank_manager').val(tank_manager);
                });


                //Post perk form to server using ajax (edit)
                $('#edit_tank').on('click', function () {
                    var strUrl = '/vehicle_management/edit_fueltank/' + tankID;
                    var formName = 'edit-tank-form';
                    var modalID = 'edit-tank-modal';
                    var submitBtnID = 'edit_tank';
                    var redirectUrl = '/vehicle_management/fuel_tank';
                    var successMsgTitle = 'Changes Saved!';
                    var successMsg = 'The  details have been updated successfully!';
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

            </script>
@endsection
