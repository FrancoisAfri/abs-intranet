<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <i class="fa fa-file-text-o pull-right"></i>
                <h3 class="box-title">Employee Ranking Report</h3>
                <p>Generated report</p>
            </div>
            <!-- /.box-header -->
            <form class="form-horizontal" method="POST" action="/appraisal/reports/result/print" target="_blank">
                {{ csrf_field() }}
                <input type="hidden" name="report_type" value="{{ $report_type }}">
                <input type="hidden" name="ranking_limit" value="{{ $ranking_limit }}">
                <input type="hidden" name="ranking_type" value="{{ $ranking_type }}">
                @if (isset($division_level_1) && $division_level_1 > 0)
                    <input type="hidden" name="division_level_1" value="{{ $division_level_1 }}">
                @elseif(isset($division_level_2) && $division_level_2 > 0)
                    <input type="hidden" name="division_level_2" value="{{ $division_level_2 }}">
                @elseif(isset($division_level_3) && $division_level_3 > 0)
                    <input type="hidden" name="division_level_3" value="{{ $division_level_3 }}">
                @elseif(isset($division_level_4) && $division_level_4 > 0)
                    <input type="hidden" name="division_level_4" value="{{ $division_level_4 }}">
                @elseif(isset($division_level_5) && $division_level_5 > 0)
                    <input type="hidden" name="division_level_5" value="{{ $division_level_5 }}">
                @endif
                <div class="box-body">
                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                        <strong class="lead">Report Parameters</strong><br>
                        <strong>{{ $divLevelName }}:</strong> <em>{{ $divName }}</em> &nbsp; &nbsp;
                        @if(!empty($rankingType))
                            | &nbsp; &nbsp; <strong>Ranking Type:</strong> <em>{{ $rankingType }}</em> &nbsp; &nbsp;
                        @endif
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
                                <td>{{ $empResult->emp_full_name }}</td>
                                <td>{{ $empResult->emp_result . '%' }}</td>
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