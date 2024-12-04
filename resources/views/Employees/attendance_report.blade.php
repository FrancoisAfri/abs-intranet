@extends('layouts.main_layout')
@section('page_dependencies')

    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">
	    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
	    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
	<!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
@stop
@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user-times pull-right"></i>
                    <h3 class="box-title"> Report Results </h3>
                </div>
                <div class="box-body">
                    <div class="box-header">

                        <div class="form-group container-sm">
                            <form class="form-horizontal" method="get" action="{{ route('attendance.report') }}">
                                {{ csrf_field() }}
                                <div class="col-md-12" id="view_users">
                                    <div class="form-group">
                                        <div class="col-sm-4">
											@foreach($division_levels as $division_level)
												<div class="form-group {{ $errors->has('division_level_' . $division_level->level) ? ' has-error' : '' }}">
													<label for="{{ 'division_level_' . $division_level->level }}"
														   class="col-sm-2 control-label">{{ $division_level->name }}</label>

													<div class="col-sm-10">
														<div class="input-group">
															<div class="input-group-addon">
																<i class="fa fa-black-tie"></i>
															</div>
															<select id="{{ 'division_level_' . $division_level->level }}"
																	name="{{ 'division_level_' . $division_level->level }}"
																	class="form-control" onchange="divDDOnChange(this, 'hr_person_id', 'view_users')">
															</select>
														</div>
													</div>
												</div>
											@endforeach
                                        </div>
										<div class="col-sm-4">
                                            <label>Employees</label>
											<select class="form-control select2" multiple="multiple" style="width: 100%;" id="employee_number" name="employee_number[]">
												<option value="">*** Select Employee ***</option>
												@foreach($employees as $employee)
													<option value="{{ $employee->id }} ">{{$employee->first_name . ' ' . $employee->surname }}</option>
												@endforeach
											</select>
                                        </div>
										<div class="col-sm-4">
                                           <label for="late_arrival" class="col-sm-2 control-label">Late Arrival</label>
										   <div class="col-sm-4">
												<label class="radio-inline pull-right no-padding" style="padding-left: 0px;">
													<input class="rdo-iCheck" type="checkbox" id="late_arrival" name="late_arrival" value="1">
												</label>
											</div>
											<label for="early_clockout" class="col-sm-2 control-label">Early Clockout</label>
										   <div class="col-sm-4">
												<label class="radio-inline pull-right no-padding" style="padding-left: 0px;">
													<input class="rdo-iCheck" type="checkbox" id="early_clockout" name="early_clockout" value="1">
												</label>
											</div>
                                        </div>
										
										<div class="col-sm-4">
                                           <label for="absent" class="col-sm-2 control-label">Absent</label>
										   <div class="col-sm-4">
												<label class="radio-inline pull-right no-padding" style="padding-left: 0px;">
													<input class="rdo-iCheck" type="checkbox" id="absent" name="absent" value="1">
												</label>
											</div>
											<label for="onleave" class="col-sm-2 control-label">On Leave</label>
										   <div class="col-sm-4">
												<label class="radio-inline pull-right no-padding" style="padding-left: 0px;">
													<input class="rdo-iCheck" type="checkbox" id="onleave" name="onleave" value="1">
												</label>
											</div>
                                        </div>
										<div class="col-sm-4">
                                            <label>Dates</label>
											<input type="text" class="form-control daterangepicker" id="date_of_action"
												name="date_of_action" value="" placeholder="Select Action Date...">
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary pull-left">Submit</button>
                                        <br>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <br>
                    </div>
                    <div style="overflow-X:auto;">
                        <table id=" " class="asset table table-bordered data-table my-2">
                            <thead>
                            <tr>
                                <th style="width: 10px; text-align: center;"></th>
								@foreach($levels as $level)
									<th style="width: 10px; text-align: center;">{{ $level->name }}</th>
								@endforeach
                                <th style="width: 5px; text-align: center;">Employee Number</th>
                                <th style="width: 5px; text-align: center;">Name</th>
                                <th style="width: 5px; text-align: center;">Date</th>
                                <th style="width: 5px; text-align: center;">Clokin Time</th>
                                <th style="width: 5px; text-align: center;">Location</th>
                                <th style="width: 5px; text-align: center;">Clockout Time</th>
                                <th style="width: 5px; text-align: center;">Location</th>
                                <th style="width: 5px; text-align: center;">Hours Worked</th>
                                <th style="width: 5px; text-align: center;">Late Arrival</th>
                                <th style="width: 5px; text-align: center;">Early Clockout</th>
                                <th style="width: 5px; text-align: center;">Absent</th>
                                <th style="width: 5px; text-align: center;">On Leave</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($attendances) > 0)
                                <ul class="products-list product-list-in-box">
                                    @foreach ($attendances as $key => $attendance)
                                        <tr>
                                            <td nowrap>
                                                <div class="product-img">
                                                    <img src="{{ (!empty($attendance->user->profile_pic)) ? asset('storage/avatars/'.$attendance->user->profile_pic)  :
                                                            (!empty($attendance->user->gender) && ($attendance->user->gender === 2) ? $f_silhouette : $m_silhouette)}} "
                                                         width="50" height="50" alt="Profile Picture">
                                                </div>
                                                <div class="modal fade" id="enlargeImageModal" tabindex="-1"
                                                     role="dialog" align="center"
                                                     aria-labelledby="enlargeImageModal" aria-hidden="true">
                                                    <!--  <div class="modal-dialog modal" role="document"> -->
                                                    <div class="modal-dialog modal-sm">
                                                        <div class="modal-body" align="center">
                                                            <img src="" class="enlargeImageModalSource"
                                                                 style="width: 200%;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
											<td style="text-align:center;">{{ !empty($attendance->user->division->name) ? $attendance->user->division->name : '' }}</td>
											<td style="text-align:center;">{{ !empty($attendance->user->department->name) ? $attendance->user->department->name : '' }}</td>
                                            <td style="text-align:center;">
                                                <span data-toggle="tooltip" title="" class="badge bg-grey"
                                                      data-original-title="">{{ (!empty($attendance->user->employee_number)) ? $attendance->user->employee_number : '' }}</span>
                                            </td>
                                            <td style="text-align:center;">
                                                {{ (!empty($attendance->user->first_name) && !empty($attendance->user->surname)) ?  $attendance->user->first_name . ' ' . $attendance->user->surname : ''}}
                                            </td>
											<td style="text-align:center;">{{ (!empty($attendance->created_at)) ? $attendance->created_at : '' }}</td>
                                            <td style="text-align:center;"> {{ (!empty($attendance->clokin_time)) ? $attendance->clokin_time : '' }}</td>
                                            <td style="text-align:center;"> {{ (!empty($attendance->clockin_locations)) ? $attendance->clockin_locations : '' }}</td>
											<td style="text-align:center;"> {{ (!empty($attendance->clockout_time)) ? $attendance->clockout_time : '' }}</td>
                                            <td style="text-align:center;"> {{ (!empty($attendance->clockout_locations)) ? $attendance->clockout_locations : '' }}</td>
                                            <td style="text-align:center;">{{ (!empty($attendance->hours_worked)) ? $attendance->hours_worked : '' }}</td>
                                            <td style="text-align:center;">{{ (!empty($attendance->late_arrival)) ? 'Yes' : '' }}</td>
                                            <td style="text-align:center;">{{ (!empty($attendance->early_clockout)) ? 'Yes' : '' }}</td>
                                            <td style="text-align:center;">{{ (!empty($attendance->absent)) ? 'Yes' : '' }}</td>
                                            <td style="text-align:center;">{{ (!empty($attendance->onleave)) ? 'Yes' : '' }}</td>
                                        </tr>
                                @endforeach
                            @endif
                            </tbody>
							<tr>
                                <th style="width: 10px; text-align: center;"></th>
								@foreach($levels as $level)
									<th style="width: 10px; text-align: center;">{{ $level->name }}</th>
								@endforeach
                                <th style="width: 5px; text-align: center;">Employee Number</th>
                                <th style="width: 5px; text-align: center;">Name</th>
                                <th style="width: 5px; text-align: center;">Date</th>
                                <th style="width: 5px; text-align: center;">Clokin Time</th>
                                <th style="width: 5px; text-align: center;">Location</th>
                                <th style="width: 5px; text-align: center;">Clockout Time</th>
                                <th style="width: 5px; text-align: center;">Location</th>
                                <th style="width: 5px; text-align: center;">Hours Worked</th>
                                <th style="width: 5px; text-align: center;">Late Arrival</th>
                                <th style="width: 5px; text-align: center;">Early Clockout</th>
                                <th style="width: 5px; text-align: center;">Absent</th>
                                <th style="width: 5px; text-align: center;">On Leave</th>
                            </tr>
                        </table>
                        <!-- /.box-body -->
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop
@section('page_script')
<!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('custom_components/js/modal_ajax_submit.js') }}"></script>
    <script src="{{ asset('custom_components/js/deleteAlert.js') }}"></script>
	<!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
	<script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js"
			type="text/javascript"></script>
	<!-- the main fileinput plugin file -->
	<script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
	<!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
	<script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
	<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <script src="{{ asset('bower_components/bootstrap_fileinput/js/fileinput.min.js') }}"></script>

    <script src="{{ asset('plugins/axios/dist/axios.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
    <!-- Bootstrap date picker -->
    <script src="/bower_components/AdminLTE/plugins/daterangepicker/moment.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- End Bootstrap File input -->
	<!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
    <script type="text/javascript">

        function sendStatus() {

            let select = document.getElementById("status_id");
            console.log(select)

        }

        function postData(id, data) {
            if (data === 'actdeac') location.href = "{{route('employee.activate', '')}}" + "/" + id;
        }

        $('.popup-thumbnail').click(function () {
            $('.modal-body').empty();
            $($(this).parents('div').html()).appendTo('.modal-body');
            $('#modal').modal({show: true});
        });

        //TODO WILL CREATE A SIGLE GLOBAL FILE

        $(function () {
			//Initialize iCheck/iRadio Elements
			$('input').iCheck({
				checkboxClass: 'icheckbox_square-blue',
				radioClass: 'iradio_square-blue',
				increaseArea: '10%' // optional
			});
			//Initialize Select2 Elements
            $(".select2").select2();
            $('table.asset').DataTable({

                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: true,
                dom: 'Bfrtip',
				buttons: [
						{
							extend: 'copy',
							text: 'Copy to Clipboard'
						},
						{
							extend: 'csv',
							text: 'Export to CSV',
							filename: 'Time and Attendance Report'
						},
						{
							extend: 'excel',
							text: 'Export to Excel',
							filename: 'Time and Attendance Report'
						},
						{
							extend: 'pdf',
							text: 'Export to PDF',
							filename: 'Time and Attendance Report'
						},
						{
							extend: 'print',
							text: 'Print Table'
						}
				]

            });
			//Date Range picker
            $('.daterangepicker').daterangepicker({
				locale:{ format:'DD/MM/YYYY' },
                endDate: '-1d',
                autoclose: true
            });
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

            $(function () {
                $('img').on('click', function () {
                    $('.enlargeImageModalSource').attr('src', $(this).attr('src'));
                    $('#enlargeImageModal').modal('show');
                });
            });
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
@stop