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
                        <label for="path" class="col-sm-2 control-label"> Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="name" name="name" value=""
                                   placeholder="Enter Name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label"> Description</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="description" name="description" value=""
                                   placeholder="Enter Description" required>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="exp_date" class="col-sm-2 control-label">Date </label>

                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control datepicker" id="exp_date" name="exp_date"
                                       value="{{ old('exp_date') }}" placeholder="Click to Select a Date...">
                            </div>
                        </div>
                    </div>
                    @foreach($division_levels as $division_level)
                        <div class="form-group{{ $errors->has('division_level_' . $division_level->level) ? ' has-error' : '' }}">
                            <label for="{{ 'division_level_' . $division_level->level }}"
                                   class="col-sm-2 control-label">{{ $division_level->name }}</label>

                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-black-tie"></i>
                                    </div>
                                    <select id="{{ 'division_level_' . $division_level->level }}"
                                            name="{{ 'division_level_' . $division_level->level }}"
                                            class="form-control select2"
                                            onchange="divDDOnChange(this, null, 'add-news-modal')"
                                            style="width: 100%;">
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="form-group zip-field">
                        <label for="image" class="col-sm-2 control-label">Upload a Picture</label>

                        <div class="col-sm-8">

                            <input type="file" id="image" name="image" class="file file-loading"
                                   data-allowed-file-extensions='["jpg", "jpeg", "png"]' data-show-upload="false">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="term_name" class="col-sm-2 control-label">Summary</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <textarea class="form-control" rows="3" cols="70" id="term_name" name="term_name"></textarea>
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
           