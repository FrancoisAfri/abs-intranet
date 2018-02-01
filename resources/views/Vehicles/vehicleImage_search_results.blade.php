@extends('layouts.main_layout')
@section('page_dependencies')
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                    <h3 class="box-title"> Vehicle Image Result(s) </h3>
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
                                        <th align="center">Description</th>
                                        <th>Date Uploaded</th>
                                        {{--<th> Registration</th>--}}
                                        <th>Uploaded By</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (count($vehicleImages) > 0)
                                        @foreach ($vehicleImages as $document)
                                            <tr id="categories-list">
                                                <td nowrap >
                                                    <div class="product-img">
                                                        <img src="{{ (!empty($document->image)) ? Storage::disk('local')->url("Vehicle/images/$document->image") : 'http://placehold.it/60x50' }}"
                                                             alt="Product Image" width="100" height="75">
                                                    </div>

                                                    <div class="modal fade" id="enlargeImageModal" tabindex="-1"
                                                         role="dialog" align="center"
                                                         aria-labelledby="enlargeImageModal" aria-hidden="true">
                                                        <!--  <div class="modal-dialog modal" role="document"> -->
                                                        <div class="modal-dialog modal-sm" >
                                                            {{--<div class="modal-content">--}}
                                                                {{--<div class="modal-header">--}}
                                                                    {{--<button type="button" class="close"--}}
                                                                            {{--data-dismiss="modal"--}}
                                                                            {{--aria-label="Close"><span aria-hidden="true">x</span>--}}
                                                                    {{--</button>--}}
                                                                {{--</div>--}}
                                                                <div class="modal-body" align="center">
                                                                    <img src="" class="enlargeImageModalSource"
                                                                         style="width: 200%;">

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td align="center">{{ !empty($document->description) ? $document->description : ''}}</td>
                                                <td>{{ !empty($document->upload_date) ? date(' d M Y', $document->upload_date) : '' }}</td>

                                                <td>{{ !empty($document->first_name . ' ' . $document->surname ) ? $document->first_name . ' ' . $document->surname : ''}}</td>

                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th style="width: 5px; text-align: center;"></th>
                                        <th>Description</th>
                                        <th>Date Uploaded</th>
                                        <th> Date From</th>

                                    </tr>
                                    </tfoot>
                                </table>
                                <div class="box-footer">
                                    <button type="button" id="cancel" class="btn btn-default pull-left"><i
                                                class="fa fa-arrow-left"></i> Back
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endsection

                @section('page_script')
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
                                location.href = "/vehicle_management/Search";
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