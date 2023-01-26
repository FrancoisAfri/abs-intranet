<div id="add-training-docs-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-training-docs-form" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add New Document</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="name" name="name" value=""
                                   placeholder="Enter name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="description" name="description" value=""
                                   placeholder="Enter Description" required>
                        </div>
                    </div>
                    <hr class="hr-text" data-content="DOCUMENTS UPLOAD">
                    <div class="form-group supDoc-field{{ $errors->has('documents') ? ' has-error' : '' }}">
                        <label for="documents" class="col-sm-2 control-label">Document </label>
                        <div class="col-sm-8">
                            <input type="file" id="document" name="document"
                                   class="file file-loading" data-allowed-file-extensions='["pdf", "docx", "doc","txt"]'
                                   data-show-upload="false">
                        </div>
                    </div>
                    <hr class="hr-text" data-content="Add Policy Users">
                    @foreach($division_levels as $division_level)
                        <div class="form-group manual-field{{ $errors->has('division_level_' . $division_level->level) ? ' has-error' : '' }}">
                            <label for="{{ 'division_level_' . $division_level->level }}"
                                   class="col-sm-2 control-label">{{ $division_level->name }}</label>
                            <div class="col-sm-8">
                                    <select id="{{ 'division_level_' . $division_level->level }}"
                                            name="{{ 'division_level_' . $division_level->level }}"
                                            class="form-control"
                                            onchange="divDDOnChange(this, null, 'add-training-docs-modal')">
                                    </select>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add-training-doc" class="btn btn-warning"><i class="fa fa-cloud-upload"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
           