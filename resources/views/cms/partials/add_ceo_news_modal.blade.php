<div id="add-news-modal" class="modal modal-default fade">
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

                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label"> Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="name" name="name" value=""
                                   placeholder="Enter Name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-sm-2 control-label"> Description</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="description" name="description" value=""
                                   placeholder="Enter Description" required>
                        </div>
                    </div>
					<div class="form-group">
                        <label for="video_content" class="col-sm-2 control-label">Upload a Video</label>
                        <div class="col-sm-8">

                            <input type="file" id="video_content" name="video_content" class="file file-loading"
                                   data-allowed-file-extensions='["mp4"]' data-show-upload="false">
                            <strong> Allowed filetypes are mp4. Max upload size allowed is 50M."</strong>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="term_name" class="col-sm-2 control-label">Summary</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <textarea class="form-control" rows="3" cols="60" id="term_name"
                                          name="term_name"></textarea>
                            </div>
                        </div>
                    </div>
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
