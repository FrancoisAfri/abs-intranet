@extends('layouts.main_layout')
@section('page_dependencies')
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<!-- iCheck -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
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
                    <h3 class="box-title">Job Cards </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>

                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 5px; text-align: center;"></th>
                                    <th>Job Card #</th>
                                    <th>Vehicle Name</th>
                                    <th>Registration</th>
                                    <th>Job Card Date </th>
                                    <th>Completion Date</th>
									<th style="width: 5px; text-align: center;">Instruction</th>
                                    <th>Mechanic</th>
                                    <th>Service Type</th>
                                    <th>Supplier</th>
                                    <th>Status</th>
                            </tr>
                            @if (count($jobcardmaintanance) > 0)
                              @foreach ($jobcardmaintanance as $jobcard)
                               <tr id="configuration-list">
                                <td>
                               <a href="{{ '/Product/price/' . $jobcard->id }}" id="edit_compan" class="btn btn-warning  btn-xs"><i class="fa fa-money"></i> View</a></td>
                                </td> 
								<td>{{ !empty($jobcard->jobcard_number) ? $jobcard->jobcard_number : '' }}</td>
                                <td>{{ (!empty( $jobcard->fleet_number . ' ' .  $jobcard->vehicle_registration . ' ' . $jobcard->vehicle_make . ' ' . $jobcard->vehicle_model)) 
                                    ?  $jobcard->fleet_number . ' ' .  $jobcard->vehicle_registration . ' ' . $jobcard->vehicle_make . ' ' . $jobcard->vehicle_model : ''}} </td>
                                <td>{{ (!empty( $jobcard->vehicle_registration)) ?  $jobcard->vehicle_registration : ''}} </td>
                                <td>{{ !empty($jobcard->card_date) ? date(' d M Y', $jobcard->card_date) : '' }}</td>
                                <td></td>
                                <td>{{ !empty($jobcard->instruction) ? $jobcard->instruction : '' }}</td>
                                <td>{{ !empty($jobcard->firstname . '' . $jobcard->surname) ? $jobcard->firstname . '' . $jobcard->surname : '' }}</td>
                                <td>{{ !empty($jobcard->servicetype) ? $jobcard->servicetype : '' }}</td>
                                <td>{{ !empty($jobcard->Supplier) ? $jobcard->Supplier : '' }}</td>
                                <td>{{ !empty($jobcard->status) ? $Status[$jobcard->status] : '' }}</td>									
                               </tr>
                                   @endforeach
                               @else
                               <tr id="categories-list">
                        <td colspan="12">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            No Record to display, please start by adding a new Record....
                        </div>
                        </td>
                        </tr>
                           @endif
                            </table>
                    <div class="box-footer">
                         <button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                     <button type="button" id="safe_module" class="btn btn-warning pull-right" data-toggle="modal" data-target="#add-jobcard-modal">Add new Job card</button>
                    </div>
             </div>
        </div>
   <!-- Include add new prime rate modal -->
        @include('job_cards.partials.add_jobcard_modal')
        @include('job_cards.partials.edit_servicetype_modal')
          <!-- Include delete warning Modal form-->
		  
		   <!-- Confirmation Modal -->
        @if(Session('success_edit'))
            @include('job_cards.partials.success_action', ['modal_title' => "User not permitted!", 'modal_content' => session('success_edit')])
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
                    if (data == 'actdeac') location.href = "/vehicle_management/policy_act/" + id;

                }

                $('#back_button').click(function () {
                    location.href = '/vehicle_management/viewdetails';
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

                    $('#card_date').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });


                     $('#schedule_date').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });
                     
                     // 
                      $('#booking_date').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });


                     $('#dateserviced').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });


                });


                //Post perk form to server using ajax (add)
                $('#add_jobcardtypes').on('click', function () {
                    var strUrl = '/jobcards/addjobcard';
                    var formName = 'add-jobcard-form';
                    var modalID = 'add-jobcard-modal';
                    var submitBtnID = 'add_jobcardtypes';
                    var redirectUrl = '/jobcards/mycards';
                    var successMsgTitle = 'New Record Added!';
                    var successMsg = 'The Record  has been updated successfully.';
                    modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                });


                var serviceID;
                $('#edit-servicedetails-modal').on('show.bs.modal', function (e) {
                    var btnEdit = $(e.relatedTarget);
                    serviceID = btnEdit.data('id');
                    var invoice_number = btnEdit.data('invoice_number');
                    var total_cost = btnEdit.data('total_cost');
                    var dateserviced = btnEdit.data('dateserviced');
                    var garage = btnEdit.data('garage');
                    var nxtservice_date = btnEdit.data('nxtservicedate');
                    var nxt_service_km = btnEdit.data('nxt_service_km');
                    var description = btnEdit.data('description');
                    var documents = btnEdit.data('documents');
                    var documents1 = btnEdit.data('documents1');
                    var valueID = btnEdit.data('valueID');
                    var modal = $(this);
                    modal.find('#invoice_number').val(invoice_number);
                    modal.find('#total_cost').val(total_cost);
                    modal.find('#dateserviced').val(dateserviced);
                    modal.find('#garage').val(garage);
                    modal.find('#nxtservice_date').val(nxtservice_date);
                    modal.find('#nxt_service_km').val(nxt_service_km);
                    modal.find('#description').val(description);
                    modal.find('#documents').val(documents);
                    modal.find('#documents1').val(documents1);
                    modal.find('#valueID').val(valueID);
                });

                 $('#edit_servicedetails').on('click', function () {
                    var strUrl = '/vehicle_management/edit_servicedetails/'+ serviceID ;
                    var formName = 'edit-servicedetails-form';
                    var modalID = 'edit-servicedetails-modal';
                    var submitBtnID = 'edit_servicedetails';
                    var redirectUrl = '/vehicle_management/service_details';
                    var successMsgTitle = 'New Record Added!';
                    var successMsg = 'The Record  has been updated successfully.';
                     var Method = 'PATCH'
                    modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg,Method);
                });


            </script>
@endsection
