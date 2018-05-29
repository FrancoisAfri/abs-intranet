<div id="add-jobparts-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-jobparts-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Job Card Parts </h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    @if (count($errors) > 0)
                        <div class="alert alert-danger alert-dismissible fade in">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-ban"></i> Invalid Input Data!</h4>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group{{ $errors->has('product_id') ? ' has-error' : '' }}">
                        <label for="{{ 'product_id' }}" class="col-sm-2 control-label">Product Category </label>

                        <div class="col-sm-8">
                            <select id="product_id" name="product_id" class="form-control select2" style="width: 100%;"
                                    onchange="productcategoryDDOnChange(this)">
                                <option value="">*** Please Select a Product Category ***</option>
                                <option value="0"></option>
                                @foreach($jobCategories as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
                        <label for="{{ 'category_id' }}" class="col-sm-2 control-label">Product </label>

                        <div class="col-sm-8">
                            <select id="category_id" name="category_id" class="form-control select2"
                                    style="width: 100%;">
                                <option value="">*** Please Select a Category First ***</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="no_of_parts_used" class="col-sm-2 control-label">Number of Parts</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="no_of_parts_used" name="no_of_parts_used"
                                   value=""
                                   placeholder="" required>
                        </div>
                    </div>
                    <input type="hidden" id="jobcard_card_id" name="jobcard_card_id"
                           value="{{ !empty($jobcardparts->id) ? $jobcardparts->id : ''}}">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add_jobparts" class="btn btn-warning"><i class="fa fa-cloud-upload"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
           