@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <!-- Include Date Range Picker -->
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
                    <h3 class="box-title"> Vehicle General costs </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
           <!--  {{ csrf_field() }}
            {{ method_field('PATCH') }} -->
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                <strong class="lead">Vehicle Details</strong><br>
                                @if(!empty($vehiclemaker))
                                    | &nbsp; &nbsp; <strong>Vehicle Make:</strong> <em>{{ $vehiclemaker->name }}</em> &nbsp;
                                    &nbsp;
                                @endif
                                @if(!empty($vehiclemodeler))
                                    -| &nbsp; &nbsp; <strong>Vehicle Model:</strong> <em>{{ $vehiclemodeler->name }}</em>
                                    &nbsp; &nbsp;
                                @endif
                                @if(!empty($vehicleTypes))
                                    -| &nbsp; &nbsp; <strong>Vehicle Type:</strong> <em>{{ $vehicleTypes->name }}</em> &nbsp;
                                    &nbsp;
                                @endif
                                @if(!empty($maintenance->vehicle_registration))
                                    -| &nbsp; &nbsp; <strong>Vehicle Registration:</strong>
                                    <em>{{ $maintenance->vehicle_registration }}</em> &nbsp; &nbsp;
                                @endif
                                @if(!empty($maintenance->year))
                                    -| &nbsp; &nbsp; <strong>Year:</strong> <em>{{ $maintenance->year }}</em> &nbsp;
                                    &nbsp;
                                @endif
                                @if(!empty($maintenance->vehicle_color))
                                    -| &nbsp; &nbsp; <strong>Vehicle Color:</strong>
                                    <em>{{ $maintenance->vehicle_color }}</em> &nbsp; &nbsp; -|
                                @endif
                            </p>
                        </div>
                    </div>
                    <div align="center">
                        <!--  -->
                        <a href="{{ '/vehicle_management/viewdetails/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-bars"></i> General Details
                        </a>
                        <a href="{{ '/vehicle_management/bookin_log/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-book"></i> Booking Log
                        </a>

                        <a href="{{ '/vehicle_management/fuel_log/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-tint"></i> Fuel Log
                        </a>
                        {{--<a href="{{ '/vehicle_management/oil_log/' . $maintenance->id }}" class="btn btn-app">--}}
                            {{--<i class="fa fa-file-o"></i> Oil Log--}}
                        {{--</a>--}}
                        <a href="{{ '/vehicle_management/incidents/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-medkit"></i> Incidents
                        </a>
                        <a href="{{ '/vehicle_management/fines/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-list-alt"></i> Fines
                        </a>
                        <a href="{{ '/vehicle_management/service_details/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-area-chart"></i> Service Details
                        </a>
                        <a href="{{ '/vehicle_management/insurance/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-car"></i>Insurance
                        </a>
                        <a href="{{ '/vehicle_management/warranties/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-snowflake-o"></i>Warranties
                        </a>
                        <a href="{{ '/vehicle_management/general_cost/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-money"></i> General Cost
                        </a>
						<a href="{{ '/vehicle_management/fleet-communications/' . $maintenance->id }}"
                                   class="btn btn-app"><i class="fa fa-money"></i> Communications</a>
                        <!--  -->
                    </div>
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 10px; text-align: center;"></th>
                            <th>Date</th>
                            <th>Document Number</th>
                            <th>Supplier Name</th>
                            <th>Cost Type</th>
                            <th>Cost (R)</th>
                            <th>litre</th>
                            <th>Description</th>
                            <th>Person Responsible</th>
                            <th></th>
                            <th style="width: 5px; text-align: center;"></th>
                        </tr>
                        @if (count($generalcost) > 0)
                            @foreach ($generalcost as $reminder)
                                <tr id="categories-list">
                                    <td nowrap>
                                        <button reminder="button" id="edit_compan" class="btn btn-warning  btn-xs"
                                                data-toggle="modal" data-target="#edit-costs-modal"
                                                data-id="{{ $reminder->id }}"
                                                data-date="{{ date(' d M Y', $reminder->date) }}"
                                                data-document_number="{{ $reminder->document_number }}"
                                                data-supplier_name="{{ $reminder->supplier_name }}"
                                                data-cost_type="{{ $reminder->cost_type }}"
                                                data-cost="{{ $reminder->cost }}" data-litres_new="{{ $reminder->litres_new }}"
                                                data-description="{{ $reminder->description }}"><i
                                                    class="fa fa-pencil-square-o"></i> Edit
                                        </button>
                                    </td>
                                    <td>{{ !empty($reminder->date) ? date(' d M Y', $reminder->date) : '' }}</td>
                                    <td>{{ !empty($reminder->document_number) ?  $reminder->document_number : '' }}</td>
                                    <td>{{ !empty($reminder->supplier_name) ?  $reminder->supplier_name : '' }}</td>
                                    <td>{{ (!empty($reminder->cost_type)) ?  $costtype[$reminder->cost_type] : ''}}</td>
                                    <td>{{ !empty($reminder->cost) ?  'R' .number_format($reminder->cost, 2): '' }}</td>
                                    <td>{{ !empty($reminder->litres_new) ?  number_format($reminder->litres_new, 2) : '' }}</td>
                                    <td>{{ !empty($reminder->description) ?  $reminder->description : '' }}</td>
                                    <td>{{ !empty($reminder->first_name . ' ' . $reminder->surname) ? $reminder->first_name . ' ' . $reminder->surname : ''}}</td>
                                    <td>
                                        
                                    </td>
