<div id="add-video-modal" class="modal modal-default fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-videos-form" enctype="multipart/form-data">

                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Video</h4>
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

                    <div class="form-group {{ $errors->has('description') ? ' has-error' : '' }}">
                        <label for="description" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <textarea rows="4" cols="50" class="form-control" id="description" name="description"
                                          placeholder="Enter Description">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">File</label>
                        <div class="col-sm-8">

                            <input type="file" id="path" name="path" class="file file-loading"
                                   data-allowed-file-extensions='["mp4"]' data-show-upload="false">
                            <strong> Allowed filetypes are mp4. Max upload size allowed is 50M."</strong>
                        </div>
                    </div>

{{--                    @foreach($division_levels as $division_level)--}}
{{--                        <div class="form-group manual-field{{ $errors->has('division_level_' . $division_level->level) ? ' has-error' : '' }}">--}}
{{--                            <label for="{{ 'division_level_' . $division_level->level }}"--}}
{{--                                   class="col-sm-2 control-label">{{ $division_level->name }}</label>--}}
{{--                            <div class="col-sm-8">--}}
{{--                                <div class="input-group">--}}
{{--                                    <div class="input-group-addon">--}}
{{--                                        <i class="fa fa-black-tie"></i>--}}
{{--                                    </div>--}}
{{--                                    <select id="{{ 'division_level_' . $division_level->level }}"--}}
{{--                                            name="{{ 'division_level_' . $division_level->level }}" class="form-control"--}}
{{--                                            onchange="divDDOnChange(this, null, 'view_users')">--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    @endforeach--}}

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add-video" class="btn btn-warning"><i
                                class="fa fa-cloud-upload"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>




