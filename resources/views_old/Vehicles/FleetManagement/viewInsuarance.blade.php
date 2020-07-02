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
                    <h3 class="box-title"> Vehicle Insurance  </h3>
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
                            <th>Policy/Document #</th>
                            <th>Type</th>
                            <th>Inception Date</th>
                            <th> Value Covered(R)</th>
                            <th> Premium Amount(R)</th>
                            <th> Document(s)</th>
                            <th style="width: 5px; text-align: center;"></th>
                        </tr>
                        @if (count($vehicleinsurance) > 0)
                            @foreach ($vehicleinsurance as $reminder)
                                <tr id="categories-list">
                                    <td nowrap>
                                        <button reminder="button" id="edit_compan" class="btn btn-warning  btn-xs"
                                                data-toggle="modal" data-target="#edit-policy-modal"
                                                data-id="{{ $reminder->id }}" data-name="{{ $reminder->name }}"
                                                data-description="{{ $reminder->description }}"  data-service_provider="{{ $reminder->service_provider }}"
                                                data-contact_person="{{ $reminder->contact_person }}"  data-contact_number="{{ $reminder->contact_number }}"
                                                data-contact_email="{{ $reminder->contact_email }}"  data-address="{{ $reminder->address }}"   data-inceptiondate ="{{ date(' d M Y', $reminder->inception_date)}}"
                                                data-policy_no="{{ $reminder->policy_no }}"  data-premium_amount="{{ $reminder->premium_amount }}"  data-value_coverd="{{ $reminder->value_coverd }}"
                                                 data-type="{{ $reminder->type }}" data-notes="{{ $reminder->notes }}"
                                                data-document="{{ $reminder->document }}"
                                               ><i class="fa fa-pencil-square-o"></i> Edit

                                        </button>
                                    </td>
                                    <td>{{ !empty($reminder->companyName) ? $reminder->companyName : '' }}</td>
                                    <td>{{ !empty($reminder->policy_no) ?  $reminder->policy_no : '' }}</td>
                                    <td>{{ !empty($reminder->type) ? $reminder->type : '' }}</td>
                                    <td>{{ !empty($reminder->inception_date) ? date(' d M Y', $reminder->inception_date) : '' }}</td>
                                    <td>{{ !empty($reminder->value_coverd) ? 'R' .number_format($reminder->value_coverd, 2) : '' }}</td>
                                    <td>{{ !empty($reminder->premium_amount) ?  'R' .number_format($reminder->premium_amount, 2) : '' }}</td>
                                    <td nowrap>
                                        <div class="form-group{{ $errors->has('document') ? ' has-error' : '' }}">
                                            <label for="document" class="control-label"></label>
                                            @if(!empty($reminder->document))
                                                <a class="btn btn-default btn-flat btn-block pull-right btn-xs"
                                                   href="{{ Storage::disk('local')->url("Vehicle/Insurance/$reminder->document") }}"
                                                   target="_blank"><i class="fa fa-file-pdf-o"></i> View Document</a>
                                            @else
                                                <a class="btn btn-default pull-centre btn-xs"><i class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
                                            @endif
                                        </div>
                                        {{--<div class="form-group{{ $errors->has('document') ? ' has-error' : '' }}">--}}
                                            {{--<label for="document" class="control-label"></label>--}}
                                            {{--@if(!empty($reminder->document1))--}}
                                                {{--<a class="btn btn-default btn-flat btn-block pull-right btn-xs"--}}
                                                   {{--href="{{ Storage::disk('local')->url("Vehicle/Insurance/$reminder->document1") }}"--}}
                                                   {{--target="_blank"><i class="fa fa-file-pdf-o"></i> View Document</a>--}}
                                            {{--@else--}}
                                                {{--<a class="btn btn-default pull-centre btn-xs"><i class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>--}}
                                            {{--@endif--}}
                                        {{--</div>--}}
                                    </td>
                                    <td>
                                        <!--   leave here  -->
                                        <button reminder="button" id="view_ribbons"
                                                class="btn {{ (!empty($reminder->status) && $reminder->status == 1) ? " btn-danger " : "btn-success " }}
                                                        btn-xs" onclick="postData({{$reminder->id}}, 'actdeac');"><i
                                                    class="fa {{ (!empty($reminder->status) && $reminder->status == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($reminder->status) && $reminder->status == 1) ? "De-Activate" : "Activate"}}
                                        </button>
                                    </td>
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
                                data-target="#add-policy-modal">Add New Policy
                        </button>
                    </div>
                </div>
            </div>
        </form>
            <!-- Include add new prime rate modal -->
        @include('Vehicles.partials.add_vehicleInsurance_modal')
        @include('Vehicles.partials.edit_vehicleInsurance_modal')
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

					$('#inception_date').datepicker({
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
			//Post perk form to server using ajax (add)
			$('#add_policy').on('click', function () {
				var strUrl = '/vehicle_management/addpolicy';
				var formName = 'add-policy-form';
				var modalID = 'add-policy-modal';
				var submitBtnID = 'add_policy';
				var redirectUrl = '/vehicle_management/insurance/{{ $maintenance->id }}';
				var successMsgTitle = 'New Record Added!';
				var successMsg = 'The Record  has been updated successfully.';
				modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
			});
			var policyID;
			$('#edit-policy-modal').on('show.bs.modal', function (e) {
				var btnEdit = $(e.relatedTarget);
				if (parseInt(btnEdit.data('id')) > 0) {
					policyID = btnEdit.data('id');
				 }
				var service_provider = btnEdit.data('service_provider');
				var contact_person = btnEdit.data('contact_person');
				var contact_number = btnEdit.data('contact_number');
				var contact_email = btnEdit.data('contact_email');
				var address = btnEdit.data('address');
				var policy_no = btnEdit.data('policy_no');
				var inception_date = btnEdit.data('inceptiondate');
				var exp_date = btnEdit.data('exp_date');
				var value_coverd = btnEdit.data('value_coverd');
				var premium_amount = btnEdit.data('premium_amount');
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
				modal.find('#inception_date').val(inception_date);
				modal.find('#exp_date').val(exp_date);
				modal.find('#value_coverd').val(value_coverd);
				modal.find('#premium_amount').val(premium_amount);
				modal.find('#type').val(type);
				modal.find('#warranty_amount').val(warranty_amount);
				modal.find('#description').val(description);
				modal.find('#notes').val(notes);
				modal.find('#documents').val(documents);
				modal.find('#name').val(name);
				modal.find('#valueID').val(valueID);
			});

			 $('#edit_insurance').on('click', function () {
				var strUrl = '/vehicle_management/edit_policy/'+ policyID ;
				var formName = 'edit-policy-form';
				var modalID = 'edit-policy-modal';
				var submitBtnID = 'edit_insurance';
				var redirectUrl = '/vehicle_management/insurance/{{ $maintenance->id }}';
				var successMsgTitle = 'New Policy Details have been updated!';
				var successMsg = 'The Policy Details has been updated successfully.';
				var Method = 'PATCH';
				modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
			});
		});	
            </script>
@endsection
