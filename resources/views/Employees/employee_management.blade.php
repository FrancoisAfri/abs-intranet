@extends('layouts.main_layout')
@section('page_dependencies')

    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"') }}">
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
                                                <option value="1">** Select Status **</option>
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
                    </div>
                    <div style="overflow-X:auto;">
                        <table id=" " class="asset table table-bordered data-table my-2">
                            <thead>
                            <tr>
                                <th style="width: 10px; text-align: center;"></th>
                                <th style="width: 5px; text-align: center;">Employee Number</th>
                                <th style="width: 5px; text-align: center;">Name</th>
                                <th style="width: 5px; text-align: center;">Email</th>
                                <th style="width: 5px; text-align: center;">Phone Number</th>
                                <th style="width: 5px; text-align: center;">Position</th>
                                <th style="width: 5px; text-align: center;">Manager</th>
                                <th style="width: 5px; text-align: center;">Division</th>
                                <th style="width: 5px; text-align: center;">Department</th>
                                <th style="width: 5px; text-align: center;"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($employee) > 0)
                                <ul class="products-list product-list-in-box">
                                    @foreach ($employee as $key => $person)
                                        <tr>
                                            <td nowrap>
                                                <div class="product-img">
                                                    <img src="{{ (!empty($person->profile_pic)) ? asset('storage/avatars/'.$person->profile_pic)  :
                                                            (($person->gender === 2) ? $f_silhouette : $m_silhouette)}} "
                                                         width="50" height="50" alt="Profile Picture">
                                                </div>
                                                <div class="modal fade" id="enlargeImageModal" tabindex="-1"
                                                     role="dialog" align="center"
                                                     aria-labelledby="enlargeImageModal" aria-hidden="true">
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
                                                      data-original-title="">{{ (!empty($person->employee_number)) ? $person->employee_number : '' }}</span>
                                            </td>
                                            <td>
                                                <input type="button" onclick="location.href='/employee/show/{{str_replace(' ', '_', strtolower($person->first_name) )}}-{{$person->id}}-{{str_replace(' ', '_', strtolower($person->surname))}}';"
                                                       value="{{ (!empty( $person->first_name . ' ' . $person->surname)) ?  $person->first_name . ' ' . $person->surname : ''}}" />
                                            </td>

                                            <td>
                                                <i class="fa fa-envelope-o"></i> {{ (!empty($person->email)) ? $person->email : ' ' }}
                                            </td>

                                            <td>
                                                <i class="fa fa-mobile"></i> {{ (!empty($person->cell_number)) ? $person->cell_number : ' ' }}
                                            </td>

                                            <td>{{ (!empty($person->jobTitle->name)) ? $person->jobTitle->name : ' ' }}</td>

                                            <td>{{ (!empty($person->managerDetails->first_name . ' ' . $person->managerDetails->surname)) ? $person->managerDetails->first_name . ' ' . $person->managerDetails->surname : ' ' }}</td>

                                            <td>{{ (!empty($person->division->name)) ? $person->division->name : ' ' }}</td>

                                            <td>{{ (!empty($person->department->name)) ? $person->department->name : ' ' }}</td>

                                            <td>
                                                <button vehice="button" id="view_ribbons" class="btn {{ (!empty($person->status) && $person->status == 1) ? " btn-danger " : "btn-success " }}
                                                      btn-xs" onclick="postData({{$person->user_id}}, 'actdeac');"><i
                                                            class="fa {{ (!empty($person->status) && $person->status == 1) ?
                                                      " fa-times " : "fa-check " }}"></i> {{(!empty($person->status) && $person->status == 1) ? "De-Activate" : "Activate"}}
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
    {{--    <script src="{{ asset('custom_components/js/dataTable.js') }}"></script>--}}

    <!-- End Bootstrap File input -->
    <script type="text/javascript">

        function sendStatus() {

            let select = document.getElementById("status_id");
        }

        function postData(id, data) {
            if (data === 'actdeac') location.href = "{{route('employee.activate', '')}}" + "/" + id;
        }

        function redirectData(id, data) {
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
                    // 'copy', 'csv', 'excel',
                    {
                        extend: 'print',
                        title: 'Impression',
                        exportOptions: {
                            stripHtml: false,
                            columns: ':visible:not(.not-export-col)'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Employee Records',
                        //download: 'open',
                        exportOptions: {
                            columns: ':visible'
                        },
                        orientation: 'landscape',
                        pageSize: 'LEGAL'
                    },
                    {extend: 'copyHtml5', exportOptions: {columns: ':visible'}},
                    {extend: 'csvHtml5', title: 'CSV', exportOptions: {columns: ':visible'}},
                   
                    {
                        text: 'excel',
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        }
                    },
                    'colvis'
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

            $(function () {
                $('img').on('click', function () {
                    $('.enlargeImageModalSource').attr('src', $(this).attr('src'));
                    $('#enlargeImageModal').modal('show');
                });
            });
        });
    </script>
@stop