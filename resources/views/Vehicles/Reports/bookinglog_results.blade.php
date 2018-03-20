@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
    <!-- DataTables -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <form class="form-horizontal" method="POST" action="/System/policy/update_status">
                    {{ csrf_field() }}

                    <div class="box-header with-border">
                        <h3 class="box-title">Vehicle Booking Deatails Report - for {{$vehicledetail->vehicle_make . ' ' . $vehicledetail->vehicle_model }} </h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-remove"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
                                </button>
                                <h4><i class="icon fa fa-ban"></i> Invalid Input Data!</h4>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <table id="emp-list-table" class="table table-bordered table-striped table-hover">
                            <thead>
                            <tr>
                                <th>Date Collected</th>
                                <th>Date Returned</th>
                                <th>Approved By</th>
                                <th>Driver</th>
                                <th>Purpose</th>
                                <th>Destination</th>
                                <th>Starting Km</th>
                                <th>Ending Km</th>
                                <th>Type</th>
                                <th>Total Km Travelled</th>


                            </tr>
                            </thead>
                            <tbody>
                            @foreach($vehicle_booking as $policy)
                                <tr>
                                    <td>{{ !empty($policy->collect_timestamp) ? date(' d M Y', $policy->collect_timestamp) : ''}}</td>
                                    <td>{{ !empty($policy->return_timestamp) ? date(' d M Y', $policy->return_timestamp) : ''}}</td>
                                    <td></td>purpose

                                    <td>{{ !empty($policy->firstname . ' ' . $policy->surname ) ? $policy->firstname . ' ' . $policy->surname : ''}}</td>
                                    <td>{{ !empty($policy->purpose) ? $policy->purpose : ''}}</td>
                                    <td>{{ !empty($policy->destination) ? $policy->destination : ''}}</td>
                                    <td>{{ !empty($policy->odometer_reading) ? $policy->odometer_reading : ''}}</td>
                                    <td>{{ !empty($policy->odometer_reading) ? $policy->odometer_reading : ''}}</td>
                                    <td>{{ !empty($policy->end_mileage_id - $policy->start_mileage_id) ? $policy->end_mileage_id - $policy->start_mileage_id  : ''}}</td>
                                    @endforeach
                                </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                
                                <th> Date Collected</th>
                                <th>Date Returned</th>
                                <th>Approved By</th>
                                <th>Driver</th>
                                <th>Purpose</th>
                                <th>Destination</th>
                                <th>Starting Km</th>
                                <th>Ending Km Km</th>
                                <th>Total Km Travelled</th>
                            </tr>
                            </tfoot>
							<input type="hidden" name="vehicle_id" size="10" value="$iVehicleID">
                            <class
                            ="caption">
							 <td style="text-align: right"></td>
							  <td style="text-align: right"></td>
                            <td colspan="6" style="text-align:right">Total</td>
                            <td style="text-align: right" nowrap></td>
                        </table>
                    </div>
                    <!-- /.box-body -->

                </form>
            </div>
        </div>

    </div>
@endsection

@section('page_script')
    <!-- Start Bootstrap File input -->
    <!-- canvas-to-blob.min.js is only needed if you wish to resize images before upload. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/canvas-to-blob.min.js"
            type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
    <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
    <!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
    <script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
    <!-- optionally if you need translation for your language then include locale file as mentioned below
    <script src="/bower_components/bootstrap_fileinput/js/locales/<lang>.js"></script>-->
    <!-- End Bootstrap File input -->

    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <!-- DataTables -->
    <script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();

            //Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });

            //Tooltip
            $('[data-toggle="tooltip"]').tooltip();

            //Initialize the data table
            $('#emp-list-table').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": false,
                "autoWidth": true
            });

            //Cancel button
            $('#cancel').click(function () {
                location.href = '/users/users-access';
            });

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
            @if(Session('changes_saved'))
            $('#success-action-modal').modal('show');
            @endif
        });
    </script>
@endsection