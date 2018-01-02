@extends('layouts.main_layout')
@section('page_dependencies')
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
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
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                    <h3 class="box-title"> Fleet Cards Report </h3>
                </div>
                <div class="box-body">
                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div style="overflow-X:auto;">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th style="width: 5px; text-align: center;"></th>
                                        <th>Fleet Card Type</th>
                                        <th>Vehicle Fleet Number</th>
                                        <th>Holder</th>
                                        <th>Card Number</th>
                                        <th>CVS Number</th>
                                        <th>Issued By</th>
                                        <th>Issued Date</th>
                                        <th>Expiry Date</th>
                                        <th>Active</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (count($fleetcard) > 0)
                                        @foreach ($fleetcard as $fleet)
                                            <tr id="categories-list">
                                                <td nowrap>
                                                    <button vehice="button" id="edit_compan"
                                                            class="btn btn-warning  btn-xs" data-toggle="modal"
                                                            data-target="#edit-vehiclefleet-modal"
                                                            data-id="{{ $fleet->id }}"
                                                            data-fleet_number="{{ $fleet->fleet_number }}"
                                                            data-cvs_number="{{ $fleet->cvs_number }}"
                                                            data-holder_id="{{ $fleet->holder_id }}"
                                                            data-company_id="{{ $fleet->company_id }}"
                                                            data-card_number="{{$fleet->card_number}}"
                                                            data-card_type_id="{{ $fleet->card_type_id }}"
                                                            data-issued_date="{{ date("d/m/Y", $fleet->issued_date)}}"
                                                            data-expiry_date="{{date("d/m/Y",  $fleet->expiry_date)}}"
                                                            data-status="{{ $fleet->status }}"
                                                    ><i
                                                                class="fa fa-pencil-square-o"></i> Edit
                                                    </button>
                                                </td>
                                                <td>{{ !empty($fleet->type_name ) ? $fleet->type_name : '' }}</td>
                                                <td>{{ !empty($fleet->fleet_number ) ? $fleet->fleet_number : '' }}</td>
                                                <td>{{ !empty($fleet->first_name . '' . $fleet->surname ) ? $fleet->first_name . '' . $fleet->surname : ''}}</td>
                                                <td>{{ !empty($fleet->card_number) ? $fleet->card_number : ''}}</td>
                                                <td>{{ !empty($fleet->cvs_number) ? $fleet->cvs_number : ''}}</td>
                                                <td>{{ !empty($fleet->Vehicle_Owner) ? $fleet->Vehicle_Owner : ''}}</td>
                                                <td>{{ !empty($fleet->issued_date ) ? date("d/m/Y", $fleet->issued_date) : ''}}</td>
                                                <td>{{ !empty($fleet->expiry_date ) ? date("d/m/Y",  $fleet->expiry_date) : ''}}</td>
                                                <td>{{ !empty($fleet->status) ? $status[$fleet->status] : ''}}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th style="width: 5px; text-align: center;"></th>
										<th>Fleet Card Type</th>
										<th>Vehicle Fleet Number</th>
                                        <th>Holder</th>
                                        <th>Card Number</th>
                                        <th>CVS Number</th>
                                        <th>Issued By</th>
                                        <th>Issued Date</th>
                                        <th>Expiry Date</th>
                                        <th>Active</th>
                                    </tr>
                                    </tfoot>
                                </table>
                                <div class="box-footer">
                                    <button type="button" id="cancel" class="btn btn-default pull-left"><i
                                                class="fa fa-arrow-left"></i> Back
                                        <button type="button" id="cancel" class="btn btn-primary btn-xs pull-right">
                                            import to PDF
                                        </button>

                                        <button type="button" id="cancel" class="btn btn-primary btn-xs pull-right">
                                            import to EXCEL
                                        </button>
                                </div>
                            </div>
                            @include ('Vehicles.Fleet_cards.edit_vehiclefleetcard_modal')
                        </div>
                    </div>
                @endsection

                @section('page_script')
                    <!-- DataTables -->
                        <script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
                        <script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
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
                        <!-- End Bootstrap File input -->
                        <script>
                            //Cancel button click event
                            document.getElementById("cancel").onclick = function () {
                                location.href = "/vehicle_management/fleet_cards";
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

                            $(function () {
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

                            //Initialize iCheck/iRadio Elements
                            $('input').iCheck({
                                checkboxClass: 'icheckbox_square-blue',
                                radioClass: 'iradio_square-blue',
                                increaseArea: '10%' // optional
                            });

                            $(document).ready(function () {

                                $('input[name="issued_date"]').datepicker({
                                    format: 'dd/mm/yyyy',
                                    autoclose: true,
                                    todayHighlight: true
                                });

                                $('input[name="expiry_date"]').datepicker({
                                    format: 'dd/mm/yyyy',
                                    autoclose: true,
                                    todayHighlight: true
                                });
                            });

                            var fleetID;
                            $('#edit-vehiclefleet-modal').on('show.bs.modal', function (e) {
                                var btnEdit = $(e.relatedTarget);
                                if (parseInt(btnEdit.data('id')) > 0) {
                                    fleetID = btnEdit.data('id');
                                }
                                var fleet_number = btnEdit.data('fleet_number');
                                var cardTypeId = btnEdit.data('card_type_id');
                                var company_id = btnEdit.data('company_id');
                                var holder_id = btnEdit.data('holder_id');
                                var card_number = btnEdit.data('card_number');
                                var cvs_number = btnEdit.data('cvs_number');
                                var issuedDate = btnEdit.data('issued_date');
                                var expiryDate = btnEdit.data('expiry_date');
                                var Status = btnEdit.data('status');
                                var modal = $(this);
                                modal.find('#fleet_number').val(fleet_number);
								modal.find('select#card_type_id').val(cardTypeId).trigger("change");
                                modal.find('#company_id').val(company_id);
                                modal.find('#holder_id').val(holder_id);
                                modal.find('#card_number').val(card_number);
                                modal.find('#cvs_number').val(cvs_number);
                                modal.find('#issued_date').val(issuedDate);
                                modal.find('#expiry_date').val(expiryDate);
                                modal.find('#status').val(Status);
                            });

                            $('#edit_vehiclefleetcard').on('click', function () {
                                var strUrl = '/vehicle_management/edit_vehiclefleetcard/' + fleetID;
                                var formName = 'edit-vehiclefleet-form';
                                var modalID = 'edit-vehiclefleet-modal';
                                var submitBtnID = 'edit_vehiclefleetcard';
                                var redirectUrl = '/vehicle_management/fleet_card_search';
                                var successMsgTitle = 'Record has been updated!';
                                var successMsg = 'The Record has been updated successfully.';
                                var Method = 'PATCH';
                                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                            });

                        </script>
@endsection