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
                    <h3 class="box-title">Fuel Tank </h3>
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
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 5px; text-align: center;"></th>
                                <th>Transaction Date</th>
                                <th>Transaction Type</th>
                                <th>Fleet No.</th>
                                <th>Reg. No.</th>
                                <th>Supplier/Employee</th>
                                <th>Reading before filling</th>
                                <th>Reading after filling</th>
                                <th> Litres</th>
                                <th>Rate Per Litre</th>
                                <th>Cost</th>

                            </tr>

                            @if (count($Approvals) > 0)
                                @foreach ($Approvals as $filling)
                                    <tr style="text-align:center">

                                        <td></td>
                                        <td>{{ (!empty( $filling->date)) ?  $filling->date : ''}} </td>
                                        <td>{{ (!empty( $filling->transaction_type)) ?  $filling->transaction_type : ''}} </td>
                                        <td>{{ (!empty( $filling->odometer_reading)) ?  $filling->odometer_reading : ''}} </td>
                                        <td>{{ (!empty( $filling->Department)) ?  $filling->Department : ''}} </td>
                                        <td>{{ (!empty( $filling->company)) ?  $filling->company : ''}} </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>


                                    </tr>
                                @endforeach
                            @else
                                <tr id="categories-list">
                                    <td colspan="15">
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                                &times;
                                            </button>
                                            No Fuel Tank to display, please start by adding a new Fuel Tank..
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
                function postData(id, data) {
                    if (data == 'actdeac') location.href = "/vehice/station_act/" + id;

                }

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




                });


            </script>
@endsection
