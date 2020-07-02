@extends('layouts.main_layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Meeting Search Results</h3>
                </div>
                <!-- /.box-header -->
				<!--<form class="form-horizontal" method="POST" action="/audits/print">-->
					{{ csrf_field() }}
                <div class="box-body">
                    <!-- Collapsible section containing the amortization schedule -->
                    <div class="box-group" id="accordion">
                        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                            <div class="box-body">
								<table class="table table-striped">
									<tr>
										<th>Title</th>
										<th>Location</th>
										<th>Agenda</th>
										<th>Date</th>
										<th>Client</th>
									</tr>
									@if(!empty($meetings))
										@foreach($meetings as $meeting)
											<tr>
												<td><a href="{{ '/meeting_minutes/view_meeting/' . $meeting->id . '/view' }}" class="product-title">{{ !empty($meeting->meeting_name) ? $meeting->meeting_name : '' }}</a></td>
												<td>{{ !empty($meeting->meeting_location) ? $meeting->meeting_location : '' }}</td>
												<td>{{ !empty($meeting->meeting_agenda) ? $meeting->meeting_agenda : '' }}</td>
												<td>{{ !empty($meeting->created_at) ? $meeting->created_at : '' }}</td>
												<td>{{ !empty($meeting->compname) ? $meeting->compname : '' }}</td>
											</tr>
										@endforeach
									<tr>
										<th>Title</th>
										<th>Location</th>
										<th>Agenda</th>
										<th>Date</th>
									</tr>
									@endif
								</table>
								<div class="row no-print">
									<div class="col-xs-12">
									<button type="button" class="btn btn-default pull-left" id="back_button"><i class="fa fa-arrow-left"></i> Back</button>
									</div>
								</div>
								<!-- End amortization /table -->
							</div>
                    </div>
                    <!-- /. End Collapsible section containing the amortization schedule -->
                </div>
				</form>
            </div>
        </div>
    </div>
@endsection
@section('page_script')
    <script>
        $(function () {
            //Cancel button click event
            $('#back_button').click(function () {
                location.href = '/meeting_minutes/search';
            });
        });
    </script>
@endsection