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
                    <h3 class="box-title"> Vehicle Fire Extinguishers  </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
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
                    <table class="table table-bordered">
                        <tr>
                             <th style="width: 10px; text-align: center;"></th>
                            <th style="width: 10px; text-align: center;"></th>
                            <th>Barcode</th>
                            <th>Item</th>
                            <th>Company</th>
                            <th>Department</th>
                            <th>Last Audit</th>
                            <th>Serial Number</th>
                            <th>Date Purchased</th>
                            <th>Cost </th>
                            <th>Rental Amount </th>
                            <th>Status </th>
                            <th style="width: 5px; text-align: center;"></th>
                        </tr>
                        @if (count($fireextinguishers) > 0)
                            @foreach ($fireextinguishers as $extinguishers)
                                <tr id="categories-list">
                                    <td nowrap>
                                        <button vehice="button" id="edit_compan" class="btn btn-warning  btn-xs"
                                                data-toggle="modal" data-target="#edit-firestatus-modal"
                                                data-id="{{ $extinguishers->id }}" data-bar_code="{{$extinguishers->bar_code}}"
                                                data-item_no="{{ $extinguishers->item_no }}" data-Description="{{$extinguishers->Description}}"
                                                data-Weight="{{ $extinguishers->Weight }}" data-Serial_number="{{$extinguishers->Serial_number}}"
                                                data-purchase_order="{{ $extinguishers->purchase_order }}" data-invoice_number="{{$extinguishers->invoice_number}}"
                                                data-supplier_id="{{ $extinguishers->supplier_id }}" data-date_purchased="{{date(' d M Y', $extinguishers->date_purchased)}}"
                                                data-Cost="{{ $extinguishers->Cost }}" data-rental_amount="{{$extinguishers->rental_amount}}"
                                               
                                        ><i class="fa fa-pencil-square-o"></i> Edit
                                        </button>
                                    </td>
                                    <td>
                                        <div class="product-img">
                                        <img src="{{ (!empty($extinguishers->image)) ? Storage::disk('local')->url("Vehicle/fireextinguishers/images/$extinguishers->image") : 'http://placehold.it/60x50' }}"
                                                             alt="Product Image" width="100" height="75">
                                       </div> 
                                    </td>                
                                    <td>{{ (!empty( $extinguishers->bar_code)) ?  $extinguishers->bar_code : ''}} </td>
                                    <td>{{ (!empty( $extinguishers->item_no)) ?  $extinguishers->item_no : ''}} </td>
                                    <td>{{ (!empty( $extinguishers->company)) ?  $extinguishers->company : ''}} </td>
                                    <td>{{ (!empty( $extinguishers->Department)) ?  $extinguishers->Department : ''}} </td>
                                    <td> </td>
                                    <td>{{ (!empty( $extinguishers->Serial_number)) ?  $extinguishers->Serial_number : ''}} </td>
                                    <td>{{ !empty($extinguishers->date_purchased) ? date(' d M Y', $extinguishers->date_purchased) : '' }} </td>
                                    <td>{{ !empty($extinguishers->Cost) ?  'R' .number_format($extinguishers->Cost, 2): '' }}</td>
                                    <td>{{ !empty($extinguishers->rental_amount) ?  'R' .number_format($extinguishers->rental_amount, 2): 0 }}</td>
                                    <td>{{ (!empty( $extinguishers->Status)) ?  $status[$extinguishers->Status] : ''}} </td>
                                    <td nowrap>
                                        
                                         <button details="button" id="edit_compan" class="btn btn-warning  btn-xs"
                                                data-toggle="modal" data-target="#add-safe-modal"
                                                data-id="{{ $extinguishers->id }}" data-Status="{{ $extinguishers->Status }}"
                                                ><i class="fa fa-pencil-square-o"></i>
                                            Change Status
                                        </button>
                                    </td>

                                </tr>
                            @endforeach
                        @else
                            <tr id="categories-list">
                                <td colspan="14">
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        No Record for this vehicle, please start by adding Record for this
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
                                data-target="#add_fireextinguishers-modal">Add New Fire Extinguishers
                        </button>
                    </div>
                </div>
            </div>
        <!-- Include Modal form-->
          @include('Vehicles.partials.add_fire_extinguishers_modal')
          @include('Vehicles.partials.fire_extinguishers_status_modal')
          @include('Vehicles.partials.edit_fireextinguisher_modal')
          @include('Vehicles.partials.add_extinguisherstatus_modal')
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
                    if (data == 'actdeac') location.href = "/vehicle_management/policy_act/" + id;

                }
            $(function () {
                $('#back_button').click(function () {
                    location.href = '/vehicle_management/viewdetails/{{ $maintenance->id }}';
                });

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

                    $('#date_purchased').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });


                     $('#inceptiondate').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });


                });

                $('#addfireextinguishers').on('click', function () {
                    var strUrl = '/vehicle_management/addfireextinguishers';
                    var formName = 'add-fireextinguishers-form';
                    var modalID = 'add_fireextinguishers-modal';
                    var submitBtnID = 'addfireextinguishers';
                    var redirectUrl = '/vehicle_management/fire_extinguishers/{{ $maintenance->id }}';
                    var successMsgTitle = 'New Record Added!';
                    var successMsg = 'The Record  has been updated successfully.';
                    modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                });

                
               var reject_ID;
                $('#add-safe-modal').on('show.bs.modal', function (e) {
                    var btnEdit = $(e.relatedTarget);
                    reject_ID = btnEdit.data('id');
                    var Status = btnEdit.data('Status');
                    var modal = $(this);
                   modal.find('#Status').val(Status);
                });
                //Post module form to server using ajax (ADD)
                
                
                 $('#edit_status').on('click', function() {
                var strUrl = '/vehicle_management/changestatus/' + reject_ID;
                var formName = 'edit-module-form';
                var modalID = 'add-safe-modal';
                var submitBtnID = 'edit_status';
                var redirectUrl = '/vehicle_management/fire_extinguishers/{{ $maintenance->id }}';
                    var successMsgTitle = 'New Record Added!';
                    var successMsg = 'The Record  has been updated successfully.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                });
                
                     
                var fire_ID;
                $('#edit-firestatus-modal').on('show.bs.modal', function (e) {
                    var btnEdit = $(e.relatedTarget);
                    fire_ID = btnEdit.data('id');
                    var bar_code = btnEdit.data('bar_code');
                    var item_no = btnEdit.data('item_no');
                    var Description = btnEdit.data('Description');
                    var Weight = btnEdit.data('Weight');
                    var Serial_number = btnEdit.data('Serial_number');
                    var purchase_order = btnEdit.data('purchase_order');
                    var invoice_number = btnEdit.data('invoice_number');
                    var supplier_id = btnEdit.data('supplier_id');
                    var date_purchased = btnEdit.data('date_purchased');
                    var Cost = btnEdit.data('Cost');
                    var rental_amount = btnEdit.data('rental_amount');
                    var valueID = btnEdit.data('valueID');
                    var modal = $(this);
                   modal.find('#bar_code').val(bar_code);
                   modal.find('#item_no').val(item_no);
                   modal.find('#Description').val(Description);
                   modal.find('#Weight').val(Weight);
                   modal.find('#Serial_number').val(Serial_number);
                   modal.find('#purchase_order').val(purchase_order);
                   modal.find('#invoice_number').val(invoice_number);
                   modal.find('#supplier_id').val(supplier_id);
                   modal.find('#date_purchased').val(date_purchased);
                   modal.find('#Cost').val(Cost);
                   modal.find('#rental_amount').val(rental_amount);
                   modal.find('#valueID').val(valueID);
                });
                //Post module form to server using ajax (ADD)
                
                
              $('#edit_firestatus').on('click', function() {
                var strUrl = '/vehicle_management/editfireexting/' + fire_ID;
                var formName = 'edit-firestatus-form';
                var modalID = 'edit-firestatus-modal';
                var submitBtnID = 'edit_firestatus';
                var redirectUrl = '/vehicle_management/fire_extinguishers/{{ $maintenance->id }}';
                    var successMsgTitle = 'New Record Added!';
                    var successMsg = 'The Record  has been updated successfully.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
              });
                             
            });
     
            </script>
@endsection
