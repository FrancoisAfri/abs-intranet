@extends('layouts.main_layout')
@section('page_dependencies')
	<link rel="stylesheet" href="{{ asset('bower_components/AdminLTE/plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/AdminLTE/plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"') }}">
	<link rel="stylesheet" href="{{ asset('bower_components/AdminLTE/plugins/iCheck/square/green.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">
@stop
@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-barcode pull-right"></i>
                    <h3 class="box-title"> Survey Report </h3>
                </div>
                <div class="box-body">
                    <div class="box-header">
                    </div>
                    <div style="overflow-X:auto;">
                        <table id=" " class="survey-report table table-bordered data-table my-2">
                            <thead>
								<tr>
									<th>Employee Name</th>
									<th>Date</th>
									<th>Client Name</th>
									<th>Comment</th>
									<th>Average Score</th>
								</tr>
                            </thead>
							<tbody>
								@if (count($empRatings) > 0)
									<ul class="products-list product-list-in-box">
										@foreach ($empRatings as $key => $ratings)
											<tr>
												<td>
													<a data-toggle="tooltip" title="Click to View Details"
													   href="{{ route('survey.show',['survey' => $ratings->id]) }}">
                                                    {{ (!empty($ratings->person->first_name) && !empty($ratings->person->surname)) ?  $ratings->person->first_name.' '.$ratings->person->surname : ''}}
													</a>
												</td>
												<td>{{ (!empty( $ratings->created_at)) ?  $ratings->created_at : ''}} </td>
												<td>{{ (!empty( $ratings->client_name)) ?  $ratings->client_name : ''}} </td>
												<td>{{ (!empty( $ratings->additional_comments)) ?  $ratings->additional_comments : ''}} </td>
												<td>{{ (!empty( $ratings->score)) ? $ratings->score    : '' }} </td>
												
											</tr>
									@endforeach
								@endif
							</tbody>
                        </table>
                        <!-- /.box-body -->
                        <div class="box-footer">
							<button type="button" class="btn btn-default pull-left" id="back_button"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
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
	<!-- bootstrap datepicker -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('bower_components/bootstrap_fileinput/js/fileinput.min.js') }}"></script>
	 <script src="{{ asset('bower_components/AdminLTE/plugins/iCheck/icheck.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>

    <script src="{{ asset('custom_components/js/deleteModal.js') }}"></script>

    <!-- End Bootstrap File input -->
    <script type="text/javascript">

        $('.popup-thumbnail').click(function(){
            $('.modal-body').empty();
            $($(this).parents('div').html()).appendTo('.modal-body');
            $('#modal').modal({show:true});
        });
		$('#back_button').click(function () {
                location.href = '/survey/reports';
            });
        $(function () {
			
			
            $('table.survey-report').DataTable({
                
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
                        title: 'Asset list Report',
                        exportOptions: {
                            stripHtml: false,
                            columns: ':visible:not(.not-export-col)'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Asset list Report',
                        //download: 'open',
                        exportOptions: {
                            stripHtml: true,
                            columns: ':visible:not(.not-export-col)'
                        },
                        orientation: 'landscape',
                        pageSize: 'LEGAL'
                    },
                    {extend: 'copyHtml5', exportOptions: {columns: ':visible'}},
                    {extend: 'csvHtml5', title: 'CSV', exportOptions: {columns: ':visible'}},
                    // { extend: 'excelHtml5', title: 'Excel', exportOptions: { columns: ':visible' } },
                    {
                        text: 'excel',
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        }
                    },

                ]

            });
        });
    </script>
@stop