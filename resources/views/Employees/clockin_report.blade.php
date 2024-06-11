@extends('layouts.main_layout')
@section('page_dependencies')

    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">
	    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
@stop
@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user-times pull-right"></i>
                    <h3 class="box-title"> Report Results </h3>
                </div>
                <div class="box-body">
                    <div class="box-header">

                        <div class="form-group container-sm">
                            <form class="form-horizontal" method="get" action="{{ route('employee.clockin_report') }}">
                                {{ csrf_field() }}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-sm-4">
                                            <label>Employees</label>
											<select class="form-control select2" style="width: 100%;"
													id="employee_number" name="employee_number">
												<option value="">*** Select Employee ***</option>
												@foreach($employees as $employee)
													<option value="{{ $employee->id }} ">{{$employee->first_name . ' ' . $employee->surname }}</option>
												@endforeach
											</select>
                                        </div>
										<div class="col-sm-4">
                                            <label>Types</label>
                                            <select class="form-control select2 " style="width: 100%;"
                                                    id="clockin_type" name="clockin_type" data-select2-id="1"
                                                    tabindex="-1" aria-hidden="true">
                                                <option value="0">** Select Type **</option>
                                                    <option value="1">CLOCK IN</option>
                                                    <option value="2">CLOCK OUT</option>
                                            </select>
                                        </div>
										<div class="col-sm-4">
                                            <label>Dates</label>
													<input type="text" class="form-control daterangepicker" id="action_date"
														   name="action_date" value="" placeholder="Select Action Date...">
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
                    </div>
                    <div style="overflow-X:auto;">
                        <table id=" " class="asset table table-bordered data-table my-2">
                            <thead>
                            <tr>
                                <th style="width: 10px; text-align: center;"></th>
                                <th style="width: 5px; text-align: center;">Employee Number</th>
                                <th style="width: 5px; text-align: center;">Name</th>
                                <th style="width: 5px; text-align: center;">Email</th>
                                <th style="width: 5px; text-align: center;">Type</th>
                                <th style="width: 5px; text-align: center;">Location</th>
                                <th style="width: 5px; text-align: center;">Date & Time</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($clockins) > 0)
                                <ul class="products-list product-list-in-box">
                                    @foreach ($clockins as $key => $clockin)
                                        <tr id="categories-list">
                                            <td nowrap>
                                                <div class="product-img">
                                                    <img src="{{ (!empty($clockin->user->profile_pic)) ? asset('storage/avatars/'.$clockin->user->profile_pic)  :
                                                            (!empty($clockin->user->gender) && ($clockin->user->gender === 2) ? $f_silhouette : $m_silhouette)}} "
                                                         width="50" height="50" alt="Profile Picture">
                                                </div>
                                                <div class="modal fade" id="enlargeImageModal" tabindex="-1"
                                                     role="dialog" align="center"
                                                     aria-labelledby="enlargeImageModal" aria-hidden="true">
                                                    <!--  <div class="modal-dialog modal" role="document"> -->
                                                    <div class="modal-dialog modal-sm">
                                                        <div class="modal-body" align="center">
                                                            <img src="" class="enlargeImageModalSource"
                                                                 style="width: 200%;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="text-align:center;">
                                                <span data-toggle="tooltip" title="" class="badge bg-grey"
                                                      data-original-title="">{{ (!empty($clockin->user->employee_number)) ? $clockin->user->employee_number : '' }}</span>
                                            </td>
                                            <td style="text-align:center;">
                                                {{ (!empty($clockin->user->first_name) && !empty($clockin->user->surname)) ?  $clockin->user->first_name . ' ' . $clockin->user->surname : ''}}
                                            </td>
                                            <td style="text-align:center;">
                                                <i class="fa fa-envelope-o"></i> {{ (!empty($clockin->user->email)) ? $clockin->user->email : ' ' }}
                                            </td>
                                            <td style="text-align:center;">{{ (!empty($clockin->clockin_type) && $clockin->clockin_type == 1) ? 'CLOCK IN' : 'CLOCK OUT' }}</td>
                                            <td style="text-align:center;">{{ (!empty($clockin->location)) ? $clockin->location : ' ' }}</td>
                                            <td style="text-align:center;">{{ (!empty($clockin->created_at)) ? $clockin->created_at : ' ' }}</td>
                                        </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        <!-- /.box-body -->
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop
@section('page_script')
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('custom_components/js/modal_ajax_submit.js') }}"></script>
    <script src="{{ asset('custom_components/js/deleteAlert.js') }}"></script>

    <script src="{{ asset('bower_components/bootstrap_fileinput/js/fileinput.min.js') }}"></script>

    <script src="{{ asset('plugins/axios/dist/axios.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
    <!-- Bootstrap date picker -->
    <script src="/bower_components/AdminLTE/plugins/daterangepicker/moment.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- End Bootstrap File input -->
    <script type="text/javascript">

        function sendStatus() {

            let select = document.getElementById("status_id");
            console.log(select)

        }

        function postData(id, data) {
            if (data === 'actdeac') location.href = "{{route('employee.activate', '')}}" + "/" + id;
        }

        $('.popup-thumbnail').click(function () {
            $('.modal-body').empty();
            $($(this).parents('div').html()).appendTo('.modal-body');
            $('#modal').modal({show: true});
        });

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
			//Date Range picker
            $('.daterangepicker').daterangepicker({
				locale:{ format:'DD/MM/YYYY' },
                endDate: '-1d',
                autoclose: true
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

            $(function () {
                $('img').on('click', function () {
                    $('.enlargeImageModalSource').attr('src', $(this).attr('src'));
                    $('#enlargeImageModal').modal('show');
                });
            });

        });
    </script>
@stop