<!--<button type="button" class="btn btn-danger btn-xs" data-toggle="modal"
                                                data-target="#delete-contact-warning-modal"><i class="fa fa-trash"></i>
                                            Delete
                                        </button>-->
                                </tr>
                            @endforeach
                        @else
                            <tr id="categories-list">
                                <td colspan="10">
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        No Record for this vehicle, please start by adding a new Record for this
                                        vehicle..
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
                                data-target="#add-costs-modal">Add New Costs
                        </button>
                    </div>
                </div>
            </div>
            <!-- Include add new prime rate modal -->
        @include('Vehicles.partials.add_generalcosts_modal')
        @include('Vehicles.partials.edit_generalcosts_modal')
        <!-- Include delete warning Modal form-->
            @if (count($generalcost) > 0)
                @include('Vehicles.warnings.costs_warning_action', ['modal_title' => 'Delete Task', 'modal_content' => 'Are you sure you want to delete this Safe ? This action cannot be undone.'])
            @endif


        </div>


        @endsection

        @section('page_script')
            <script src="/custom_components/js/modal_ajax_submit.js"></script>
            <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
            <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
            <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
            <!-- iCheck -->
            <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
            <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
            <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
            <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
            <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
            <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js"
                    type="text/javascript"></script>
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
            <script>
                function postData(id, data) {
                    if (data == 'actdeac') location.href = "/vehicle_management/reminder_act/" + id;

                }

                $('#back_button').click(function () {
                    location.href = '/vehicle_management/viewdetails/{{ $maintenance->id }}';
                });


                var moduleId;
                //Initialize Select2 Elements
                $(".select2").select2();
                $('.zip-field').hide();


                //Tooltip

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


                $(document).ready(function () {

                    $('input[name="date"]').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });


                });

                $('#ss_date').datepicker({
                    format: 'dd/mm/yyyy',
                    autoclose: true,
                    todayHighlight: true
                });


                //Post perk form to server using ajax (add)
                $('#add_costs').on('click', function () {
                    var strUrl = '/vehicle_management/addcosts';
                    var formName = 'add-costs-form';
                    var modalID = 'add-costs-modal';
                    var submitBtnID = 'add_costs';
                    var redirectUrl = '/vehicle_management/general_cost/{{ $maintenance->id }}';
                    var successMsgTitle = 'New Record Added!';
                    var successMsg = 'The Record  has been updated successfully.';
                    modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                });


                var costsID;
                $('#edit-costs-modal').on('show.bs.modal', function (e) {
                    var btnEdit = $(e.relatedTarget);
                    if (parseInt(btnEdit.data('id')) > 0) {
                        costsID = btnEdit.data('id');
                    }
                    //console.log('gets here: ' + incidentID);
                    //costsID = btnEdit.data('id');
                    var date = btnEdit.data('date');
                    var document_number = btnEdit.data('document_number');
                    var supplier_name = btnEdit.data('supplier_name');
                    var cost_type = btnEdit.data('cost_type');
                    var cost = btnEdit.data('cost');
                    var litres_new = btnEdit.data('litres_new');
                    var description = btnEdit.data('description');
                    var person_esponsible = btnEdit.data('person_esponsible');
                    var valueID = btnEdit.data('valueID');
                    var modal = $(this);
                    modal.find('#date').val(date);
                    modal.find('#document_number').val(document_number);
                    modal.find('#supplier_name').val(supplier_name);
                    modal.find('#cost_type').val(cost_type);
                    modal.find('#cost').val(cost);
                    modal.find('#litres_new').val(litres_new);
                    modal.find('#description').val(description);
                    modal.find('#person_esponsible').val(person_esponsible);
                    modal.find('#valueID').val(valueID);
                });


                 $('#edit_costs').on('click', function () {
                     var strUrl = '/vehicle_management/edit_costs/' + costsID;
                    var formName = 'edit-costs-form';
                    var modalID = 'edit-costs-modal';
                    var submitBtnID = 'edit_costs';
                    var redirectUrl = '/vehicle_management/general_cost/{{ $maintenance->id }}';
                    var successMsgTitle = 'Changes Saved!';
                    var successMsg = 'The Record  has been updated successfully.';
                    var Method = 'PATCH';
                    modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                });


            </script>
@endsection
