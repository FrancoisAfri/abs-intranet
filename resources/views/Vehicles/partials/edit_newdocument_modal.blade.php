<div id="edit-newdoc-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" name="edit-newdoc-form" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Documnt Model</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                    {{--<div class="form-group{{ $errors->has('upload_type') ? ' has-error' : '' }}">--}}
                        {{--<label for="upload_type" class="col-sm-3 control-label"> Upload Type</label>--}}

                        {{--<div class="col-sm-8">--}}
                            {{--<label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_single"--}}
                                                                                          {{--name="upload_type" value="1"--}}
                                                                                          {{--checked>--}}
                                {{--Bulk </label>--}}
                            {{--<label class="radio-inline"><input type="radio" id="rdo_bulke" name="upload_type" value="2">--}}
                                {{--Safe--}}
                            {{--</label>--}}
                        {{--</div>--}}

                    {{--</div>--}}
                    <div class="form-group">
                        <label for="type" class="col-sm-2 control-label"> Type</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-filter"></i>
                                </div>

                                <select id="type" name="type" class="form-control">
                                    <option value="0">*** Select Document Type ***</option> 
                                    @foreach($documentTypes as $documentType)
                                    <option value="{{ $documentType->id }}">{{ $documentType->name}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group zip-field">
                        <label for="days" class="col-sm-2 control-label">Note</label>
                        <div class="col-sm-8">

                            <textarea class="form-control" id="descriptionss" name="descriptionss"
                                      placeholder="Please make sure you zip the files you wish to upload and then upload the zip file. The files in zip file will then be uploaded..."
                                      rows="3" readonly="">{{ old('description') }}</textarea>

                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="path" class="col-sm-2 control-label">Description </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="description" name="description"
                                   value=" " placeholder="Enter  Description ...">
                        </div>
                    </div>
                    <div class="form-group supDoc-field{{ $errors->has('documents') ? ' has-error' : '' }}">
                        <label for="documents" class="col-sm-2 control-label">Upload </label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-file-pdf-o"></i>
                                </div>
                                <input type="file" id="documents" name="documents"
                                       class="file file-loading" data-allowed-file-extensions='["pdf", "docx", "doc", "xlsx"]'
                                       data-show-upload="false">
								<a class="btn btn-default btn-flat btn-block btn-xs" href="http://afrixcel.co.za/"
									target="_blank"><i class="fa fa-file-pdf-o"></i>Document</a>	   
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="date_froms" class="col-sm-2 control-label"> Date From</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="date_froms" name="date_from"
                                   value="{{ old('date_froms') }}" placeholder="Select Date From ...">
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="exp_dates" class="col-sm-2 control-label"> Expiry Date </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="exp_dates" name="exp_date"
                                   value="{{ old('exp_dates') }}" placeholder="Select Expiry date ...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="edit_doc" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
        
           