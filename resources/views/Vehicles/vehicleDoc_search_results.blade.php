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
                    <h3 class="box-title"> Vehicle Document Result(s) </h3>
                </div>
                <div class="box-body">
                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div style="overflow-X:auto;">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th>Date Uploaded</th>
                                        <th> Date From</th>
                                        <th> Expiry From</th>
                                        <th style="width: 5px; text-align: center;"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (count($vehicleDocumets) > 0)
                                        @foreach ($vehicleDocumets as $document)
                                            <tr id="categories-list">
                                                <td>{{ !empty($document->description) ? $document->description : ''}}</td>
                                                <td>{{ !empty($document->upload_date) ? date(' d M Y', $document->upload_date) : '' }}</td>
                                                <td>{{ !empty($document->date_from) ? date(' d M Y', $document->date_from) : '' }}</td>
                                                <td>{{ !empty($document->exp_date) ? date(' d M Y', $document->exp_date) : '' }}</td>
                                                <td nowrap>
                                                    <div class="form-group{{ $errors->has('document') ? ' has-error' : '' }}">
                                                        <label for="document" class="control-label"></label>
                                                        @if(!empty($document->document))
                                                            <a class="btn btn-default btn-flat btn-block pull-right btn-xs"
                                                               href="{{ Storage::disk('local')->url("Vehicle/documents/$document->document") }}"
                                                               target="_blank"><i class="fa fa-file-pdf-o"></i> View Document</a>
                                                        @else
                                                            <a class="btn btn-default pull-centre btn-xs"><i class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Description</th>
                                        <th>Date Uploaded</th>
                                        <th> Date From</th>
                                        <th> Expiry From</th>
                                        <th style="width: 5px; text-align: center;"></th>
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
                        <script>
                            function postData(id, data) {
                                if (data == 'actdeac') location.href = "/vehicle_management/vehicles_Act/" + id;
                            }

                            //Cancel button click event
                            document.getElementById("cancel").onclick = function () {
                                location.href = "/vehicle_management/create_request";
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

                        </script>
@endsection