<div id="View-news-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-policy-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"> </h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label"> News Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="name" name="name" value=""
                                   placeholder="Enter name" required disabled="">
                        </div>
                    </div>
                    {{--<div class="form-group">--}}
                        {{--<label for="path" class="col-sm-2 control-label">Description</label>--}}
                        {{--<div class="col-sm-8">--}}
                            {{--<input type="text" class="form-control" id="description" name="description" value=""--}}
                                   {{--placeholder="Enter Description" required>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    <hr class="hr-text" data-content="">
                    <div class="form-group{{ $errors->has('summary') ? ' has-error' : '' }}">
                            <textarea class="form-control" id="summary" name="summary" placeholder="summary" rows="10"
                            >{{ old('summary') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>