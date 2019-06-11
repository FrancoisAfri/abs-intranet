@extends('layouts.main_layout')
@section('page_dependencies')

    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">

@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12 col-md-offset-0">
            <!-- Horizontal Form -->
            <!-- <form class="form-horizontal" method="get" action="/leave/approval"> -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                    <h3 class="box-title">Internal Vehicle Management </h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <div class="box-body">
                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div style="overflow-X:auto;">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th style="width: 5px; text-align: center;">Image</th>
                                        <th>Fleet Number</th>
                                        <th>Vehicle Model/Year</th>
                                        <th>Vehicle Registration</th>
                                        <th>VIN Numberr</th>
                                        <th>Engine Number</th>
                                        <th>Odometer/Hours</th>
                                        <th>Company</th>
                                        <th>Department</th>
                                        <th>Rejection Reason</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (count($vehiclemaintenance) > 0)
                                        @foreach ($vehiclemaintenance as $card)
                                            <tr id="categories-list">
                                                <td>
                                                    
                                                    <a href="{{ '/vehicle_management/viewdetails/' . $card->id }}"
                                                       id="edit_compan" class="btn btn-default pull-left"
                                                       data-id="{{ $card->id }}">View</a>

                                                    <div id="my_div" class="hidden">
                                                        <a href="{{ '/vehicle_management/viewImage/' . $card->id }}"
                                                        >image</a>
                                                    </div>
                                                    <div id="my_div" class="hidden">
                                                        <a href="{{ '/vehicle_management/keys/' . $card->id }}"
                                                        >Keys </a>
                                                    </div>
                                                    <div id="my_div" class="hidden">
                                                        <a href="{{ '/vehicle_management/permits_licences/' . $card->id }}">Permits/Licences </a>
                                                    </div>
                                                    <div id="my_div" class="hidden">
                                                        <a href="{{ '/vehicle_management/document/' . $card->id }}"
                                                        >document </a>
                                                    </div>
                                                    <div id="my_div" class="hidden">
                                                        <a href="{{ '/vehicle_management/contracts/' . $card->id }}"
                                                        ">Contracts </a>
                                                    </div>
                                                    <div id="my_div" class="hidden">
                                                        <a href="{{ '/vehicle_management/notes /' . $card->id }}"
                                                        >Notes </a>
                                                    </div>
                                                    <div id="my_div" class="hidden">
                                                        <a href="{{ '/vehicle_management/reminders  /' . $card->id }}"
                                                        >Reminders </a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="product-img">
                                                        <img src="{{ (!empty($card->image)) ? Storage::disk('local')->url("Vehicle/images/$card->image") : 'http://placehold.it/60x50' }}"
                                                             alt="Product Image" width="50" height="50">
                                                    </div>
                                                    <div class="modal fade" id="enlargeImageModal" tabindex="-1"
                                                         role="dialog"
                                                         aria-labelledby="enlargeImageModal" aria-hidden="true">
                                                        <!--  <div class="modal-dialog modal" role="document"> -->
                                                        <div class="modal-dialog modal-sm">
                                                            <div class="modal-body">
                                                                <img src="" class="enlargeImageModalSource"
                                                                     style="width: 200%;">

                                                            </div>
                                                        </div>
                                                    </div>
                            </div>
                            </td>
                            <td>{{ !empty($card->fleet_number) ? $card->fleet_number : ''}}</td>
                            <td>{{ !empty($card->vehicle_model . ' ' . $card->year ) ? $card->vehicle_model  . ' ' . $card->year: ''}}</td>
                            <td>{{ !empty($card->vehicle_registration) ? $card->vehicle_registration : ''}}</td>
                            <td>{{ !empty($card->chassis_number) ? $card->chassis_number : ''}}</td>
                            <td>{{ !empty($card->engine_number) ? $card->engine_number : ''}}</td>
                                <td>{{ !empty($card->hours_reading) ? $card->hours_reading.'Hrs' : ''}}</br>
                            {{ !empty($card->odometer_reading) ? $card->odometer_reading.'Kms' : ''}}</td>
                            <td>{{ !empty($card->company) ? $card->company : ''}}</td>
                            <td>{{ !empty($card->Department) ? $card->Department : ''}}</td>
                            <td>{{ !empty($card->reject_reason)  && $card->status == 3 ? $card->reject_reason : ''}}</td>
                            </tr>
                            @endforeach
                            @endif
                            </tbody>
                            <tfoot>
                            <tr>
                                <td></td>
                                <th style="width: 5px; text-align: center;">Image</th>
                                <th>Vehicle Model/Year</th>
                                <th>Fleet Number</th>
                                <th>Vehicle Registration</th>
                                <th>VIN Numberr</th>
                                <th>Engine Number</th>
                                <th>Odometer/Hours</th>
                                <th>Company</th>
                                <th>Department</th>
								<th>Rejection Reason</th>
                            </tr>
                            </tfoot>
                            </table>

                            <!-- /.box-body -->
                            <div class="box-footer">
                                <button type="button" id="cancel" class="btn btn-default pull-left"><i
                                            class="fa fa-arrow-left"></i> Back
                                </button>
                            </div>
                            @include('Vehicles.partials.upload_newImage_modal')
                        </div>
                    </div>
                    <!-- End new User Form-->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_script')
<!-- DataTables -->
	<!-- DataTables -->
	<script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
	<!-- End Bootstrap File input -->
	<script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js"
			type="text/javascript"></script>
	<!-- the main fileinput plugin file -->
	<script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
	<!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
	<script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
	<script src="/custom_components/js/modal_ajax_submit.js"></script>
	<script>


		//Cancel button click event
		document.getElementById("cancel").onclick = function () {
			location.href = "/vehicle_management/manage_fleet";
		};
		$(function () {
			$('#example2').DataTable({
				"paging": true,
				"lengthChange": true,
				"searching": true,
				"ordering": true,
				"info": true,
				"autoWidth": true
			});
		});

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
		//
		$(function () {
			$('img').on('click', function () {
				$('.enlargeImageModalSource').attr('src', $(this).attr('src'));
				$('#enlargeImageModal').modal('show');
			});
		});
	</script>
@endsection