<div id="view-news-modal" class="modal modal-default fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-news-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add News </h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>


                    <td>{{ !empty($news->expirydate) ? date(' d M Y', $news->expirydate) : '' }}</td>
                    <td>{{ !empty($news->name) ? $news->name : ''}}</td>
                    <td>{{ !empty($news->description) ? $news->description : ''}}</td>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add_news" class="btn btn-primary">
                        Add News
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
           