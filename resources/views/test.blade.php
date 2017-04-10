@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <form class="form-horizontal" method="POST" name="capture-payment-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Capture Client Payment</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group">
                        <label for="date_added" class="col-sm-3 control-label">Payment Date</label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control datepicker" id="date_added" name="date_added" placeholder="  dd/mm/yyyy">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="amount_paid" class="col-sm-3 control-label">Amount</label>

                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    R
                                </div>
                                <input type="text" class="form-control" id="amount_paid" name="amount_paid" placeholder="Enter the amount paid">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" id="submit-payment" class="btn btn-success">Submit Payment</button>
                </div>
            </form>
        </div>
    </div>
    <!--
    <div id="attendees">
            <div id="att1" class="attendee">
                    <fieldset>
                        <legend><span class="legend">Attendee 1 Booking Details</span></legend>
                        <p name="test">
                        <label for="A_Title_1">Title: <span class="req">*</span></label>
                        <input name="A_Title_1" id="A_Title_1" value="" type="text" class="f_input" />
                        </p>

                        <p>
                        <label for="A_Forename_1">Forename: <span class="req">*</span></label>
                        <input name="A_Forename_1" id="A_Forename_1" type="text" class="f_input" />
                        </p>

                        <p>
                        <label for="A_Surname_1">Surname: <span class="req">*</span></label>
                        <input name="A_Surname_1" id="A_Surname_1" type="text" class="f_input" />
                        </p>

                        <p>
                        <label for="A_Info_1">Additional Info: </label>
                        <textarea name="A_Info_1" id="A_Info_1" cols="20" rows="10"></textarea>
                        <span class="info">Please include any infomation relating to dietary/ access/special requirements you might have.</span>
                        </p>
                    </fieldset>
                    </div>
            <a href="#" class="add">Add more</a>
    </div>
    -->
@endsection

@section('page_script')

<script type="text/javascript">
$(function(){
    var template = $('#attendees .attendee:first').clone(),
        attendeesCount = 1;

    var addAttendee = function(){
        attendeesCount++;
        var attendee = template.clone().find(':input').each(function(){
            var newId = this.id.substring(0, this.id.length-1) + attendeesCount;
            $(this).prev().attr('for', newId); // update label for (assume prev sib is label)
            this.name = this.id = newId; // update id and name (assume the same)
        }).end() // back to .attendee
        .attr('id', 'att' + attendeesCount) // update attendee id
        .prependTo('#attendees'); // add to container
    };

    $('.add').click(addAttendee); // attach event
});
</script>
@endsection