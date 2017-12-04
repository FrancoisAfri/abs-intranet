<div id="cancel-booking-warning-modal" class="modal modal-warning  fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-note-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title"><i class="fa fa-warning"></i> Cancel Booking</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <!-- <div id="success-alert"></div> -->

                     <div class="alert alert-warning">
                        <strong>Warning!</strong> Are you sure you want to Cancel this Booking ? This action cannot be undone
                      </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning pull-left" data-dismiss="modal">No</button>
                    <button type="button" id="cancel_booking" class="btn btn-warning" > Yes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
        
           