@extends('layouts.main_layout')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h4 class="box-title">Job Card Management</h4>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>

                <div align="center" class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title"></h3>
                    </div>
                    <div class="box-body">
                        <a href="/Jobcard_management/addJob_card" class="btn btn-app">
                            <i class="fa fa-tasks"></i> Add Job Card
                        </a>

                        {{--<a href="/vehicle_management/fleet_card" class="btn btn-app">--}}
                        {{--<i class="fa fa-car" ></i> Fleet Card Types --}}
                        {{--</a>--}}

                        {{--<a href="/vehicle_management/fillingstaion" class="btn btn-app">--}}
                        {{--<i class="fa fa-bitbucket"></i> Fleet Filling Station damage categories --}}
                        {{--</a>--}}

                        {{--<a href="/vehicle_management/Document_type"  class="btn btn-app">--}}
                        {{--<i class="fa fa-file-o"></i>  Document Type --}}
                        {{--</a>--}}

                        {{--<a href="/vehicle_management/Permit"  class="btn btn-app">--}}
                        {{--<i class="fa fa-id-card-o"></i> Fleet License Type/Permit --}}
                        {{--</a>--}}

                        {{--<a href="/vehicle_management/Incidents_type"  class="btn btn-app">--}}
                        {{--<i class="fa fa-medkit"></i>  Incidents Type  --}}
                        {{--</a>--}}
                        {{--<a href="/vehicle_management/group_admin"  class="btn btn-app">--}}
                        {{--<i class="fa fa-comments"></i>  Message group admin  --}}
                        {{--</a>--}}
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div align="center">
                        <div class="box-header with-border">
                            <h3 class="box-title">Vehicle Configuration</h3>
                        </div>
                        <div class="box-body">

                            <a href="/vehicle_management/vehicle_configuration" class="btn btn-app">
                                <i class="fa fa-cog"></i> Settings
                            </a>

                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>
        @endsection
        @section('page_script')
            <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>

            <script src="/custom_components/js/modal_ajax_submit.js"></script>


            <script>

                $('#back_button').click(function () {
                    location.href = '/product/Packages';
                });
                $(function () {
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


                });
            </script>
@endsection