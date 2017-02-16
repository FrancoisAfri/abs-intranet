@extends('layouts.main_layout')
@section('page_dependencies')

    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <!--<form class="form-horizontal" method="get" action="/education/search">-->
                <div class="box box-success">
                    <div class="box-header with-border">
                        <i class="fa fa-user pull-right"></i>
                        <h3 class="box-title">Registered Clients</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->

                    <div class="box-body">
                        <!--<div class="box">-->
                            <!-- /.box-header -->
                            <!--<div class="box-body">-->
                                <table id="registered-people" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>ID Number</th>
                                        <th>Registration Date</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (count($registrations) > 0)
                                        @foreach($registrations as $registration)
                                            <tr>
                                                <td>{{ !empty($registration->client->full_name) ? $registration->client->full_name : '' }}</td>
                                                <td>{{ !empty($registration->client->id_number) ? $registration->client->id_number : '' }}</td>
                                                <td>{{ !empty($registration->created_at) ? $registration->created_at->format('d-m-Y') : '' }}</td>
                                                <td style="text-align: center;"><button type="button" class="btn btn-xs btn-primary btn-flat" data-registration="{{ $registration }}" data-toggle="modal" data-target="#capture-results-modal">Capture Results</button></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <!--<tfoot>
                                    <tr>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Budget Expenditure</th>
                                        <th>Budget Income</th>
                                    </tr>
                                    </tfoot>-->
                                </table>
                            <!--</div>-->
                            <!-- /.box-body -->
                        <!--</div>-->
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button id="btn_back" class="btn btn-default pull-left"><i class="fa fa-arrow-left"></i> Back</button>
                    </div>
                    <!-- /.box-footer -->
                </div>
            <!--</form>-->
            <!-- /.box -->
        </div>
        <!-- End new User Form-->
        <!-- Include results modal forms -->
        @if(count($registrations) > 0)
            @include('results.partials.capture_results_modal', ['modal_title' => 'Capture Results'])
        @endif
    </div>
    @endsection

    @section('page_script')
            <!-- DataTables -->
    <script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- End Bootstrap File input -->

    <script type="text/javascript">

        $(function () {
            $('#registered-people').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true
            });

            $('#btn_back').click(function () {
                location.href = '/education/loadclients';
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
            $(window).on('resize', function() {
                $('.modal:visible').each(reposition);
            });

            //Load results(fields) on modal show
            $('#capture-results-modal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget) // Button that triggered the modal
                var registration = button.data('registration') // Extract info from data-* attributes
                var subjects = registration.subjects;
                var i;
                for (i = 0; i < subjects.length; ++i) {
                    console.log('###Subjects Data: ' + JSON.stringify(subjects));
                }
                //console.log('Reg Data: ' + JSON.stringify(registration));
                var modal = $(this)
                //modal.find('.modal-title').text('New message to ' + recipient)
                //modal.find('.modal-body input').val(recipient)
            })
        });

    </script>
@endsection