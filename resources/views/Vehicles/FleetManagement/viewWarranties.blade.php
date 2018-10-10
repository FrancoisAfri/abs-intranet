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
                    <h3 class="box-title"> Vehicle Warranties </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div><!-- 
        
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
                            <th>Service Provider</th>
                            <th>Contact Person</th>
                            <th>Policy/Document #</th>
                            <th>Type</th>
                            <th>Inception Date</th>
                            <th>Expiry Date</th>
                            <th>Warranty Amount (R)</th>
                            <th>Maximum Kliometres</th>
                            <th> Documents</th>
                            <th style="width: 5px; text-align: center;"></th>
                        </tr>
                        @if (count($vehiclewarranties) > 0)
                            @foreach ($vehiclewarranties as $warranty)
                                <tr id="categories-list">
                                    <td nowrap>
                                        <button warranty="button" id="edit_compan" class="btn btn-warning  btn-xs"
                                                data-toggle="modal" data-target="#edit-warrantie-modal"
                                                data-id="{{ $warranty->id }}" data-name="{{ $warranty->name }}"
                                                data-description="{{ $warranty->description }}"
                                                data-service_provider="{{ $warranty->service_provider }}"
                                                data-contact_person="{{ $warranty->contact_person }}"
                                                data-contact_number="{{ $warranty->contact_number }}"
                                                data-contact_email="{{ $warranty->contact_email }}"
                                                data-address="{{ $warranty->address }}"
                                                data-inceptiondate="{{ date(' d M Y', $warranty->inception_date)}}"
                                                data-expdate="{{ date(' d M Y', $warranty->exp_date)}}"
                                                data-policy_no="{{ $warranty->policy_no }}"
                                                data-warranty_period="{{ $warranty->warranty_period }}"
                                                data-kilometers="{{ $warranty->kilometers }}"
                                                data-warranty_amount="{{ $warranty->warranty_amount }}"
                                                data-type="{{ $warranty->type }}"
                                                data-notes="{{ $warranty->notes }}"
                                                data-document="{{ $warranty->document }}"
                                        ><i class="fa fa-pencil-square-o"></i> Edit

                                        </button>
                                    </td>
                                    <td>{{ !empty($warranty->serviceprovider) ? $warranty->serviceprovider : '' }}</td>
                                    <td style="width: 20px;">{{ !empty($warranty->first_name) ? $warranty->first_name." ".$warranty->surname." |".$warranty->cell_number." |".$warranty->res_address : '' }}</td>
                                    <td>{{ !empty($warranty->policy_no) ?  $warranty->policy_no : '' }}</td>
                                    <td>{{ !empty($warranty->type) ? $warranty->type : '' }}</td>
                                    <td>{{ !empty($warranty->inception_date) ? date(' d M Y', $warranty->inception_date) : '' }}</td>
                                    <td>{{ !empty($warranty->exp_date) ? date(' d M Y', $warranty->exp_date) : '' }}</td>
                                    <td>{{ !empty($warranty->warranty_amount) ?  'R' .number_format($warranty->warranty_amount, 2) : '' }}</td>                                                           
                                    <td>{{ !empty($warranty->kilometers) ? 'km' .number_format($warranty->kilometers, 2) : '' }}</td>
                                    <td nowrap>
                                        <div class="form-group{{ $errors->has('document') ? ' has-error' : '' }}">
                                            <label for="document" class="control-label"></label>
                                            @if(!empty($warranty->document))
                                                <a class="btn btn-default btn-flat btn-block pull-right btn-xs"
                                                   href="{{ Storage::disk('local')->url("Vehicle/warranty/$warranty->document") }}"
                                                   target="_blank"><i class="fa fa-file-pdf-o"></i> View Document</a>
                                            @else
                                                <a class="btn btn-default pull-centre btn-xs"><i class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <!--   leave here  -->
                                        <button warranty="button" id="view_ribbons"
                                                class="btn {{ (!empty($warranty->status) && $warranty->status == 1) ? " btn-danger " : "btn-success " }}
                                                        btn-xs" onclick="postData({{$warranty->id}}, 'actdeac');"><i
                                                    class="fa {{ (!empty($warranty->status) && $warranty->status == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($warranty->status) && $warranty->status == 1) ? "De-Activate" : "Activate"}}
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
                                data-target="#add-warrantie-modal">Add New Warranty
                        </button>
                    </div>
                </div>
            </div>
            <!-- Include add new prime rate modal -->
            @include('Vehicles.partials.add_vehicleWarranties_modal')
            @include('Vehicles.partials.edit_vehicleWarranties_modal')
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


              <!-- Ajax dropdown options load -->
            <script src="/custom_components/js/load_dropdown_options.js"></script>
            <script>
                function postData(id, data) {
                    if (data == 'actdeac') location.href = "/vehicle_management/warranty_act/" + id;

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

                //

                $(".js-example-basic-multiple").select2();

                //Initialize iCheck/iRadio Elements
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '10%' // optional
                });


                $(document).ready(function () {

                    $('input[name="inception_date"]').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });

                     $('input[name="exp_date"]').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });

                    $('#inceptiondate').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });

                    $('#expdate').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });


                });


                //Post perk form to server using ajax (add)
                $('#add_warrantie').on('click', function () {
                    var strUrl = '/vehicle_management/Addwarranty';
                    var formName = 'add-warrantie-form';
                    var modalID = 'add-warrantie-modal';
                    var submitBtnID = 'add_warrantie';
                    var redirectUrl = '/vehicle_management/warranties/{{ $maintenance->id }}';
                    var successMsgTitle = 'New Record Added!';
                    var successMsg = 'The Record  has been updated successfully.';
                    modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                });


                var warrantyID;
                $('#edit-warrantie-modal').on('shown.bs.modal', function (e) {
                    var btnEdit = $(e.relatedTarget);
                    if (parseInt(btnEdit.data('id')) > 0) {
                        warrantyID = btnEdit.data('id');
                    }
                   // console.log('gets here: ' + warrantyID);

                    var service_provider = btnEdit.data('service_provider');
                    var contact_person = btnEdit.data('contact_person');
                    var contact_number = btnEdit.data('contact_number');
                    var contact_email = btnEdit.data('contact_email');
                    var address = btnEdit.data('address');
                    var policy_no = btnEdit.data('policy_no');
                    var inceptiondate = btnEdit.data('inceptiondate');
                    var exp_date = btnEdit.data('expdate');
                    var warranty_period = btnEdit.data('warranty_period');
                    var kilometers = btnEdit.data('kilometers');
                    var type = btnEdit.data('type');
                    var warranty_amount = btnEdit.data('warranty_amount');
                    var description = btnEdit.data('description');
                    var notes = btnEdit.data('notes');
                    var documents = btnEdit.data('documents');
                    var name = btnEdit.data('name');
                    var valueID = btnEdit.data('valueID');
                    var modal = $(this);
                    modal.find('#service_provider').val(service_provider);
                    modal.find('#contact_person').val(contact_person);
                    modal.find('#contact_number').val(contact_number);
                    modal.find('#contact_email').val(contact_email);
                    modal.find('#address').val(address);
                    modal.find('#policy_no').val(policy_no);
                    modal.find('#inceptiondate').val(inceptiondate);
                    modal.find('#expdate').val(exp_date);
                    modal.find('#warranty_period').val(warranty_period);
                    modal.find('#kilometers').val(kilometers);
                    modal.find('#type').val(type);
                    modal.find('#warranty_amount').val(warranty_amount);
                    modal.find('#description').val(description);
                    modal.find('#notes').val(notes);
                    modal.find('#documents').val(documents);
                    modal.find('#name').val(name);
                    modal.find('#valueID').val(valueID);
                });


                $('#edit_warrantie').on('click', function () {
                    var strUrl = '/vehicle_management/edit_warrantie/' + warrantyID;
                    var formName = 'edit-warrantie-form';
                    var modalID = 'edit-warrantie-modal';
                    var submitBtnID = 'edit_warrantie';
                    var redirectUrl = '/vehicle_management/warranties/{{ $maintenance->id }}';
                    var successMsgTitle = 'New Record Added!';
                    var successMsg = 'The Record  has been updated successfully.';
                    var Method = 'PATCH'
                    modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                });


            </script>
@endsection
