<div id="delete-contact-warning-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="delete-contact-warning-modal-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

               <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-warning"></i> {{ $modal_title }}</h4>
				</div>
                <div class="modal-body">
				<form>
				<input type="hidden">
					<p>{{ $modal_content }}</p>
				</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning pull-left" data-dismiss="modal">No</button>
                    <button type="button" id="delete_contact" class="btn btn-warning"> Yes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
        
           