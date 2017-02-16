<div id="set-rates-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" name="set-rates-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Set Interest Rate</h4>
                </div>
                <div class="modal-body">
                    <div id="prime-rate-invalid-input-alert"></div>
                    <div id="prime-rate-success-alert"></div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="prime_rate">Prime</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                                    <input type="text" class="form-control" name="prime_rate" id="prime_rate" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div id="plus_minus_group" class="form-group">
                                <label for="plus_minus">Plus/Minus</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-plus"></i></span>
                                    <select class="form-control" name="plus_minus" id="plus_minus">
                                        <option value="+">Plus</option>
                                        <option value="-">Minus</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="variable_rate">Variable</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                                    <input type="number" class="form-control" name="variable_rate" id="variable_rate" placeholder="0.00" min="0" max="40" step="0.50">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="loan_interest_rate">Interest Rate</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                                    <input type="text" class="form-control" name="loan_interest_rate" id="loan_interest_rate" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="submit-rates" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>