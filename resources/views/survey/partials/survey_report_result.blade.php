<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <i class="fa fa-file-text-o pull-right"></i>
                <h3 class="box-title">Survey Report</h3>
                <p>Generated report</p>
            </div>
            <!-- /.box-header -->
            <form method="POST" action="/survey/reports/print" target="_blank">
                {{ csrf_field() }}
                <input type="hidden" name="hr_person_id" value="{{ $empID }}">
                <input type="hidden" name="date_from" value="{{ $dateFrom }}">
                <input type="hidden" name="date_to" value="{{ $dateTo }}">
                <div class="box-body">
                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                        <strong class="lead">Report Parameters</strong><br>
                        <strong>Employee Name:</strong> <em>{{ $empFullName }}</em> &nbsp; &nbsp;
                        @if(!empty($strDateFrom) || !empty($strDateTo))
                            | &nbsp; &nbsp; <strong>Report Period:</strong> <em>{{ ($strDateFrom) ? $strDateFrom : '[first entry]' . ' - ' . ($strDateTo) ? $strDateTo : '[last entry]' }}</em> &nbsp; &nbsp;
                        @endif
                    </p>
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th style="width: 5px">#</th>
                            <th nowrap>Feedback Date</th>
                            <th nowrap>Client Name</th>
                            <th nowrap>Quote / Booking No.</th>
                            <th>Ratings</th>
                            <th>Client's Comment</th>
                        </tr>
                        @foreach($empRatings as $empRating)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td nowrap>{{ ($empRating->feedback_date) ? date('d F Y', $empRating->feedback_date) : '' }}</td>
                                <td nowrap>{{ $empRating->client_name }}</td>
                                <td nowrap>{{ $empRating->booking_number }}</td>
                                <td style="width: 244px;">
                                    @foreach($empRating->surveyQuestions as $surveyQuestion)
                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label class="control-label"><em>{{ $surveyQuestion->description }}</em></label>
                                            <input value="{{ $surveyQuestion->pivot->result }}" class="form-control rating-loading" data-size='xs'>
                                        </div>
                                    @endforeach
                                    @if($empRating->avg_rating)
                                        <div class="form-group" style="margin-bottom: 0; color: blue;">
                                            <label class="control-label"><em>Total Rating</em></label>
                                            <input value="{{ $empRating->avg_rating }}" class="form-control rating-loading" data-size='xs'>
                                        </div>
                                    @endif
                                </td>
                                <td style="max-width: 250px;">{{ $empRating->additional_comments }}</td>
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