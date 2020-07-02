<div id="add-document-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-module-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

               <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add New Document Type</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                        <div class="form-group user-field{{ $errors->has('doc_type_id') ? ' has-error' : '' }}">
                            <label for="doc_type_id" class="col-sm-2 control-label">Document Type</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-files-o"></i>
                                    </div>
                                    <select class="form-control select2" style="width: 100%;">
                                        <option value="">*** Select a Document Type ***</option>
                                        @foreach($doc_type as $document)
                                    <option id="doc_type" name="doc_type" value="{{ $document->id }}">{{ $document->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                         <div class="form-group supDoc-field{{ $errors->has('supporting_docs') ? ' has-error' : '' }}">
                        <label for="days" class="col-sm-2 control-label">Supporting Document</label>
                            <div class="col-sm-8">
                               <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-upload"></i>
                                    </div>
                                    <input type="file" id="supporting_docs" name="supporting_docs" class="file file-loading" data-allowed-file-extensions='["pdf", "docx", "doc"]' data-show-upload="false">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                        <label for="date_from" class="col-sm-2 control-label">Date From</label>

                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control datepicker" id="date_from" name="date_from" value="{{ old('date_from') }}" placeholder="Click to Select a Date...">
                            </div>
                        </div>
                    </div>

                     <div class="form-group">
                        <label for="exp_date" class="col-sm-2 control-label">Expiry Date</label>

                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control datepicker" id="exp_date" name="exp_date" value="{{ old('exp_date') }}" placeholder="Click to Select a Date...">
                            </div>
                        </div>
                    </div>


                     <!-- <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="name" name="name" value="" placeholder="Enter Name" required>
                        </div>
                    </div><br><br> -->
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Doc Description</label>
                             <div class="col-sm-8">
                            <input type="text" class="form-control" id="description" name="description" value="" placeholder="Enter Description" required>
                        </div>
                           </div>
                             </div>  
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="save_doc" class="btn btn-primary"><i class="fa fa-cloud-upload"></i> Upload</button>
                </div>
             </form>
            </div>
         </div>
        </div>
     </div>
           