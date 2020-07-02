@extends('layouts.main_layout')

@section('page_dependencies')
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/buttons.dataTables.min.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <form class="form-horizontal" method="POST" action="/System/policy/viewUsers">
                    {{ csrf_field() }}
                    <div class="box-header with-border">
                        <h3 class="box-title">Policy Users For - {{$policy->name}} </h3>
						</br>
                         <h3 class="box-title">
							@if(!empty($policy->document))
								<a class="btn btn-default btn-flat btn-block pull-right btn-xs"
								   href="{{ Storage::disk('local')->url("Policies/policy/$policy->document") }}"
								   target="_blank"><i class="fa fa-file-pdf-o"></i> View Document</a>
							@else
								<a class="btn btn-default pull-centre btn-xs"><i
											class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
							@endif							
						</h3>
                        <button type="button" class="btn btn-default pull-right" id="print"><i class="fa fa-print"></i> Print</button>
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
                        <table id="example2" class="table table-bordered table-striped table-hover">
                            <thead>
                            <tr>
                                <th style="width: 5px; text-align: center;">Accept <input type="checkbox"
                                                                                          id="checkallaccept"
                                                                                          onclick="checkAllboxAccept()"/>
                                </th>
                                <th>Employee Name</th>
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
                            @foreach($policies as $policy)
                                <tr>
                                    <td style="width: 5px; text-align: center;">
                                        <input type="hidden" class="checkbox selectall"
                                               id="userID_{{ $policy->user_id }}"
                                               name="userID_{{ $policy->user_id }}" value="0">
                                        <input type="checkbox" class="checkbox selectall"
                                               id="userID_{{ $policy->user_id }}"
                                               name="userID_{{ $policy->user_id }}"
                                               value="1" {{$policy->read_understood === 1 ? 'checked ="checked"' : 0 }}>
                                    </td>
                                    <td style="vertical-align: middle;"
                                        nowrap>{{ (!empty( $policy->firstname . ' ' . $policy->surname)) ? $policy->firstname . ' ' . $policy->surname : ''}}</td>

                                    <td style="vertical-align: middle;"
                                        nowrap>{{ (!empty( $policy->company )) ? $policy->company : ''}}</td>

                                    <td style="vertical-align: middle;"
                                        nowrap>{{ (!empty( $policy->Department )) ? $policy->Department : ''}}</td>
                                    <td style="vertical-align: middle;"
                                        nowrap>{{ (!empty( $policy->date_added )) ? date(' d M Y', $policy->date_added)  : ''}}</td>

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
                                <th style="vertical-align: middle; text-align: center;"></th>
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
                                    class="fa fa-envelope-square"></i> Send Email
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
	<script src="/bower_components/AdminLTE/plugins/datatables/dataTables.buttons.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/buttons.flash.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/jszip.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/pdfmake.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/vfs_fonts.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/buttons.html5.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/buttons.print.min.js"></script>
    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>

    <script>
        $('#back_button').click(function () {
            location.href = '/System/policy/reports';
        });

        $('#print').click(function () {
            location.href = '/System/policy/print/{{ $policy->id }}';
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
            $('#example2').DataTable({
				"paging": true,
				"lengthChange": true,
				"lengthMenu": [ 50, 75, 100, 150, 200, 250 ],
				"pageLength": 50,
				"searching": true,
				"ordering": true,
				"info": true,
				"autoWidth": true,
				dom: 'lfrtipB',
				buttons: [
					{
						extend: 'excelHtml5',
						title: 'Fines Report'
					},
					{
						extend: 'csvHtml5',
						title: 'Fines Report'
					},
					{
						extend: 'copyHtml5',
						title: 'Fines Report'
					}
				]
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