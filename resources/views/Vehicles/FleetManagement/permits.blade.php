@extends('layouts.main_layout')
@section('page_dependencies')
 <!-- Include Date Range Picker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
    <!--Time Charger-->
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title"> Permits/Licences </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- <form class="form-horizontal" method="POST" action="/hr/document"> -->
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
                                        -| &nbsp; &nbsp; <strong>Vehicle Registration:</strong> <em>{{ $maintenance->vehicle_registration }}</em> &nbsp; &nbsp;
                                    @endif
                                    @if(!empty($maintenance->year))
                                        -| &nbsp; &nbsp; <strong>Year:</strong> <em>{{ $maintenance->year }}</em> &nbsp; &nbsp;
                                    @endif
                                     @if(!empty($maintenance->vehicle_color))
                                        -| &nbsp; &nbsp; <strong>Vehicle Color:</strong> <em>{{ $maintenance->vehicle_color }}</em> &nbsp; &nbsp; -|
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
                        <a href="{{ '/vehicle_management/oil_log/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-file-o"></i> Oil Log
                        </a>
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
							    <th>License Type</th>
                                <th>Supplier</th>
                                <th>Permit/Licence Number </th>
                                <th> Date Issued </th>
                                <th> Expiry Date</th>
                                <th>Status </th>
                                <th>Captured By</th>
                                <th>Date Captured</th>
                                <th> Document</th>
                                <th style="width: 10px; text-align: center;"></th>
                            </tr>
                            @if (count($permits) > 0)
								@foreach ($permits as $permit)
								<tr id="categories-list">
                            		<td>
										<button type="button" id="edit_compan" class="btn btn-default  btn-xs" data-toggle="modal" 
										data-target="#edit-permit-modal" data-id = "{{ $permit->id }}" 
										data-supplier_id ="{{ $permit->Supplier }}" 
										data-permits_licence_no = "{{ $permit->permits_licence_no }}"
										data-date_issued = "{{ date(' d M Y', $permit->date_issued)}}"
										data-exp_date = "{{ date(' d M Y', $permit->exp_date)}}"  
										data-licence_id="{{ $permit->permit_licence }}"
										data-status="{{ $permit->status }}" data-captured_by ="{{ $permit->captured_by }}" 
										data-date_captured ="{{ $permit->date_captured }}"><i class="fa fa-pencil-square-o"></i> Edit</button> 
									</td>
                                    @if (isset($permit) && $permit->exp_date < $currentdate)
										<td bgcolor="red">{{ !empty($permit->license_name) ? $permit->license_name : '' }} {{ $permit->permit_licence }}</td>
										<td bgcolor="red">{{ !empty($permit->comp_name) ? $permit->comp_name : ''}}</td>
										<td bgcolor="red">{{ !empty($permit->permits_licence_no) ? $permit->permits_licence_no : ''}}</td>
										<td bgcolor="red">{{ !empty($permit->date_issued) ? date(' d M Y', $permit->date_issued) : '' }}</td>
										<td bgcolor="red">{{ !empty($permit->exp_date) ? date(' d M Y', $permit->exp_date) : '' }}</td>
										<td bgcolor="red">{{ (!empty($permit->status)) ?  $status[$permit->status] : ''}}</td>
										<td bgcolor="red">{{ !empty($permit->captured_by) ? $permit->captured_by : ''}}</td>
										<td bgcolor="red">{{ !empty($permit->date_captured) ? date(' d M Y', $permit->date_captured) : '' }}</td>
										<td bgcolor="red" nowrap>
											<div class="form-group{{ $errors->has('document') ? ' has-error' : '' }}">
												<label for="document" class="control-label"></label>
												@if(!empty($permit->document))
													<a class="btn btn-default btn-flat btn-block pull-right btn-xs"
													   href="{{ Storage::disk('local')->url("Vehicle/permits_licence/$permit->document") }}"
													   target="_blank"><i class="fa fa-file-pdf-o"></i> View Document</a>
												@else
												<a class="btn btn-default pull-centre btn-xs"><i class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
												@endif
											</div>
										</td>
										<td bgcolor="red"> Expired </td>  
									@else
										<td>{{ !empty($permit->license_name) ? $permit->license_name : '' }}</td>
										<td>{{ !empty($permit->comp_name) ? $permit->comp_name : ''}}</td>
										<td>{{ !empty($permit->permits_licence_no) ? $permit->permits_licence_no : ''}}</td>
										<td>{{ !empty($permit->date_issued) ? date(' d M Y', $permit->date_issued) : '' }}</td>
										<td>{{ !empty($permit->exp_date) ? date(' d M Y', $permit->exp_date) : '' }}</td>
										<td>{{ (!empty($permit->status)) ?  $status[$permit->status] : ''}}</td>
										<td>{{ !empty($permit->captured_by) ? $permit->captured_by : ''}}</td>
										<td>{{ !empty($permit->date_captured) ? date(' d M Y', $permit->date_captured) : '' }}</td>
										<td nowrap>
											<div class="form-group{{ $errors->has('document') ? ' has-error' : '' }}">
												<label for="document" class="control-label"></label>
												@if(!empty($permit->document))
													<a class="btn btn-default btn-flat btn-block pull-right btn-xs"
													   href="{{ Storage::disk('local')->url("Vehicle/permits_licence/$permit->document") }}"
													   target="_blank"><i class="fa fa-file-pdf-o"></i> View Document</a>
												@else
												<a class="btn btn-default pull-centre btn-xs"><i class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
												@endif
											</div>
										</td>
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            <tr id="categories-list">
                                <td colspan="10">
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        No Record records for this vehicle, please start by adding
                                        Record records for this vehicle..
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
                                data-target="#add-permit-modal">Add New Permits/Licences
                        </button>
                    </div>
                </div>
            </div>
            <!-- Include add new prime rate modal -->
        @include('Vehicles.partials.add_permits_licence_modal')
        @include('Vehicles.partials.edit_permits_licence_modal')
        <!-- Include delete warning Modal form-->
        </div>

    @endsection

    @section('page_script')

        <!-- Select2 -->
            <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
            <!-- bootstrap datepicker -->
            <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>

            <!-- InputMask -->
            <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
            <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
            <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

            <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
            <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js"
                    type="text/javascript"></script>
            <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->

            <!-- the main fileinput plugin file -->
            <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>

            <!-- iCheck -->
            <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

            <!-- Ajax dropdown options load -->
            <script src="/custom_components/js/load_dropdown_options.js"></script>
            <!-- Ajax form submit -->
            <script src="/custom_components/js/modal_ajax_submit.js"></script>
            <script type="text/javascript">

                $(function () {

                    $('#back_button').click(function () {
                        location.href = '/vehicle_management/viewdetails/{{ $maintenance->id }}';
                    });
                    $(".select2").select2();
                    $('.hours-field').hide();
                    $('.comp-field').hide();
                    var moduleId;
                    //Tooltip
                    $('[data-toggle="tooltip"]').tooltip();

                    //Vertically center modals on page

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
                });
                $('input[name="date_issued"]').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });

                $('input[name="exp_date"]').datepicker({
                    format: 'dd/mm/yyyy',
                    autoclose: true,
                    todayHighlight: true
                });

                $('#date_captured').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });
                //Initialize iCheck/iRadio Elements
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '10%' // optional
                });
                //Post perk form to server using ajax (add)
                $('#add_permit').on('click', function () {
                    var strUrl = '/vehicle_management/addPermit';
                    var formName = 'add-permit-form';
                    var modalID = 'add-permit-modal';
                    var submitBtnID = 'add_permit';
                    var redirectUrl = '/vehicle_management/permits_licences/{{ $maintenance->id }}';
                    var successMsgTitle = 'New Permits/Licences Details Added!';
                    var successMsg = 'The Permits/Licences Details has been updated successfully.';
                    modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                });
                //
                var permitID;
                $('#edit-permit-modal').on('show.bs.modal', function (e) {
                    //console.log('kjhsjs');
                    var btnEdit = $(e.relatedTarget);
                     if (parseInt(btnEdit.data('id')) > 0) {
                      permitID = btnEdit.data('id');
                    }
                    var licenceID = btnEdit.data('licence_id');
                    var supplierID = btnEdit.data('supplier_id');
                    var permits_licence_no = btnEdit.data('permits_licence_no');
                    var date_issued = btnEdit.data('date_issued');
                    var exp_date = btnEdit.data('exp_date');
                    var status = btnEdit.data('status');
                    var captured_by = btnEdit.data('captured_by');
                    var modal = $(this);
                    modal.find('#name').val(name);
                    modal.find('#permits_licence_no').val(permits_licence_no);
                    modal.find('#date_issued').val(date_issued);
                    modal.find('#exp_date').val(exp_date);
                    modal.find('#status').val(status);
                    modal.find('#captured_by').val(captured_by);
					modal.find('#supplier_id').val(supplierID);
					modal.find('#permit_licence').val(licenceID);
					console.log(supplierID);
                });
            //Post perk form to server using ajax (edit)
            $('#edit_permit').on('click', function() {
                var strUrl = '/vehicle_management/edit_permit/' + permitID;
                var formName = 'edit-permit-form';
                 var modalID = 'edit-permit-modal';
                var submitBtnID = 'edit_permit';
                var redirectUrl = '/vehicle_management/permits_licences/{{$maintenance->id}}';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The  details have been updated successfully!';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
    </script>
@endsection