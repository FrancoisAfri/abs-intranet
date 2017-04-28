@extends('layouts.main_layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-file-text pull-right"></i>
                    <h3 class="box-title">Employee Ranking Report</h3>
                    <p>Generated report</p>
                </div>
                <!-- /.box-header -->
                <form class="form-horizontal" method="POST" action="">
                    <input type="hidden" name="action_date" value="">
                    <input type="hidden" name="user_id" value="">
                    <input type="hidden" name="module_name" value="">
                    <input type="hidden" name="action" value="">
                    {{ csrf_field() }}
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
@endsection
@section('page_script')
    <script>
        $(function () {
            //Cancel button click event
            $('#back_button').click(function () {
                location.href = '/appraisal/reports';
            });
        });

    </script>
@endsection