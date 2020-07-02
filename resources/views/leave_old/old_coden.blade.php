<div class="row emp-field" style="display: block;">
                        <div class="col-xs-6">
                            <div class="form-group Sick-field {{ $errors->has('date_from') ? ' has-error' : '' }}">
                                <label for="date_from" class="col-sm-4 control-label">Available Neg Annual Days:</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" id="negannual" class="form-control pull-left" name="val" value=" {{$negannualDays}}" disabled="true">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group neg-field {{ $errors->has('date_to') ? ' has-error' : '' }}">
                                <label for="date_to" class="col-sm-3 control-label">Available Neg Sick Days:</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" id="negsick" class="form-control pull-left" name="val" value=" {{$negsickDays}}" disabled="true">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>