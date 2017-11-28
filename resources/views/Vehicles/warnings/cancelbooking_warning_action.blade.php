<div id="cancel-booking-warning-modal" class="modal modal-warning  fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <form class="form-horizontal" method="POST" name="reject-leave-form">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-warning"></i> {{ $modal_title }}</h4>
            </div>

            <div class="modal-body">
                <p>{{ $modal_content }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">No</button>
                <button type="button" id="cancel_booking" class="btn btn-outline pull-right" data-dismiss="modal">Yes</button>

            </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>