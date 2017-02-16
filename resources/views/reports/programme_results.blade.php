@extends('layouts.main_layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Programme Report</h3>
                </div>
                <!-- /.box-header -->
                <form class="form-horizontal" method="POST" action="/reports/programme/print">
                    <input type="hidden" name="start_date" value="{{!empty($start_date) ? $start_date : ''}}">
                    <input type="hidden" name="end_date" value="{{!empty($end_date) ? $end_date : ''}}">
                    <input type="hidden" name="service_provider_id"
                           value="{{!empty($service_provider_id) ? $service_provider_id : ''}}">
                    <input type="hidden" name="Prog_manager_id"
                           value="{{!empty($Prog_manager_id) ? $Prog_manager_id : ''}}">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <!-- Collapsible section containing the amortization schedule -->
                        <div class="box-group" id="accordion">
                            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                            <div class="panel box box-primary">
                                <div class="box-body">
                                    <table class="table table-striped">
                                        <tr>
                                            <th>Name</th>
                                            <th>Code</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Brief Description</th>
                                            <th>Service Provider</th>
                                            <th>Manager</th>
                                        </tr>
                                        @if(count($programmes) > 0)
                                            @foreach($programmes as $programme)
                                                <tr>
                                                    <td>{{ !empty($programme->name) ? $programme->name : '' }}</td>
                                                    <td>{{ !empty($programme->code) ? $programme->code : '' }}</td>
                                                    <td>{{ !empty($programme->start_date) ? date('Y M d', $programme->start_date) : '' }}</td>
                                                    <td>{{ !empty($programme->end_date) ? date('Y M d', $programme->end_date) : '' }}</td>
                                                    <td>{{ !empty($programme->description) ? $programme->description : '' }}</td>
                                                    <td>{{ !empty($programme->com_name) ? $programme->com_name : '' }}</td>
                                                    <td>{{ !empty($programme->hr_firstname) && !empty($programme->hr_surname) ? $programme->hr_firstname.' '.$programme->hr_surname : '' }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </table>
                                    <div class="row no-print">
                                        <div class="col-xs-12">
                                            <button type="submit" class="btn btn-primary pull-right"><i
                                                        class="fa fa-print"></i>Print report
                                            </button>
                                        </div>
                                    </div>
                                    <!-- End amortization /table -->
                                </div>
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
    <script type="text/javascript">
        //Cancel button click event
        document.getElementById("back_to_search").onclick = function () {
            location.href = "/reports/programme";
        };
    </script>
@endsection