@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
    <!-- DataTables -->
    {{--<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">--}}

@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <form class="form-horizontal" method="POST" action="/System/policy/print/{{$Policy->id}}">
                    {{ csrf_field() }}

                    <div class="box-header with-border">
                        <h3 class="box-title">Policy Users For - {{$Policy->name}} </h3>
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

                                <th> Employee Name</th>
                                <th>Company</th>
                                <th>Department</th>
                                <th>Date Added</th>
                                <th>Date Read</th>
                                <th>Understood</th>
                                <th>Not Understood</th>
                                <th>Read but not sure</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($Policies as $policy)
                                <tr>

                                    <td style="vertical-align: middle;"
                                        nowrap>{{ (!empty( $policy->firstname . ' ' . $policy->surname)) ? $policy->firstname . ' ' . $policy->surname : ''}}</td>

                                    <td style="vertical-align: middle;"
                                        nowrap>{{ (!empty( $policy->company )) ? $policy->company : ''}}</td>

                                    <td style="vertical-align: middle;"
                                        nowrap>{{ (!empty( $policy->Department )) ? $policy->Department : ''}}</td>
                                    <td style="vertical-align: middle;"
                                        nowrap>{{ (!empty( $policy->date_added )) ? $policy->date_added  : ''}}</td>

                                    <td style="vertical-align: middle;"
                                        nowrap>{{ (!empty( $policy->date_read )) ? date(' d M Y', $policy->date_read) : '' }}</td>

                                    <td style="vertical-align: middle;"
                                        nowrap>{{ (!empty( $policy->read_understood )) ? 'Yes' : 'N/A'}}
                                    </td>
                                    <td style="vertical-align: middle;" nowrap>
                                        {{ (!empty( $policy->read_not_understood )) ? 'Yes' : 'N/A'}}
                                    </td>
                                    <td style="vertical-align: middle;" nowrap>
                                        {{ (!empty( $policy->read_not_sure )) ? 'Yes' : 'N/A'}}</td>
                                    @endforeach
                                </tr>

                            </tbody>
                            <tfoot>
                            <tr>
                                <th> Employee Name</th>
                                <th>Company</th>
                                <th>Department</th>
                                <th> Date Added</th>
                                <th> Date Read</th>
                                <th>Understood</th>
                                <th>Not Understood</th>
                                <th>Read but not sure</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                        <button type="submit" class="btn btn-primary pull-right"><i
                                    class="fa fa-print"></i> Print
                        </button>

                    </div>

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

    <!-- DataTables -->
    <script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>

    <script>
        $('#back_button').click(function () {
            location.href = '/System/policy/reports';
        });

        $('#print').click(function () {
            location.href = '/System/policy/viewuserdetails/{{ $Policy->id }}';
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
            //Initialize Select2 Elements
            $(".select2").select2();

            //Tooltip
            $('[data-toggle="tooltip"]').tooltip();

            //Initialize the data table
//            $('#emp-list-table').DataTable({
//                "paging": true,
//                "lengthChange": true,
//                "searching": true,
//                "ordering": true,
//                "info": false,
//                "autoWidth": true
//            });

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

            function postData(id, data) {
                if (data == 'access_button') location.href = "/leave/approval/" + id;
            }
            //Show success action modal
            @if(Session('changes_saved'))
            $('#success-action-modal').modal('show');
            @endif
        });
    </script>
@endsection