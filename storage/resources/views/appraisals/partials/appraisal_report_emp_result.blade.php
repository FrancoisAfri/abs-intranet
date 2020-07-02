<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <i class="fa fa-file-text-o pull-right"></i>
                <h3 class="box-title">Employees Performance Report</h3>
                <p>Generated report</p>
            </div>
            <!-- /.box-header -->
            <form class="form-horizontal" method="POST" action="/appraisal/reports/result/print" target="_blank">
                {{ csrf_field() }}
                <input type="hidden" name="report_type" value="{{ $report_type }}">
                @foreach($hr_person_id as $empID)
                    <input type="hidden" name="hr_person_id[]" value="{{ $empID }}">
                @endforeach
                <input type="hidden" name="date_from" value="{{ $dateFrom }}">
                <input type="hidden" name="date_to" value="{{ $dateTo }}">
                <div class="box-body">
                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                        <strong class="lead">Report Parameters</strong><br>
                        <strong>Appraisal Period:</strong> <em>{{ $dateFrom . ' - ' . $dateTo }}</em> &nbsp; &nbsp;
                        <!-- @if(!empty($dateFrom))
                                | &nbsp; &nbsp; <strong>Make:</strong> <em>{{ $dateFrom }}</em> &nbsp; &nbsp;
                            @endif -->
                    </p>
                    <table class="table table-striped">
                        <tr>
                            <th style="width: 5px">#</th>
                            <th>Employee Name</th>
                            <th>Result</th>
                        </tr>
                        @foreach($empsResult as $empResult)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $empResult->emp_name }}</td>
                                <td>
                                    <ul class="list-inline no-margin">
                                        @if(count($empResult->appraisal_result) > 0)
                                            @foreach($empResult->appraisal_result as $result)
                                                <li>{{ $result->month }} : <strong>{{ $result->result . '%' }}</strong></li>
                                                @if( ! $loop->last)
                                                    |
                                                @endif
                                            @endforeach
                                        @else
                                            <li>N/A</li>
                                        @endif
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div class="box-footer no-print">
                    <button type="button" class="btn btn-default pull-left" id="back_button"><i class="fa fa-arrow-left"></i> Back</button>
                    <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print Report</button>
                </div>
            </form>
        </div>
    </div>
</div>