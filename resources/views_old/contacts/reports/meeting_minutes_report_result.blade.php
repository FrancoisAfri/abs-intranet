@extends('layouts.main_layout')
@section('page_dependencies')
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/buttons.dataTables.min.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <form class="form-horizontal" method="POST" action="/reports/contact_note/meetingreport">
                    {{ csrf_field() }}
					<input type="hidden" name="hr_person_id" value="{{ !empty($personID) ? $personID : ''  }}">
					<input type="hidden" name="company_id" value="{{ !empty($companyID) ? $companyID : ''  }}">
					<input type="hidden" name="date_from" value="{{ !empty($Datefrom) ? $Datefrom : ''  }}">
					<input type="hidden" name="date_to" value="{{ !empty($Dateto) ? $Dateto : ''  }}">
                    <div class="box-header with-border">
                         <i class="fa fa-file-text-o pull-right"></i>
                        <h3 class="box-title">Meetings Report Search Results</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div style="overflow-x:auto;">  
							<table  id="example2" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th>Meeting Name</th>
										<th>Meeting Date</th>
										<th>Meeting Location</th>
										<th>Meeting Agenda</th>
										<th>Meeting Minutes</th>
									</tr>
								</thead>
								<tbody>
                                    @if(count($meetingminutes) > 0)
										@foreach($meetingminutes as $meeting)
											<tr>
												<td>{{ (!empty($meeting->meeting_name)) ?  $meeting->meeting_name : ''}} </td>
												<td>{{ !empty($meeting->meeting_date) ? date('d M Y ', $meeting->meeting_date) : '' }}</td>
												<td>{{ (!empty($meeting->meeting_location)) ?  $meeting->meeting_location : ''}} </td>
												<td>{{ (!empty($meeting->meeting_agenda)) ?  $meeting->meeting_agenda : ''}} </td> 
												<td>{{ (!empty($meeting->meeting_minutes)) ?  $meeting->meeting_minutes : ''}} </td>     
											</tr>
										@endforeach
                                    @endif
                                </tbody> 
								<tfoot>
									<tr>
										<th>Meeting Name</th>
										<th>Meeting Date</th>
										<th>Meeting Location</th>
										<th>Meeting Agenda</th>
										<th>Meeting Minutes</th>
									</tr>						
								</tfoot>
                            </table>
                        </div>
                    </div>
					<div class="box-footer no-print">
						<button type="button" class="btn btn-default pull-left" id="back_button"><i class="fa fa-arrow-left"></i> Back</button>
						<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print Report</button>
					</div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page_script')
	<!-- DataTables -->
	<script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/dataTables.buttons.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/buttons.flash.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/jszip.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/pdfmake.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/vfs_fonts.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/buttons.html5.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/buttons.print.min.js"></script><!-- End Bootstrap File input -->
    <script>
         $(function () {
            //Cancel button click event
            $('#back_button').click(function () {
                location.href = '/contacts/Clients-reports';
            });
        });
		$(function () {
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
						title: 'Meetings Report'
					},
					{
						extend: 'csvHtml5',
						title: 'Meetings Report'
					},
					{
						extend: 'copyHtml5',
						title: 'Meetings Report'
					}
				]
			});
		});
    </script>
@endsection
