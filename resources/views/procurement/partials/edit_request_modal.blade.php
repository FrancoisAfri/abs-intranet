<div id="edit-request-modal" class="modal modal-default fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-request-form">
				<input type="hidden" name="file_index" id="file_index" value="1"/>
				<input type="hidden" name="total_files" id="total_files" value="1"/>
				{{ csrf_field() }}
				{{ method_field('PATCH') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Request</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
					<div class="form-group">
                        <label for="title_name" class="col-sm-2 control-label">Title</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="title_name" name="title_name"
                                   value="" placeholder="Enter Request Title">
                        </div>
                    </div>
					<div class="form-group">
                        <label for="employee_id" class="col-sm-2 control-label">Current User</label>
                        <div class="col-sm-8">
                            <select id="employee_id" name="employee_id" style="width: 100%;" class="form-control">
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{$employee->first_name . ' ' .  $employee->surname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="date" class="col-sm-2 control-label"> On Behalf Of</label>
                        <div class="col-sm-8">
                            <input type="checkbox" id="on_behalf" value="1" name="on_behalf"
							{{ ($procurement->title_type === 1) ? ' checked' : '' }}
                                   onclick="showHide();">
                        </div>
                    </div>
                    <div class="form-group on_behalf_field">
                        <label for="on_behalf_employee_id" class="col-sm-2 control-label">Employees</label>
                        <div class="col-sm-8">
                            <select id="on_behalf_employee_id" name="on_behalf_employee_id"
							style="width: 100%;" class="form-control">
                                <option value="0">*** Select an Employees ***</option>
                                @foreach($employeesOnBehalf as $employeeOnBehalf)
                                    <option value="{{ $employeeOnBehalf->id }}">{{ $employeeOnBehalf->first_name . ' ' .  $employeeOnBehalf->surname}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
					<div class="form-group">
                        <label for="request_remarks" class="col-sm-2 control-label">Remark</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="request_remarks" name="request_remarks"
									placeholder="Enter a Remark" rows="3"></textarea>
                        </div>
                    </div>
					<div id="tab_10">
						<hr class="hr-text" data-content="Items">
						<div class="row" id="tab_tab">
							<div class="col-sm-6" id="product_row" style="margin-bottom: 15px; display:none">
								<select class="form-control" style="width: 100%;"
                                    id="product_id" name="product_id" disabled="disabled">
									<option value="0">*** Select a Product  ***</option>
									@foreach($products as $product)
										<option value="{{ $product->id }}">{{ $product->name }}</option>
									@endforeach
								</select>
							</div>
							<div class="col-sm-6"  id="1" name="1" style="margin-bottom: 15px;">
								<select class="form-control" style="width: 100%;" id="product_id" 
								name="product_id[1]">
									<option value="0">*** Select a Product  ***</option>
									@foreach($products as $product)
										<option value="{{ $product->id }}">{{ $product->name }}</option>
									@endforeach
								</select>
							</div>
							<div class="col-sm-6" style="display:none;" id="quantity_row">
								<input type="text" class="form-control" id="quantity" name="quantity"
									   placeholder="Please Enter Quantity" disabled="disabled">
							</div>
							<div class="col-sm-6" id="1" name="1" style="margin-bottom: 15px;">
								<input type="text" class="form-control" id="quantity[1]" name="quantity[1]"
									   placeholder="Please Enter Quantity">
							</div>
						</div>
						<div class="row" id="final_row">
							<div class="col-sm-12">
								<button type="button" class="btn btn-default btn-block btn-flat add_more" onclick="addFile()">
									<i class="fa fa-clone"></i> Add More
								</button>
							</div>
						</div>
					</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="update_request" class="btn btn-warning"><i
                                class="fa fa-cloud-upload"></i> Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>         