@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title"> Vehicle Approval </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <form name="leave-application-form" class="form-horizontal" method="POST"
                      action="/vehicle_management/vehicleApproval"
                      enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="box-body">
						<div style="overflow-X:auto;">
							<table class="table table-bordered">
								<tr>
									<th style="width: 10px; text-align: center;"></th>
									<th style="width: 10px; text-align: center;"></th>
									<th>Vehicle Model/Year</th>
									<th>Fleet Number</th>
									<th>Vehicle Registration</th>
									<th>Odometer/Hours</th>
									<th>Company</th>
									<th>Department</th>
									<th style="width: 5px; text-align: center;">Accept <input type="checkbox"
																							  id="checkallaccept"
																							  onclick="checkAllboxAccept()"/>
									</th>
									<th style="width: 5px; text-align: center;">Decline</th>
									<td></td>
									<td>Rejection Reason</td>
								</tr>
								@if (count($Vehiclemanagemnt) > 0)
									@foreach ($Vehiclemanagemnt as $fleet)
										@if ($fleet->status == 3)
											<tr bgcolor="#FF0000">
												<td>
													<a href="{{ '/vehicle_management/viewdetails/' . $fleet->id }}"
														   id="fleet_view" class="btn btn-sm btn-default btn-flat"
														   target=”_blank”> View</a>
												</td>
												<td>
													<div class="product-img">
														<img src="{{ (!empty($fleet->image)) ? Storage::disk('local')->url("Vehicle/images/$fleet->image") : 'http://placehold.it/60x50' }}"
															 alt="Product Image" width="75" height="50">
													</div>
												</td>
												<td>{{ (!empty( $fleet->vehiclemodel . ' ' . $fleet->year )) ?   $fleet->vehiclemodel . ' ' . $fleet->year : ''}} </td>
												<td>{{ (!empty( $fleet->fleet_number)) ?  $fleet->fleet_number : ''}} </td>
												<td>{{ (!empty( $fleet->vehicle_registration)) ?  $fleet->vehicle_registration : ''}} </td>
												<td>{{ (!empty( $fleet->odometer_reading)) ?  $fleet->odometer_reading.''.'Kms' : '' }} </br>
													{{ !empty($fleet->hours_reading) ? $fleet->hours_reading.''.'Hrs' : '' }}</td>
												<td>{{ (!empty( $fleet->Department)) ?  $fleet->Department : ''}} </td>
												<td>{{ (!empty( $fleet->company)) ?  $fleet->company : ''}} </td>
												<td style='text-align:center'>
													<input type="hidden" class="checkbox selectall"
														   id="vehicleappprove_{{ $fleet->id }}"
														   name="vehicleappprove_{{ $fleet->id }}" value="0">
													<input type="checkbox" class="checkbox selectall"
														   id="vehicleappprove_{{ $fleet->id }}"
														   name="vehicleappprove_{{ $fleet->id }}"
														   value="1" {{$fleet->status === 1 ? 'checked ="checked"' : 0 }}>
												</td>
												<td style="text-align:center"><input type="checkbox" class="checkalldeclines "
																					 id="decline_$aVehicles[id]"
																					 onclick="$('#comment_id_{{$fleet->id}}').toggle(); uncheckCheckBoxes({{$fleet->id}}, 0);">
												</td>
												<td>
													{{--  <input type="text" size="30" id="comment_id_{{$fleet->id}}" name="declined_{{$fleet->id}}" style="display:none">         --}}
													<textarea class="form-control" id="comment_id_{{$fleet->id}}"
															  name="declined_{{$fleet->id}}"
															  placeholder="Enter rejection reason ..." rows="2"
															  style="display:none"></textarea>
												</td>
												<td>{{ (!empty( $fleet->reject_reason)) && $fleet->status == 3 ?  $fleet->reject_reason : ''}} </td>
											</tr>
										@else
											<tr>
												<td>
													<a href="{{ '/vehicle_management/viewdetails/' . $fleet->id }}"
														   id="fleet_view" class="btn btn-sm btn-default btn-flat"
														   target=”_blank”> View</a>
												</td>
												<td>
													<div class="product-img">
														<img src="{{ (!empty($fleet->image)) ? Storage::disk('local')->url("Vehicle/images/$fleet->image") : 'http://placehold.it/60x50' }}"
															 alt="Product Image" width="75" height="50">
													</div>
												</td>
												<td>{{ (!empty( $fleet->vehiclemodel . ' ' . $fleet->year )) ?   $fleet->vehiclemodel . ' ' . $fleet->year : ''}} </td>
												<td>{{ (!empty( $fleet->fleet_number)) ?  $fleet->fleet_number : ''}} </td>
												<td>{{ (!empty( $fleet->vehicle_registration)) ?  $fleet->vehicle_registration : ''}} </td>
												<td>{{ (!empty( $fleet->odometer_reading)) ?  $fleet->odometer_reading.''.'Kms' : '' }} </br>
													{{ !empty($fleet->hours_reading) ? $fleet->hours_reading.''.'Hrs' : '' }}</td>
												<td>{{ (!empty( $fleet->Department)) ?  $fleet->Department : ''}} </td>
												<td>{{ (!empty( $fleet->company)) ?  $fleet->company : ''}} </td>
												<td style='text-align:center'>
													<input type="hidden" class="checkbox selectall"
														   id="vehicleappprove_{{ $fleet->id }}"
														   name="vehicleappprove_{{ $fleet->id }}" value="0">
													<input type="checkbox" class="checkbox selectall"
														   id="vehicleappprove_{{ $fleet->id }}"
														   name="vehicleappprove_{{ $fleet->id }}"
														   value="1" {{$fleet->status === 1 ? 'checked ="checked"' : 0 }}>
												</td>
												<td style="text-align:center"><input type="checkbox" class="checkalldeclines "
																					 id="decline_$aVehicles[id]"
																					 onclick="$('#comment_id_{{$fleet->id}}').toggle(); uncheckCheckBoxes({{$fleet->id}}, 0);">
												</td>
												<td>
													{{--  <input type="text" size="30" id="comment_id_{{$fleet->id}}" name="declined_{{$fleet->id}}" style="display:none">         --}}
													<textarea class="form-control" id="comment_id_{{$fleet->id}}"
															  name="declined_{{$fleet->id}}"
															  placeholder="Enter rejection reason ..." rows="2"
															  style="display:none"></textarea>
												</td>
												<td>{{ (!empty( $fleet->reject_reason)) && $fleet->status == 3 ?  $fleet->reject_reason : ''}} </td>
											</tr>
										@endif
									@endforeach
								@else
									<tr id="categories-list">
										<td colspan="12">
											<div class="alert alert-danger alert-dismissable">
												<button type="button" class="close" data-dismiss="alert" aria-hidden="true">
													&times;
												</button>
												No vehicles to display, please start by adding a new vehicles..
											</div>
										</td>
									</tr>
								@endif
							</table>
							<!--   </div> -->
							<!-- /.box-body -->
							<div class="box-footer">
								<button type="submit" class="btn btn-primary pull-right"> Submit</button>
							</div>
						</div>
                    </div>
            </div>
            <!-- Include add new prime rate modal -->
            {{--  @include('Vehicles.Vehicle Approvals.decline_vehicle_modal')  --}}
            </form>
        </div>
        @endsection

        @section('page_script')
            <script src="/custom_components/js/modal_ajax_submit.js"></script>
            <!-- Select2 -->
            <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
            <!-- iCheck -->
            <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

            <script>
                $('#back_button').click(function () {
                    location.href = '/vehicle_management/setup';
                });

                function toggle(source) {
                    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
                    for (var i = 0; i < checkboxes.length; i++) {
                        if (checkboxes[i] != source)
                            checkboxes[i].checked = source.checked;
                    }
                }

                //
                function checkAllboxAccept() {
                    if ($('#checkallaccept:checked').val() == 'on') {
                        $('.selectall').prop('checked', true);
                    }
                    else {
                        $('.selectall').prop('checked', false);
                    }
                }

                function checkAllboxreject() {
                    if ($('#checkallreject:checked').val() == 'on') {
                        $('.reject').prop('checked', true);
                    }
                    else {
                        $('.reject').prop('checked', false);
                    }
                }

                $(function () {
                    var moduleId;
                    //Initialize Select2 Elements
                    $(".select2").select2();

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

                    $(".js-example-basic-multiple").select2();

                    //Cancell booking
                    //Post module form to server using ajax (ADD)

                    //save reject reason


                    var reasonID;
                    $('#decline-vehicle-modal').on('show.bs.modal', function (e) {
                        var btnEdit = $(e.relatedTarget);
                        if (parseInt(btnEdit.data('id')) > 0) {
                            reasonID = btnEdit.data('id');
                        }
                        console.log('gets here: ' + reasonID);
                        var description = btnEdit.data('description');
                        var modal = $(this);
                        modal.find('#description').val(description);
                    });

                    $('#rejection-reason').on('click', function () {
                        var strUrl = '/vehicle_management/reject_vehicle/' + reasonID;
                        var modalID = 'decline-vehicle-modal';
                        var objData = {
                            description: $('#' + modalID).find('#description').val(),
                            _token: $('#' + modalID).find('input[name=_token]').val()
                        };
                        var submitBtnID = 'rejection-reason';
                        var redirectUrl = '/vehicle_management/vehicle_approval';
                        var successMsgTitle = 'Reason Added!';
                        var successMsg = 'The reject reason has been updated successfully.';
                        var Method = 'PATCH';
                        modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
                    });


                });


            </script>
@endsection
