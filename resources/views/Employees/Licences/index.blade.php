@extends('layouts.main_layout')
@section('page_dependencies')

    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap_fileinput/css/fileinput.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/AdminLTE/plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/AdminLTE/plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/AdminLTE/plugins/iCheck/square/green.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fine-uploader/fine-uploader-gallery.css') }}">
    <script src="/custom_components/js/deleteAlert.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">

@stop
@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user-times pull-right"></i>
                    <h3 class="box-title"> Manage Users </h3>
                </div>
                <div class="box-body">
                    <div class="box-header">

                        <div class="form-group container-sm">
                            <form class="form-horizontal" method="get" action="{{ route('employee.index') }}">
                                {{ csrf_field() }}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-sm-4">
                                            <label>Status</label>
                                            <select class="form-control select2 " style="width: 100%;"
                                                    id="status_id" name="status_id" data-select2-id="1" tabindex="-1"
                                                    aria-hidden="true">
                                                <option value="0">** Select Status **</option>
                                                @foreach ( \App\HRPerson::STATUS_SELECT() as $key =>$values)
                                                    <option value="{{ $key }}">{{ $values }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary pull-left">Submit</button>
                                        <br>
                                    </div>

                                </div>

                            </form>
                        </div>
                        <br>
                        <button type="button" id="cat_module" class="btn btn-default pull-right" data-toggle="modal"
                                data-target="#add-licence-modal">Add new Licence
                        </button>
                    </div>
                    <div style="overflow-X:auto;">
                        <table id=" " class="asset table table-bordered data-table my-2">
                            <thead>
                            <tr>
                                <th style="width: 10px; text-align: center;"></th>
                                <th style="width: 5px; text-align: center;">Name</th>
                                <th style="width: 5px; text-align: center;">Details</th>
                                <th style="width: 5px; text-align: center;">order_number</th>
                                <th style="width: 5px; text-align: center;">Serial Number</th>
                                <th style="width: 5px; text-align: center;">Purchase Date</th>
                                <th style="width: 5px; text-align: center;">Purchase Cost</th>
                                <th style="width: 5px; text-align: center;">Total Number</th>
                                <th style="width: 5px; text-align: center;">Expiration Date</th>
                                <th style="width: 5px; text-align: center;"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count(array($licences)) > 0)
								@foreach ($licences as $key => $licence)
									<tr>
										<td>
										</td>
										<td style="width: 5px; text-align: center;">
											<a data-toggle="tooltip" title="Click to View Licence Details"
											   href="{{ route('licences_management.show',['licence' => $licence->uuid]) }}">
												{{ (!empty( $licence->name)) ?  $licence->name : ''}}
											</a>
										</td>

										<td style="width: 5px; text-align: center;">
											</i> {{ (!empty($licence->details)) ? $licence->details : ' ' }}
										</td>

										<td style="width: 5px; text-align: center;">
									   </i> {{ (!empty($licence->order_number)) ? $licence->order_number : ' ' }}
										</td>

										<td style="width: 5px; text-align: center;">
											</i> {{ (!empty($licence->serial)) ? $licence->serial : ' ' }}
										</td>

										<td style="width: 5px; text-align: center;">
											{{ (!empty($licence->purchase_date)) ? $licence->purchase_date : ' ' }}
										</td>

										<td style="width: 5px; text-align: center;">
											{{ (!empty($licence->purchase_cost)) ? $licence->purchase_cost : ' ' }}
										</td>

										<td style="width: 5px; text-align: center;">{{ (!empty($licence->total)) ? $licence->total : ' ' }}</td>

										<td style="width: 5px; text-align: center;">{{ (!empty($licence->expiration_date)) ? $licence->expiration_date : ' ' }}</td>

										<td style="width: 5px; text-align: center;">
											<button vehice="button" id="view_ribbons" class="btn {{ (!empty($licence->status) && $licence->status == 1) ? " btn-danger " : "btn-success " }}
												  btn-xs" onclick="postData({{$licence->id}}, 'actdeac');"><i class="fa {{ (!empty($licence->status) && $licence->status == 1) ?
												  " fa-times " : "fa-check " }}"></i> {{(!empty($licence->status) && $licence->status == 1) ? "De-Activate" : "Activate"}}
											</button>
										</td>
									</tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        <!-- /.box-body -->
                    </div>
                </div>
                @include('Employees.Licences.partials.create_license')
            </div>
        </div>
    </div>
@stop
@section('page_script')
    <!-- DataTables -->
    {{--    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js"') }}"></script>--}}
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('custom_components/js/modal_ajax_submit.js') }}"></script>
    <script src="{{ asset('custom_components/js/deleteAlert.js') }}"></script>

    <script src="{{ asset('bower_components/bootstrap_fileinput/js/fileinput.min.js') }}"></script>

    <script src="{{ asset('plugins/axios/dist/axios.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('plugins/fine-uploader/fine-uploader.js') }}"></script>
    <script src="/custom_components/js/deleteAlert.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    {{--    <script src="{{ asset('custom_components/js/dataTable.js') }}"></script>--}}

    <!-- End Bootstrap File input -->
    <script type="text/javascript">

        function postData(id, data) {
            if (data === 'actdeac') location.href = "{{route('LicenceMan.activate', '')}}" + "/" + id;
        }

        // Initialize date picker Elements
        $('.datepicker').datepicker({
            format: 'yyyy/mm/dd',
            autoclose: true,
            todayHighlight: true
        }).datepicker("setDate", 'now');


        //TODO WILL CREATE A SIGLE GLOBAL FILE

        $(function () {

            $('table.asset').DataTable({

                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });

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

            $('#add-licence').on('click', function () {
                let strUrl = '{{route('licences_management.store')}}';
                let modalID = 'add-licence-modal';
                let formName = 'add-licence-form';
                let files = 'file';
                let submitBtnID = 'add-licence';
                let redirectUrl = '{{ route('licences_management.index') }}';
                let successMsgTitle = 'Uploaded Successfully!';
                let successMsg = 'The Asset Licence  has been updated successfully.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });



        });
    </script>
@stop