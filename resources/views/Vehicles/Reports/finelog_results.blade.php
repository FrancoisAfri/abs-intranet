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
                        <h3 class="box-title">Vehicle Fines Report</h3>
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
                                <th style="width: 10px"></th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Reference</th>
                                <th>Location</th>
                                <th>Type</th>
                                <th>Driver</th>
                                <th>Amount</th>
                                <th>Amount Paid</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($vehiclefines as $fine)
                                <tr>
                                    <td>{{ (!empty($fine->VehicleMake) ) ? $fine->VehicleMake." ".$fine->VehicleModel." ".$fine->vehicletypes." ".$fine->vehicle_registration : ''}}</td>
                                    <td>{{ !empty($fine->date_of_fine) ? date(' d M Y', $fine->date_of_fine) : '' }}</td>
                                    <td>{{ !empty($fine->time_of_fine) ? date(' h:m:z', $fine->time_of_fine) : '' }}</td>
                                    <td>{{ !empty($fine->fine_ref) ? $fine->fine_ref : '' }}</td>
                                    <td>{{ !empty($fine->location) ?  $fine->location : '' }}</td>
                                    <td>{{ !empty($fine->fine_type) ?  $fineType[$fine->fine_type] : '' }}</td>
                                    <td>{{ !empty($fine->firstname . ' ' . $fine->surname ) ?  $fine->firstname . ' ' . $fine->surname : '' }}</td>
                                    <td>{{ !empty($fine->amount  ) ?  'R '.number_format($fine->amount, 2) :'' }}</td>
                                    <td>{{ !empty($fine->amount_paid  ) ?  'R '.number_format($fine->amount_paid, 2) :'' }}</td>
                                    <td>{{ !empty($fine->fine_status  ) ?  $status[$fine->fine_status] :'' }}</td>
                                    @endforeach

                                </tr>

                            </tbody>
                            <tfoot>
                            <tr>
                                <th style="width: 10px"></th>
                                <th> Date</th>
                                <th>Time</th>
                                <th>Reference</th>
                                <th>Location</th>
                                <th>Type</th>
                                <th>Driver</th>
                                <th>Amount</th>
                                <th>Amount Paid</th>
                                <th>Status</th>
                            </tr>
                            </tfoot>
                            <input type="hidden" name="vehicle_id" size="10" value="$iVehicleID">
                            <class
                            ="caption">
                            <td style="text-align: right" nowrap></td>
                            <td colspan="6" style="text-align:right">Total</td>
                            <td style="text-align: right">{{number_format($total, 2) }}</td>
                            <td style="text-align: right">{{number_format($totalamount_paid, 2) }}</td>
                            <td style="text-align: right" nowrap></td>
                        </table>
                            <div class="box-footer">

                                <div class="row no-print">
                                    <button type="button" id="cancel" class="btn btn-default pull-left"><i
                                                class="fa fa-arrow-left"></i> Back to Search Page
                                    </button>
                                    <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print report</button>
                                </div>
                            </div>
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
            //Cancel button
            $('#cancel').click(function () {
                location.href = "/vehicle_management/vehicle_reports";
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