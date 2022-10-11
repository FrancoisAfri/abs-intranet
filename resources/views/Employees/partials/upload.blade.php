<div id="upload-file-modal" class="modal modal-default fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="upload-asset-form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">File Upload</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>


                    <input type="hidden" value="{{ Auth::user()->id }}" name="user_id" id="user_id">

                    <input type="hidden" value="Un Allocated" name="asset_status" id="asset_status">

                    <input type="hidden" value="{{ $asset->id}}" name="asset_id" id="asset_id">

                    <input type="hidden" value="{{ date('Y-m-d') }}" name="date_added" id="asset_id">


                    <div class="form-group">
                        <label for="image" class="col-sm-2 control-label">File</label>
                        <div class="col-sm-8">

                            <input type="file" id="document" name="document"  class="file file-loading"
                                   data-allowed-file-extensions='["jpg", "jpeg", "png", "gif", "doc","pdf",
                                   "xls", "xlsx", "txt", "lic", "xml", "zip", "rtf","rar"
                                 ]' data-show-upload="false" >
                            <strong> Allowed filetypes are png, gif,
                                jpg, jpeg, doc, docx, pdf, xls, xlsx, txt, lic, xml, zip, rtf and
                                rar. Max upload size allowed is 2M."</strong>
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('description') ? ' has-error' : '' }}">
                        <label for="description" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <textarea rows="4" cols="50" class="form-control" id="description" name="description" placeholder="Enter Description">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="upload-asset" class="btn btn-warning"><i
                                class="fa fa-cloud-upload"></i> Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


